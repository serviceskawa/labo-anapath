<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->unique();
            $table->string('title')->nullable();
            $table->foreignId('patient_id')->nullable()
                ->constrained('patients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('test_order_id')->nullable()
                ->constrained('test_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamp('signature_date', $precision = 0)->nullable();
            $table->timestamp('delivery_date', $precision = 0)->nullable();
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade'); 
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
        Schema::dropIfExists('reports');
    }
}
