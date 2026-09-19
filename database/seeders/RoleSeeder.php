<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run():void{
        $role =[
            [
                'role_name' => 'farmer',
                'description' => 'Manages agricultural products'
            ],
            [
                'role_name' => 'buyer',
                'description' => 'Purchases agricultural products'
            ],
            [
                'role_name' => 'admin',
                'description' => 'Administers the FreshFarms platform'
            ]
        ];

        foreach ($role as $role){
            Role::updateOrCreate(
                ['role_name' => $role['role_name']],
                ['description' => $role['description']]
            );
        }
    }
}
