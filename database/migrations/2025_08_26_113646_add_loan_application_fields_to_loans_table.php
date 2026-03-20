<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            // Personal Information
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('name_extension')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('maiden_middle_name')->nullable();
            $table->boolean('no_middle_name')->default(false);
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('mothers_maiden_name')->nullable();
            $table->string('nationality')->default('Filipino');
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Widowed', 'Separated', 'Divorced'])->nullable();
            $table->string('citizenship')->default('Filipino');
            $table->string('tin_number')->nullable();
            $table->string('sss_gsis_no')->nullable();

            // Contact Information
            $table->string('email')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('home_telephone')->nullable();
            $table->string('business_telephone')->nullable();

            // Present Home Address
            $table->string('unit_room_no')->nullable();
            $table->string('floor')->nullable();
            $table->string('building_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('block_no')->nullable();
            $table->string('phase_no')->nullable();
            $table->string('house_no')->nullable();
            $table->string('street_name')->nullable();
            $table->string('subdivision')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality_city')->nullable();
            $table->string('province_state_country')->nullable();
            $table->string('zip_code')->nullable();

            // Employment Information
            $table->string('nature_of_work')->nullable();
            $table->string('employer_business_name')->nullable();
            $table->string('employee_id')->nullable();
            $table->date('date_of_employment')->nullable();
            $table->string('source_of_fund')->nullable();

            // Employer/Business Address
            $table->string('emp_unit_room_no')->nullable();
            $table->string('emp_floor')->nullable();
            $table->string('emp_building_name')->nullable();
            $table->string('emp_lot_no')->nullable();
            $table->string('emp_block_no')->nullable();
            $table->string('emp_phase_no')->nullable();
            $table->string('emp_house_no')->nullable();
            $table->string('emp_street_name')->nullable();
            $table->string('emp_subdivision')->nullable();
            $table->string('emp_barangay')->nullable();
            $table->string('emp_municipality_city')->nullable();
            $table->string('emp_province_state_country')->nullable();
            $table->string('emp_zip_code')->nullable();

            // Loan Details
            $table->string('loan_purpose')->nullable();
            $table->string('other_purpose_specify')->nullable();
            $table->string('desired_loan_amount')->nullable();
            $table->decimal('other_amount_specify', 15, 2)->nullable();
            $table->string('loan_term')->nullable();
        });
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'last_name', 'first_name', 'name_extension', 'middle_name', 'maiden_middle_name',
                'no_middle_name', 'date_of_birth', 'place_of_birth', 'mothers_maiden_name',
                'nationality', 'sex', 'marital_status', 'citizenship', 'tin_number', 'sss_gsis_no',
                'email', 'cell_phone', 'home_telephone', 'business_telephone',
                'unit_room_no', 'floor', 'building_name', 'lot_no', 'block_no', 'phase_no',
                'house_no', 'street_name', 'subdivision', 'barangay', 'municipality_city',
                'province_state_country', 'zip_code',
                'nature_of_work', 'employer_business_name', 'employee_id', 'date_of_employment',
                'source_of_fund',
                'emp_unit_room_no', 'emp_floor', 'emp_building_name', 'emp_lot_no', 'emp_block_no',
                'emp_phase_no', 'emp_house_no', 'emp_street_name', 'emp_subdivision', 'emp_barangay',
                'emp_municipality_city', 'emp_province_state_country', 'emp_zip_code',
                'loan_purpose', 'other_purpose_specify', 'desired_loan_amount', 'other_amount_specify',
                'loan_term'
            ]);
        });
    }
};
