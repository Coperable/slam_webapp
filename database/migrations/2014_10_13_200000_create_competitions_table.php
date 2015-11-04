<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompetitionsTable extends Migration {

	public function up() {
		Schema::create('competitions', function(Blueprint $table) {
			$table->increments('id');
            $table->string('title')->nullable();

            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions');

            $table->boolean('users_limit')->default(false);
            $table->integer('users_amount')->nullable();

            $table->longText('description')->nullable();
            $table->longText('rules')->nullable();
			$table->dateTime('event_date')->nullable();

            $table->mediumtext('cover_photo')->nullable();

            $table->string('place')->nullable();

            $table->integer('location_id')->unsigned();
            $table->foreign('location_id')->references('id')->on('locations');

            $table->boolean('active')->default(false);

            $table->string('hashtag')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();


            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('modified_by')->unsigned()->nullable();
            $table->foreign('modified_by')->references('id')->on('users');
			$table->timestamps();

		});
	}

	public function down() {
		Schema::drop('competitions');
	}

}

