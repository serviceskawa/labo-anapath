<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_contrat_id');
            $table->double('monthly_gross_salary', 10, 2)->nullable();
            $table->double('hourly_gross_rate', 10, 2)->nullable();
            $table->double('transport_allowance', 10, 2)->nullable();
            $table->string('iban')->nullable();
            $table->string('bic')->nullable();
            $table->foreign('employee_contrat_id')
                ->references('id')
                ->on('employee_contrats')
                ->onDelete('cascade');
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
        Schema::dropIfExists('employee_payrolls');
    }
}