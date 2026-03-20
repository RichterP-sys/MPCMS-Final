<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up()
	{
		// Drop columns only if they exist to avoid errors on environments where they were never created
		if (Schema::hasColumn('loans', 'interest_rate')) {
			Schema::table('loans', function (Blueprint $table) {
				$table->dropColumn('interest_rate');
			});
		}
		if (Schema::hasColumn('loans', 'term_months')) {
			Schema::table('loans', function (Blueprint $table) {
				$table->dropColumn('term_months');
			});
		}
		if (Schema::hasColumn('loans', 'due_date')) {
			Schema::table('loans', function (Blueprint $table) {
				$table->dropColumn('due_date');
			});
		}
	}

	public function down()
	{
		Schema::table('loans', function (Blueprint $table) {
			$table->decimal('interest_rate', 5, 2)->nullable(false);
			$table->integer('term_months')->nullable(false);
			$table->date('due_date')->nullable();
		});
	}
};


