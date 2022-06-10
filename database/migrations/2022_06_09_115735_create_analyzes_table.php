<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analyzes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level');
            $table->string('user_name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->on('users')->references('id')->onDelete('cascade');
            $table->integer('amount')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('discount')->nullable();
            $table->string('type',20);
            $table->string('file');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::update('alter table analyzes AUTO_INCREMENT = 10000');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('analyzes');
    }
}
