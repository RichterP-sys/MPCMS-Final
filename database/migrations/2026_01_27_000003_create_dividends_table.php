<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dividends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->decimal('total_contributions', 15, 2)->default(0);
            $table->decimal('dividend_rate', 8, 4)->default(0);
            $table->decimal('dividend_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'released'])->default('pending');
            $table->timestamp('released_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['member_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dividends');
    }
};
