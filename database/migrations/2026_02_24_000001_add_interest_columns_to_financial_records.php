<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = Schema::hasTable('financial_records') ? 'financial_records' : 'loans';

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (!Schema::hasColumn($tableName, 'interest_rate')) {
                $table->decimal('interest_rate', 5, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn($tableName, 'interest_amount')) {
                $table->decimal('interest_amount', 15, 2)->nullable()->after('interest_rate');
            }
            if (!Schema::hasColumn($tableName, 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->nullable()->after('interest_amount');
            }
            if (!Schema::hasColumn($tableName, 'monthly_repayment')) {
                $table->decimal('monthly_repayment', 15, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn($tableName, 'term_months')) {
                $table->unsignedInteger('term_months')->nullable()->after('loan_term');
            }
        });
    }

    public function down(): void
    {
        $tableName = Schema::hasTable('financial_records') ? 'financial_records' : 'loans';

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            $cols = ['interest_rate', 'interest_amount', 'total_amount', 'monthly_repayment', 'term_months'];
            foreach ($cols as $col) {
                if (Schema::hasColumn($tableName, $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
