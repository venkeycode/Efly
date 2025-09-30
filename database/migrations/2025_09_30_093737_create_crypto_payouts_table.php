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
        Schema::create('crypto_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('token');           // contract address
            $table->string('chain');           // 'bsc'
            $table->string('from');            // admin address
            $table->string('to');              // customer
            $table->string('amount');          // human decimal
            $table->string('amount_wei');      // as string
            $table->string('tx_hash')->nullable();
            $table->string('status')->default('pending'); // pending|confirmed|failed
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crypto_payouts');
    }
};
