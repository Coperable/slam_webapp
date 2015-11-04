<?php
use Illuminate\Database\Seeder;
use Slam\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

    public function run() {
        DB::table('users')->delete();

        $user = new User();
        $user->username = 'Leo';
        $user->email = 'aito0077@gmail.com';
        $user->password = Hash::make('kierkegaard');
        $user->save();

        $user = new User();
        $user->username = 'usuario1';
        $user->email = 'usuario1@test.com';
        $user->password = Hash::make('usuario1');
        $user->save();

        $user = new User();
        $user->username = 'usuario2';
        $user->email = 'usuario2@test.com';
        $user->password = Hash::make('usuario2');
        $user->save();

        $user = new User();
        $user->username = 'usuario3';
        $user->email = 'usuario3@test.com';
        $user->password = Hash::make('usuario3');
        $user->save();


        $user = new User();
        $user->username = 'participante1';
        $user->email = 'participante1@test.com';
        $user->password = Hash::make('participante1');
	$user->participant = true;
        $user->save();


    }

}


