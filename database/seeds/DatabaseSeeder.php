<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

		$this->call('UsersTableSeeder');
        $this->command->info('Users seeded!');

		$this->call('RegionsTableSeeder');
        $this->command->info('Regions seeded!');

		$this->call('RolesTableSeeder');
        $this->command->info('Roles seeded!');

        Model::reguard();
    }
}
