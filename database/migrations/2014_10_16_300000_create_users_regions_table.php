<?php
 
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateUsersRegionsTable extends Migration {
 
    public function up() {
        Schema::create('users_regions', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions');

            $table->primary(['user_id', 'region_id']);

            $table->integer('admin')->default(false);
            
			$table->timestamps();

        });
    }
 
    public function down() {
        Schema::drop('users_regions');
    }
 
}
