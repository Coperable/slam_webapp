<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorsTable extends Migration {

    public function up() {
        Schema::create('colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('description');
			$table->timestamps();
        });
    }

    public function down() {
        Schema::drop('colors');
    }

}

