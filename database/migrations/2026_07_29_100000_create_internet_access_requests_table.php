<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internet_access_requests', function (Blueprint $table) {

            $table->id();

            // Section 1: Personal & Academic Information
            $table->string('name');
            $table->string('roll_no')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();

            // Section 2: Approver Details (auto-fetched from ERP)
            $table->string('approver_email')->nullable();
            $table->string('approver_name')->nullable();
            $table->string('approver_designation')->nullable();
            $table->string('approver_department')->nullable();

            // Section 3: Hardware & Technical Profile
            $table->string('device_type')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('mac_address')->nullable();
            $table->enum('connection_duration', ['semester', 'annual', 'permanent'])->nullable();

            // Workflow state
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internet_access_requests');
    }
};
