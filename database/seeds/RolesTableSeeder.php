<?php
use Illuminate\Database\Seeder;
use Slam\User;
use Slam\Model\UserRole;
use Slam\Model\Role;
use Illuminate\Support\Facades\Hash;

class RolesTableSeeder extends Seeder {

    public function run() {
        DB::table('roles')->delete();


        $role = new Role();
        $role->name = 'edit_site';
        $role->description = 'Editar Sitio';
        $role->save();

        $role = new Role();
        $role->name = 'crud_user';
        $role->description = 'Manejo Usuarios';
        $role->save();

        $role = new Role();
        $role->name = 'create_region';
        $role->description = 'Administrar Regiones';
        $role->save();

        $role = new Role();
        $role->name = 'create_competition';
        $role->description = 'Crear Competiciones';
        $role->save();

        $role = new Role();
        $role->name = 'edit_region';
        $role->description = 'Editar Region';
        $role->save();

        $role = new Role();
        $role->name = 'crud_region_user';
        $role->description = 'Administrar Participantes';
        $role->save();

        $userRole = new UserRole();
        $userRole->user_id = 1;
        $userRole->role_id = 1;
        $userRole->save();
        
        $userRole = new UserRole();
        $userRole->user_id = 1;
        $userRole->role_id = 2;
        $userRole->save();
 

        $userRole = new UserRole();
        $userRole->user_id = 2;
        $userRole->role_id = 3;
        $userRole->save();
 
        $userRole = new UserRole();
        $userRole->user_id = 2;
        $userRole->role_id = 2;
        $userRole->save();
 
        $userRole = new UserRole();
        $userRole->user_id = 3;
        $userRole->role_id = 4;
        $userRole->save();
 
        $userRole = new UserRole();
        $userRole->user_id = 3;
        $userRole->role_id = 5;
        $userRole->save();

        $userRole = new UserRole();
        $userRole->user_id = 3;
        $userRole->role_id = 6;
        $userRole->save();


     }

}




