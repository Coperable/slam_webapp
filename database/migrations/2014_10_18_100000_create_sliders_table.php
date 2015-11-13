<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration {

	public function up() {
		Schema::create('sliders', function(Blueprint $table) {
			$table->increments('id');

            $table->enum('type', ['QUOTE', 'EVENT', 'SIMPLE'])->default('SIMPLE');
            $table->mediumtext('title')->nullable();
            $table->mediumtext('subtitle')->nullable();

            $table->mediumtext('quote_author')->nullable();
            $table->date('event_date')->nullable();
            $table->mediumtext('event_place')->nullable();

            $table->mediumtext('cover_photo')->nullable();

            $table->boolean('signup_action')->default(false);
            $table->boolean('schedule_action')->default(false);
            $table->boolean('about_action')->default(false);
            $table->boolean('players_action')->default(false);

			$table->timestamps();
		});
	}

	public function down() {
		Schema::drop('sliders');
	}


}
