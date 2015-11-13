<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {
    
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('name');
            $table->string('lastname');
            $table->string('coperable_id')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();

			$table->string('facebook')->nullable();
			$table->string('google')->nullable();
			$table->string('twitter')->nullable();
			$table->string('instagram')->nullable();

            $table->mediumtext('cover_photo');
            $table->string('photo')->nullable();

			$table->dateTime('last_access')->nullable();

            $table->string('activation_code')->nullable();
            $table->boolean('active')->default(false);

            $table->boolean('participant')->default(false);

            $table->timestamps();
        });
    }

    public function down() {
        Schema::drop('users');
    }
}
