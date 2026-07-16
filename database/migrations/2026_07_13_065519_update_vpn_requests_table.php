<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vpn_requests', function (Blueprint $table) {

            $table->string('contact')->nullable();
            $table->string('operating_system')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('purpose')->nullable();
            $table->text('resources')->nullable();

            $table->string('faculty_name')->nullable();
            $table->string('faculty_email')->nullable();
            $table->string('department')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('vpn_requests', function (Blueprint $table) {

            $table->dropColumn([
                'contact',
                'operating_system',
                'start_date',
                'end_date',
                'purpose',
                'resources',
                'faculty_name',
                'faculty_email',
                'department'
            ]);

        });
    }
};
