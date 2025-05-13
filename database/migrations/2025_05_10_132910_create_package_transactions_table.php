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
        Schema::create('package_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages', 'id');
            $table->string('customer_name');
            $table->string('class_type_name');
            $table->string('phone_num');
            $table->string('email')->nullable();
            $table->string('transaction_code');
            $table->float('price');
            $table->enum('payment_status', ['pending', 'failure', 'success']);
            $table->string('number_of_session');
            $table->string('number_of_session_left');
            $table->string('group_class_type');
            $table->boolean('is_trial');
            $table->date('valid_until')->nullable();
            $table->string('redeem_code')->nullable();
            $table->integer('duration');
            $table->enum('duration_unit', ['day', 'week', 'month', 'year']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_transactions');
    }
};
