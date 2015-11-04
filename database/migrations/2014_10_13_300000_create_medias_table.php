<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration {

	public function up() {
		Schema::create('medias', function(Blueprint $table) {
			$table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('type', ['VIDEO', 'IMAGE', 'DATA'])->default('IMAGE');

            $table->string('name');
            $table->string('ext');
            $table->string('title')->nullable();
            $table->mediumtext('description')->nullable();

            $table->mediumtext('path')->nullable();
            $table->mediumtext('tags')->nullable();

            $table->string('bucket')->nullable();
            $table->mediumtext('thumb_path')->nullable();

            $table->mediumtext('url')->nullable();

            $table->morphs('users');

            $table->boolean('cloud')->default(false);
            $table->boolean('disabled')->default(false);

            $table->integer('competition_id')->unsigned()->nullable();
            $table->foreign('competition_id')->references('id')->on('competitions');

            $table->integer('region_id')->unsigned()->nullable();
            $table->foreign('region_id')->references('id')->on('regions');

			$table->timestamps();
		});
	}

	public function down() {
		Schema::drop('medias');
	}


}

