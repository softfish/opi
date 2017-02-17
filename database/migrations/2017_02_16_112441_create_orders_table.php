<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_name', 70);
            $table->string('address', 255);
            $table->float('total');
            $table->enum('status', ['Cancelled', 'In progress', 'Completed'])->default('In progress');
            $table->timestamp('order_date');
            $table->string('updated_by', 10)->nullable();
            $table->string('created_by', 10)->default('system');
            $table->timestamps();
            
            $table->index('order_date');
            $table->index('customer_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
