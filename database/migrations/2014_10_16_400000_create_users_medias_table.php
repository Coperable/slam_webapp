<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersMediasTable extends Migration {

	public function up() {
		Schema::create('users_medias', function(Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('media_id')->unsigned();
            $table->foreign('media_id')->references('id')->on('medias');

            $table->primary(['user_id', 'media_id']);

			$table->timestamps();
		});
	}

	public function down() {
		Schema::drop('users_medias');
	}

}


