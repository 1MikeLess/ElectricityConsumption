<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DBCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table){
            $table->increments('category_id');
            $table->string('name');
            $table->string('descr')->nullable();
        });

        Schema::create('equipment', function(Blueprint $table){
            $table->increments('equipment_id');
            $table->string('name');
            $table->date('date')->nullable();
            $table->string('descr')->nullable();
        });

        Schema::create('cat_values', function(Blueprint $table){
            $table->increments('cat_value_id');
            $table->integer('category_id');
            $table->string('descr')->nullable();

            $table->foreign('category_id')->references('category_id')->on('categories');
        });

        Schema::create('equip_categories', function(Blueprint $table){
            $table->integer('cat_value_id');
            $table->integer('equipment_id');

            $table->foreign('cat_value_id')->references('cat_value_id')->on('cat_values');
            $table->foreign('equipment_id')->references('equipment_id')->on('equipment');
        });

        Schema::create('power_count', function(Blueprint $table){
            $table->increments('power_count_id');
            $table->integer('equipment_id');
            $table->integer('power');
            $table->date('date');

            $table->foreign('equipment_id')->references('equipment_id')->on('equipment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('cat_values');
        Schema::dropIfExists('equipment');
        Schema::dropIfExists('equip_categories');
        Schema::dropIfExists('power_count');
    }
}
