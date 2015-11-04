<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCompetitionsTable extends Migration {

	public function up() {
		Schema::create('users_competitions', function(Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('competition_id')->unsigned();
            $table->foreign('competition_id')->references('id')->on('competitions');

            $table->primary(['user_id', 'competition_id']);

            $table->integer('order')->unsigned()->nullable();

            $table->integer('media_id')->unsigned()->nullable();
            $table->foreign('media_id')->references('id')->on('medias');

            $table->integer('mention_id')->unsigned()->nullable();
            $table->foreign('mention_id')->references('id')->on('mentions');

            $table->integer('cups_id')->unsigned()->nullable();
            $table->foreign('cups_id')->references('id')->on('cups');

			$table->timestamps();

		});
	}

	public function down() {
		Schema::drop('users_competitions');
	}

}


