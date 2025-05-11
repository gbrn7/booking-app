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
        Schema::create('package_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_transaction_id')->constrained('package_transactions');
            $table->foreignId('schedule_detail_id')->constrained('schedule_details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_schedules');
    }
};
