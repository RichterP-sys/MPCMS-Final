<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add new columns to loans for contribution data (idempotent)
        if (!Schema::hasColumn('loans', 'record_type')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->enum('record_type', ['loan', 'contribution'])->default('loan')->after('id');
                $table->enum('contribution_type', ['regular', 'special', 'emergency'])->nullable()->after('record_type');
                $table->date('contribution_date')->nullable()->after('contribution_type');
            });
        }

        // 2. Make application_date nullable for contributions (raw SQL to avoid doctrine/dbal)
        // Check current nullability - MySQL: COLUMN_TYPE from information_schema
        $col = DB::selectOne("SELECT IS_NULLABLE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'loans' AND COLUMN_NAME = 'application_date'");
        if ($col && $col->IS_NULLABLE === 'NO') {
            DB::statement('ALTER TABLE loans MODIFY application_date DATE NULL');
        }

        // 3. Migrate contributions into loans table
        $contributions = DB::table('contributions')->get();
        foreach ($contributions as $c) {
            DB::table('loans')->insert([
                'member_id' => $c->member_id,
                'amount' => $c->amount,
                'record_type' => 'contribution',
                'contribution_type' => $c->contribution_type,
                'contribution_date' => $c->contribution_date,
                'application_date' => $c->contribution_date,
                'status' => $c->status,
                'remaining_balance' => null,
                'approval_date' => null,
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at,
            ]);
        }

        // 4. Drop foreign key from loan_repayments before rename
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->dropForeign(['loan_id']);
        });

        // 5. Drop loan_collaterals FK if exists
        if (Schema::hasTable('loan_collaterals')) {
            Schema::table('loan_collaterals', function (Blueprint $table) {
                $table->dropForeign(['loan_id']);
            });
        }

        // 6. Rename loans to financial_records
        Schema::rename('loans', 'financial_records');

        // 7. Re-add foreign key to loan_repayments
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->foreign('loan_id')->references('id')->on('financial_records')->onDelete('cascade');
        });

        // 8. Re-add loan_collaterals FK
        if (Schema::hasTable('loan_collaterals')) {
            Schema::table('loan_collaterals', function (Blueprint $table) {
                $table->foreign('loan_id')->references('id')->on('financial_records')->onDelete('cascade');
            });
        }

        // 9. Drop contributions table
        Schema::dropIfExists('contributions');
    }

    public function down(): void
    {
        // Recreate contributions table
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('contribution_type', ['regular', 'special', 'emergency']);
            $table->date('contribution_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // Migrate contribution records back
        $contributions = DB::table('financial_records')->where('record_type', 'contribution')->get();
        foreach ($contributions as $c) {
            DB::table('contributions')->insert([
                'member_id' => $c->member_id,
                'amount' => $c->amount,
                'contribution_type' => $c->contribution_type,
                'contribution_date' => $c->contribution_date,
                'status' => $c->status,
                'created_at' => $c->created_at,
                'updated_at' => $c->updated_at,
            ]);
        }

        // Rename back and drop contribution columns
        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->dropForeign(['loan_id']);
        });

        Schema::rename('financial_records', 'loans');

        Schema::table('loan_repayments', function (Blueprint $table) {
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['record_type', 'contribution_type', 'contribution_date']);
        });
        DB::statement('ALTER TABLE loans MODIFY application_date DATE NOT NULL');
    }
};
