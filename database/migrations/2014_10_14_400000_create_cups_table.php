<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCupsTable extends Migration {

    public function up() {
        Schema::create('cups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();

            $table->string('icon')->nullable();

            $table->integer('competition_id')->unsigned()->nullable();
            $table->foreign('competition_id')->references('id')->on('competitions');

        });
    }

    public function down() {
        Schema::drop('cups');
    }

}
