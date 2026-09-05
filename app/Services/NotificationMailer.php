<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

/**
 * Centralised email notification helper for CITC Services.
 *
 * All four request types share the same notification events:
 *   1. On submission  → requester + approver (L1) in CC
 *   2. After L1 approves → Dean IT (L2) notified
 *   3. After CITC completes → requester notified of fulfilment
 *   4. On rejection (any level) → requester notified
 */
class NotificationMailer
{
    /** L2 Dean IT email — from .env or fallback */
    public static function deanEmail(): string
    {
        return env('CITC_HEAD_EMAIL', 'ftest@iiti.ac.in');
    }

    // ── 1. SUBMITTED ──────────────────────────────────────────────
    /**
     * Notify the requester that their request was submitted (email 1),
     * and send a separate action-required email to the L1 approver (email 2).
     */
    public static function sendSubmitted(
        string $requesterName,
        string $requesterEmail,
        string $serviceType,        // e.g. "VPN", "Internet Access"
        string $approverEmail,
        string $approverName
    ): void {

        // ── Email 1: Requester confirmation ──────────────────────
        $subjectUser = "[IIT Indore] {$serviceType} Request Submitted";

        $bodyUser = self::wrap("
            <p>Dear <strong>{$requesterName}</strong>,</p>

            <p>Your <strong>{$serviceType}</strong> request has been submitted successfully and is now
            <span style='color:#d97706;font-weight:600;'>Pending Approval</span> from your approver.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Assigned Approver</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$approverName} &lt;{$approverEmail}&gt;</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Next Step</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>Your approver will review and forward the request to the Dean IT.</td>
                </tr>
            </table>

            <p style='margin-top:20px;'>You will receive another email once your request moves to the next stage.</p>
            <p>If you have questions, please contact the CITC Team.</p>
        ", $subjectUser);

        self::sendAsync($requesterEmail, $subjectUser, $bodyUser, 'sendSubmitted (user)');

        // ── Email 2: L1 Approver action required ─────────────────
        $subjectApprover = "[IIT Indore] Action Required: New {$serviceType} Request";

        $bodyApprover = self::wrap("
            <p>Dear <strong>{$approverName}</strong>,</p>

            <p>A new <strong>{$serviceType}</strong> request has been submitted by
            <strong>{$requesterName}</strong> and requires <strong>your approval</strong>.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Submitted by</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$requesterName} &lt;{$requesterEmail}&gt;</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Your Role</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>Level 1 Approver — please review and approve or reject this request.</td>
                </tr>
            </table>

            <p style='margin-top:20px;'>
                <a href='" . config('app.url') . "/approver-login'
                   style='display:inline-block;background:#1d4ed8;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:600;'>
                    Go to Approver Dashboard →
                </a>
            </p>
        ", $subjectApprover);

        self::sendAsync($approverEmail, $subjectApprover, $bodyApprover, 'sendSubmitted (approver)');
    }

    // ── 2. L1 APPROVED → NOTIFY L2 DEAN ──────────────────────────
    /**
     * After L1 approves, notify the Dean IT (L2) that a request
     * is waiting for their review.
     */
    public static function sendApprovedByL1(
        string $requesterName,
        string $requesterEmail,
        string $serviceType,
        string $approver1Name,
        string $approver1Email
    ): void {
        $deanEmail = self::deanEmail();
        $subject   = "[IIT Indore] Action Required: {$serviceType} Request — L1 Approved";

        $body = self::wrap("
            <p>Dear Dean IT,</p>

            <p>A <strong>{$serviceType}</strong> request has been approved by the Level 1 approver
            and is now awaiting <strong>your review and approval</strong>.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Requester</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$requesterName} &lt;{$requesterEmail}&gt;</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Approved by L1</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$approver1Name} &lt;{$approver1Email}&gt;</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Your Action</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>Please log in to the Approver Dashboard to review and approve/reject this request.</td>
                </tr>
            </table>

            <p style='margin-top:20px;'>
                <a href='" . config('app.url') . "/approver-login'
                   style='display:inline-block;background:#1d4ed8;color:#fff;padding:10px 22px;border-radius:8px;text-decoration:none;font-weight:600;'>
                    Go to Approver Dashboard →
                </a>
            </p>
        ", $subject);

