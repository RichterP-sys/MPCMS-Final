<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id')->nullable();
            $table->enum('record_type', ['loan', 'contribution', 'repayment']);
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('receipt_number')->unique();
            $table->enum('receipt_status', ['pending', 'issued'])->default('pending');
            $table->timestamp('receipt_issued_at')->nullable();
            $table->timestamps();

            $table->index(['record_id', 'record_type']);
            $table->index('member_id');
            $table->index('receipt_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
