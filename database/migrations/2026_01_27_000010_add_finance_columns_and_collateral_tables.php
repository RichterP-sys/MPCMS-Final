<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Track loan balances for repayment + pool calculations.
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'remaining_balance')) {
                $table->decimal('remaining_balance', 15, 2)->nullable()->after('amount');
            }
        });

        // Track offsets applied to member contributions (used to settle defaults).
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'offset_contribution_amount')) {
                $table->decimal('offset_contribution_amount', 15, 2)->default(0)->after('status');
            }
        });

        // Store frozen contribution (implicit collateral) per-loan.
        if (!Schema::hasTable('loan_collaterals')) {
            Schema::create('loan_collaterals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade');
                $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
                $table->decimal('frozen_amount', 15, 2)->default(0);
                $table->timestamps();

                $table->unique('loan_id');
                $table->index(['member_id', 'loan_id']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('loan_collaterals')) {
            Schema::dropIfExists('loan_collaterals');
        }

        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'offset_contribution_amount')) {
                $table->dropColumn('offset_contribution_amount');
            }
        });

        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'remaining_balance')) {
                $table->dropColumn('remaining_balance');
            }
        });
    }
};

