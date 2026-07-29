<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add 3-tier approval workflow columns to all request tables.
     * Status flow: pending → approved_by_1 → approved_by_2 → completed | rejected
     */
    public function up(): void
    {
        $tables = [
            'vpn_requests',
            'internet_access_requests',
            'vm_requests',
            'web_hosting_requests',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $t) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'approval_status')) {
                    $t->enum('approval_status', [
                        'pending',
                        'approved_by_1',
                        'approved_by_2',
                        'completed',
                        'rejected',
                    ])->default('pending');
                }
                if (!Schema::hasColumn($tableName, 'approver1_email')) {
                    $t->string('approver1_email')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'approver1_name')) {
                    $t->string('approver1_name')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'approved_by_1_at')) {
                    $t->timestamp('approved_by_1_at')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'approver2_email')) {
                    $t->string('approver2_email')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'approver2_name')) {
                    $t->string('approver2_name')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'approved_by_2_at')) {
                    $t->timestamp('approved_by_2_at')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'citc_completed_at')) {
                    $t->timestamp('citc_completed_at')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'citc_completed_by')) {
                    $t->string('citc_completed_by')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'rejected_by')) {
                    $t->string('rejected_by')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'rejected_by_level')) {
                    $t->tinyInteger('rejected_by_level')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'rejection_reason')) {
                    $t->text('rejection_reason')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'rejected_at')) {
                    $t->timestamp('rejected_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'vpn_requests',
            'internet_access_requests',
            'vm_requests',
            'web_hosting_requests',
        ];

        $columns = [
            'approval_status',
            'approver1_email', 'approver1_name', 'approved_by_1_at',
            'approver2_email', 'approver2_name', 'approved_by_2_at',
            'citc_completed_at', 'citc_completed_by',
            'rejected_by', 'rejected_by_level', 'rejection_reason', 'rejected_at',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $t) use ($tableName, $columns) {
                foreach ($columns as $col) {
                    if (Schema::hasColumn($tableName, $col)) {
                        $t->dropColumn($col);
                    }
                }
            });
        }
    }
};
