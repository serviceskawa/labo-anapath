<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->foreignId('patient_id')->nullable()
                ->constrained('patients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()
                ->constrained('doctors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('hospital_id')->nullable()
                ->constrained('hospitals')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('reference_hopital')->nullable();
            $table->foreignId('contrat_id')->nullable()
                ->constrained('contrats')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('archive')->nullable();
            $table->float('subtotal')->nullable();
            $table->float('discount')->nullable();
            $table->float('total')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('test_orders');
    }
}
