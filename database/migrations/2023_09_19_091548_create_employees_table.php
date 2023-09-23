<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->string('gender');
            $table->string('date_of_birth');
            $table->string('place_of_birth');
            $table->string('nationality');
            $table->string('cnss_number');
            $table->string('address');
            $table->string('city');
            $table->string('photo_url')->nullable();
            
            // $table->unsignedBigInteger('employee_payroll_id');
            // $table->foreign('employee_payroll_id')
            //     ->references('id')
            //     ->on('employee_payrolls')
            //     ->onDelete('cascade');

            // $table->unsignedBigInteger('employee_contrat_id');
            // $table->foreign('employee_contrat_id')
            //     ->references('id')
            //     ->on('employee_contrats')
            //     ->onDelete('cascade');

            // $table->unsignedBigInteger('employee_id');
            // $table->foreign('employee_id')
            //     ->references('id')
            //     ->on('employees')
            //     ->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}