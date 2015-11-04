<?php
use Illuminate\Database\Seeder;
use Slam\User;
use Slam\Model\UserRegion;
use Slam\Model\Region;
use Illuminate\Support\Facades\Hash;


class RegionsTableSeeder extends Seeder {

    public function run() {
        DB::table('regions')->delete();

        $region = new Region();
        $region->code = 'ARG';
        $region->name = 'Argentina';
        $region->description = 'Nacional';
        $region->color = '#37ABC8';
        $region->icon = 'argentina';
        $region->save();

        $region = new Region();
        $region->code = 'CBA';
        $region->name = 'Córdoba';
        $region->description = 'Córdoba';
        $region->color = '#EE441E';
        $region->icon = 'cordoba';
        $region->parent_id = 1;
        $region->save();

        $region = new Region();
        $region->code = 'STA';
        $region->name = 'Santa Fé';
        $region->description = 'Santa Fé';
        $region->color = '#8FC521';
        $region->icon = 'santa_fe';
        $region->parent_id = 1;
        $region->save();

        $region = new Region();
        $region->code = 'MZA';
        $region->name = 'Mendoza';
        $region->description = 'Mendoza';
        $region->color = '#F3558A';
        $region->icon = 'mendoza';
        $region->parent_id = 1;
        $region->save();


        $userRegion = new UserRegion();
        $userRegion->user_id = 1;
        $userRegion->region_id = 1;
        $userRegion->admin = true;
        $userRegion->save();
        

    }

}



