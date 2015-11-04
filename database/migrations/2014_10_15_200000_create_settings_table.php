<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	public function up() {
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');

            $table->string('module');
            $table->string('name');
            $table->mediumtext('value')->nullable();

            $table->integer('media_id')->unsigned();
            $table->foreign('media_id')->references('id')->on('medias');

			$table->timestamps();
		});
	}

	public function down() {
		Schema::drop('settings');
	}


}

