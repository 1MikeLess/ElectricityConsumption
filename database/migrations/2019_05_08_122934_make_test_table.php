<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testTable', function(Blueprint $table) {
            $table->increments('test_id');
            $table->string('name', 200)->nullable();
            $table->integer('test_integer')->nullable();
            $table->string('email')->nullable();
            $table->integer('rand_integer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExist('testTable');
    }
}
