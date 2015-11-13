<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIconosTable extends Migration {

    public function up() {
        Schema::create('icons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->boolean('is_region')->default(false);
            $table->string('description')->nullable();
			$table->timestamps();
        });
    }

    public function down() {
        Schema::drop('icons');
    }

}

