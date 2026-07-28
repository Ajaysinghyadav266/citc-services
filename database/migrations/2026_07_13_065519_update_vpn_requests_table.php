<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vpn_requests', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('email');

            $table->string('contact')->nullable();
            $table->string('operating_system')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('purpose')->nullable();
            $table->text('resources')->nullable();

            $table->string('approver_email')->nullable();
            $table->string('approver_name')->nullable();
            $table->string('approver_designation')->nullable();
            $table->string('approver_department')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vpn_requests');
    }
};