        self::sendAsync($deanEmail, $subject, $body, 'sendApprovedByL1');
    }

    // ── 3. CITC COMPLETED → NOTIFY REQUESTER ─────────────────────
    /**
     * After CITC marks a request complete, notify the requester
     * that their service is now active/fulfilled.
     */
    public static function sendCompleted(
        string $requesterName,
        string $requesterEmail,
        string $serviceType
    ): void {
        $subject = "[IIT Indore] ✅ {$serviceType} Request Fulfilled";

        $body = self::wrap("
            <p>Dear <strong>{$requesterName}</strong>,</p>

            <p>Great news! Your <strong>{$serviceType}</strong> request has been
            <span style='color:#16a34a;font-weight:700;'>fully approved and processed</span> by the CITC Team.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Status</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>
                        <span style='color:#16a34a;font-weight:700;'>✅ Completed</span>
                    </td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>What's Next</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>The requested service has been set up. Please allow a few minutes for it to take effect.</td>
                </tr>
            </table>

            <p style='margin-top:20px;'>
                If you face any issues, please contact the CITC Team at
                <a href='mailto:" . env('MAIL_FROM_ADDRESS') . "'>" . env('MAIL_FROM_ADDRESS') . "</a>.
            </p>
        ", $subject);

        self::sendAsync($requesterEmail, $subject, $body, 'sendCompleted');
    }

    // ── 4. REJECTED → NOTIFY REQUESTER ───────────────────────────
    /**
     * When a request is rejected at any level, notify the requester
     * with the reason and CITC contact info.
     */
    public static function sendRejected(
        string $requesterName,
        string $requesterEmail,
        string $serviceType,
        string $reason,
        int    $rejectedByLevel
    ): void {
        $subject = "[IIT Indore] ❌ {$serviceType} Request Rejected";

        $levelName = match($rejectedByLevel) {
            1 => 'Level 1 Approver (Faculty/Staff)',
            2 => 'Dean IT Infrastructure (Level 2)',
            3 => 'CITC Team (Level 3)',
            default => "Level {$rejectedByLevel}",
        };

        $body = self::wrap("
            <p>Dear <strong>{$requesterName}</strong>,</p>

            <p>We regret to inform you that your <strong>{$serviceType}</strong> request has been
            <span style='color:#dc2626;font-weight:700;'>rejected</span>.</p>

            <table style='border-collapse:collapse;width:100%;margin-top:16px;'>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;width:160px;'>Service</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$serviceType}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Rejected By</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;'>{$levelName}</td>
                </tr>
                <tr>
                    <td style='padding:8px 12px;background:#f3f4f6;font-weight:600;border:1px solid #e5e7eb;'>Reason</td>
                    <td style='padding:8px 12px;border:1px solid #e5e7eb;color:#dc2626;'>" . htmlspecialchars($reason) . "</td>
                </tr>
            </table>

            <div style='margin-top:20px;padding:14px 18px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;'>
                <p style='margin:0;font-weight:600;color:#991b1b;'>Need help or want to re-apply?</p>
                <p style='margin:6px 0 0;color:#7f1d1d;'>
                    Please contact the CITC Team at
                    <a href='mailto:" . env('MAIL_FROM_ADDRESS') . "' style='color:#1d4ed8;'>" . env('MAIL_FROM_ADDRESS') . "</a>
                    for clarification or to resubmit your request.
                </p>
            </div>
        ", $subject);

        self::sendAsync($requesterEmail, $subject, $body, 'sendRejected');
    }

    // ── ASYNC MAIL DISPATCHER ─────────────────────────────────────
    /**
     * Dispatch a mail send AFTER the HTTP response has been sent to the browser.
     * The page loads instantly; the SMTP handshake happens in the background.
     */
    private static function sendAsync(
        string $to,
        string $subject,
        string $body,
        string $context = 'mail'
    ): void {
        dispatch(function () use ($to, $subject, $body, $context) {
            try {
                Mail::html($body, function ($msg) use ($to, $subject) {
                    $msg->to($to)->subject($subject);
                });
            } catch (\Exception $e) {
                \Log::warning("NotificationMailer::{$context} failed: " . $e->getMessage());
            }
        })->afterResponse();
    }

    // ── SHARED HTML WRAPPER ───────────────────────────────────────
    private static function wrap(string $content, string $title): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$title}</title>
</head>
<body style="margin:0;padding:0;background:#f9fafb;font-family:'Segoe UI',Arial,sans-serif;color:#1f2937;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;padding:40px 0;">
  <tr>
    <td align="center">
      <table width="620" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">

        <!-- Header -->
        <tr>
          <td style="background:linear-gradient(135deg,#1e3a8a 0%,#1d4ed8 100%);padding:28px 36px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <p style="margin:0;color:#bfdbfe;font-size:12px;letter-spacing:1px;text-transform:uppercase;">IIT Indore — CITC Services</p>
                  <h1 style="margin:6px 0 0;color:#fff;font-size:20px;font-weight:700;">{$title}</h1>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Body -->
        <tr>
          <td style="padding:32px 36px;font-size:15px;line-height:1.7;color:#374151;">
            {$content}
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f3f4f6;border-top:1px solid #e5e7eb;padding:20px 36px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#9ca3af;">
              This is an automated notification from the CITC Services Portal, IIT Indore.<br>
              Please do not reply to this email. For queries contact CITC Team.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>
HTML;
    }
}
