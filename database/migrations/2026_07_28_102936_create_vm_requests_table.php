<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vm_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();

            // Requester details
            $table->string('institute_email');
            $table->string('department_name');
            $table->string('owner_name');
            $table->string('mobile_number', 10);
            $table->enum('employee_category', ['faculty', 'staff', 'research_scholar', 'student', 'other']);

            // VM details
            $table->string('operating_system');
            $table->date('vm_expiry_date');
            $table->enum('os_type', ['32_bit', '64_bit']);
            $table->text('purpose_usage');
            $table->unsignedTinyInteger('cpu_cores');
            $table->unsignedSmallInteger('ram_gb');
            $table->text('justification');
            $table->unsignedInteger('hard_disk_gb');
            $table->enum('license_type', ['open_source', 'licensed', 'freeware']);
            $table->string('sub_domain')->nullable();
            $table->text('software_list');
            $table->enum('ssl_configuration', ['yes', 'no']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vm_requests');
    }
};