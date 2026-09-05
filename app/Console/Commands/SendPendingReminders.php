<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\VpnRequest;
use App\Models\InternetAccessRequest;
use App\Models\VmRequest;
use App\Models\WebHostingRequest;

class SendPendingReminders extends Command
{
    protected $signature   = 'citc:send-reminders
                                {--hours=24 : Hours a request must be stale before a reminder is sent}';
    protected $description = 'Send reminder emails to approvers for requests pending for too long.';

    /** Maps model class → friendly service label */
    private array $models = [
        VpnRequest::class            => 'VPN',
        InternetAccessRequest::class => 'Internet Access',
        VmRequest::class             => 'VM Machine',
        WebHostingRequest::class     => 'Web Hosting',
    ];

    public function handle(): int
    {
        $hours     = (int) $this->option('hours');
        $threshold = Carbon::now()->subHours($hours);
        $deanEmail = env('CITC_HEAD_EMAIL', 'ftest@iiti.ac.in');

        $totalL1 = 0;
        $totalL2 = 0;

        foreach ($this->models as $modelClass => $label) {

            // ── LEVEL 1: requests still 'pending' for > $hours ────
            $staleL1 = $modelClass::where('approval_status', 'pending')
                ->where('created_at', '<=', $threshold)
                ->whereNotNull('approver_email')
                ->get();

            foreach ($staleL1 as $rec) {
                $requesterName  = $rec->name  ?? $rec->owner_name  ?? 'Requester';
                $requesterEmail = $rec->email ?? $rec->institute_email ?? '—';
                $hoursStale     = (int) Carbon::parse($rec->created_at)->diffInHours(Carbon::now());

                $this->sendReminderL1(
                    $rec->approver_email,
                    $rec->approver_name ?? 'Approver',
                    $label,
                    $requesterName,
                    $requesterEmail,
                    $hoursStale
                );
                $totalL1++;
                $this->line("  [L1 reminder] {$label} #{$rec->id} → {$rec->approver_email}");
            }

            // ── LEVEL 2: requests stuck at 'approved_by_1' for > $hours ──
            $staleL2 = $modelClass::where('approval_status', 'approved_by_1')
                ->where('approved_by_1_at', '<=', $threshold)
                ->get();

            foreach ($staleL2 as $rec) {
                $requesterName  = $rec->name  ?? $rec->owner_name  ?? 'Requester';
                $requesterEmail = $rec->email ?? $rec->institute_email ?? '—';
                $hoursStale     = (int) Carbon::parse($rec->approved_by_1_at)->diffInHours(Carbon::now());

                $this->sendReminderL2(
                    $deanEmail,
                    $label,
                    $requesterName,
                    $requesterEmail,
                    $rec->approver1_name ?? '—',
                    $hoursStale
                );
                $totalL2++;
                $this->line("  [L2 reminder] {$label} #{$rec->id} → {$deanEmail}");
            }
        }

        $this->info("Done. Sent {$totalL1} L1 reminder(s) and {$totalL2} L2 reminder(s).");
        return Command::SUCCESS;
    }

    // ── Reminder email to Level 1 approver ───────────────────────
    private function sendReminderL1(
        string $approverEmail,
        string $approverName,
        string $serviceType,
        string $requesterName,
        string $requesterEmail,
        int    $hoursStale
    ): void {
        $subject = "[IIT Indore] ⏰ Reminder: {$serviceType} Request Awaiting Your Approval";
        $body    = $this->wrap("
            <p>Dear <strong>{$approverName}</strong>,</p>

            <p>This is a friendly reminder that a <strong>{$serviceType}</strong> request submitted by
            <strong>{$requesterName}</strong> has been
            <span style='color:#d97706;font-weight:700;'>waiting for your approval for over {$hoursStale} hours</span>
            and requires your attention.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;'>Requester</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;'>{$requesterName} &lt;{$requesterEmail}&gt;</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;'>Waiting Since</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;color:#92400e;font-weight:600;'>{$hoursStale} hours</td>
                </tr>
            </table>

            <p style='margin-top:20px;'>
                Please log in to the Approver Dashboard to review and take action on this request.
            </p>
            <p>
                <a href='" . config('app.url') . "/approver-login'
                   style='display:inline-block;background:#d97706;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:600;'>
                    Review Request Now →
                </a>
            </p>
        ", $subject);

        $this->mailSend($approverEmail, $subject, $body);
    }

    // ── Reminder email to Level 2 Dean IT ────────────────────────
    private function sendReminderL2(
        string $deanEmail,
        string $serviceType,
        string $requesterName,
        string $requesterEmail,
        string $approver1Name,
        int    $hoursStale
    ): void {
        $subject = "[IIT Indore] ⏰ Reminder: {$serviceType} Request Awaiting Your Approval (L2)";
        $body    = $this->wrap("
            <p>Dear Dean IT,</p>

            <p>This is a reminder that a <strong>{$serviceType}</strong> request has already been
            approved by the Level 1 approver but has been
            <span style='color:#d97706;font-weight:700;'>waiting for your approval (Level 2) for over {$hoursStale} hours</span>.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;'>Requester</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;'>{$requesterName} &lt;{$requesterEmail}&gt;</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;'>Approved by L1</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;'>{$approver1Name}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#fef9c3;font-weight:600;border:1px solid #fde68a;'>Waiting Since</td>
                    <td style='padding:8px 12px;border:1px solid #fde68a;color:#92400e;font-weight:600;'>{$hoursStale} hours</td>
                </tr>
            </table>

            <p style='margin-top:20px;'>Please log in to the Approver Dashboard to review and approve or reject this request.</p>
            <p>
                <a href='" . config('app.url') . "/approver-login'
                   style='display:inline-block;background:#d97706;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:600;'>
                    Review Request Now →
                </a>
            </p>
        ", $subject);

        $this->mailSend($deanEmail, $subject, $body);
    }

    // ── Safe mail send ────────────────────────────────────────────
    private function mailSend(string $to, string $subject, string $body): void
    {
        try {
            Mail::html($body, fn($msg) => $msg->to($to)->subject($subject));
        } catch (\Exception $e) {
            Log::warning("SendPendingReminders mail failed [{$to}]: " . $e->getMessage());
            $this->warn("  ✗ Mail failed for {$to}: " . $e->getMessage());
        }
    }

    // ── Branded HTML wrapper ──────────────────────────────────────
    private function wrap(string $content, string $title): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>{$title}</title></head>
<body style="margin:0;padding:0;background:#f9fafb;font-family:'Segoe UI',Arial,sans-serif;color:#1f2937;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;padding:40px 0;">
  <tr><td align="center">
    <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">
      <tr>
        <td style="background:linear-gradient(135deg,#92400e 0%,#d97706 100%);padding:28px 36px;">
          <p style="margin:0;color:#fde68a;font-size:12px;letter-spacing:1px;text-transform:uppercase;">IIT Indore — CITC Services · Reminder</p>
          <h1 style="margin:6px 0 0;color:#fff;font-size:20px;font-weight:700;">{$title}</h1>
        </td>
      </tr>
      <tr>
        <td style="padding:32px 36px;font-size:15px;line-height:1.7;color:#374151;">
          {$content}
        </td>
      </tr>
      <tr>
        <td style="background:#f3f4f6;border-top:1px solid #e5e7eb;padding:20px 36px;text-align:center;">
          <p style="margin:0;font-size:12px;color:#9ca3af;">
            Automated reminder from CITC Services Portal, IIT Indore.<br>
            Do not reply to this email.
          </p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>
HTML;
    }
}
