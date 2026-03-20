<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            if (!Schema::hasColumn('members', 'nature_of_work')) {
                $table->string('nature_of_work')->nullable()->after('address');
            }
            if (!Schema::hasColumn('members', 'employer_business_name')) {
                $table->string('employer_business_name')->nullable()->after('nature_of_work');
            }
            if (!Schema::hasColumn('members', 'date_of_employment')) {
                $table->date('date_of_employment')->nullable()->after('employer_business_name');
            }
            if (!Schema::hasColumn('members', 'tin_number')) {
                $table->string('tin_number')->nullable()->after('date_of_employment');
            }
            if (!Schema::hasColumn('members', 'sss_gsis_no')) {
                $table->string('sss_gsis_no')->nullable()->after('tin_number');
            }
            if (!Schema::hasColumn('members', 'profile_completed')) {
                $table->boolean('profile_completed')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $columns = ['nature_of_work', 'employer_business_name', 'date_of_employment', 'tin_number', 'sss_gsis_no', 'profile_completed'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('members', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
