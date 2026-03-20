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
            if (!Schema::hasColumn($tableName, 'repayment_details')) {
                $table->json('repayment_details')->nullable()->after('repayment_method');
            }
        });
    }

    public function down(): void
    {
        $tableName = Schema::hasTable('financial_records') ? 'financial_records' : 'loans';

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (Schema::hasColumn($tableName, 'repayment_details')) {
                $table->dropColumn('repayment_details');
            }
        });
    }
};
