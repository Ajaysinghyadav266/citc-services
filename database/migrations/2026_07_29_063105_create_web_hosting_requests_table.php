<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_hosting_requests', function (Blueprint $table) {
            $table->id();

            // Requester Details
            $table->string('institute_email');
            $table->string('department_name');
            $table->string('owner_name');
            $table->string('mobile_number');
            $table->string('employee_category');

            // Hosting Details
            $table->string('website_name');
            $table->string('suggested_domain_name');
            $table->string('operating_system');
            $table->text('purpose');

            // Additional Comment
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_hosting_requests');
    }
};