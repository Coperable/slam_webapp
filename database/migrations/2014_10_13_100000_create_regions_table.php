<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration {

	public function up() {
		Schema::create('regions', function(Blueprint $table) {
			$table->increments('id');

            $table->string('code')->unique();
            $table->string('name')->unique();
            $table->string('description');

            $table->string('color');
            $table->string('icon');

            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('regions');

			$table->timestamps();

		});
	}

	public function down() {
		Schema::drop('regions');
	}

}

