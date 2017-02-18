<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('name',25);
            $table->string('value', 50);
            $table->enum('type', ['feature', 'option'])->default('feature');
            $table->string('updated_by', 10)->nullable();
            $table->string('created_by', 10)->default('system');
            $table->timestamps();
            
            $table->index('name');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_properties');
    }
}
