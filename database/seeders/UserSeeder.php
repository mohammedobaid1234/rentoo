<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder{

    public function run(){
        $um_roles = [
            ['name' => 'super_admin', 'label' => 'أدمن', 'guard_name' => 'web'],
            ['name' => 'vendor', 'label' => 'مورد', 'guard_name' => 'web'],
            ['name' => 'driver', 'label' => 'سائق', 'guard_name' => 'web'],
            ['name' => 'user', 'label' => 'مستخدم', 'guard_name' => 'web'],
        ];

        foreach($um_roles as $row){
            $record = new \Spatie\Permission\Models\Role;
            $record->name = $row['name'];
            $record->label = $row['label'];
            $record->guard_name = $row['guard_name'];
            $record->save();
        }
        $um_users = [
            [
                'national_id' => '406996020', 
                'first_name' => 'Mohammed',
                'last_name'=> 'Obaid', 
                'address' => 'qatar, AlDoha,',
                'email' =>  'mhmd.obaid.18@gmail.com',
                'password' => Hash::make('12345678'),
                'mobile_no' => "0594034429", 
                'roles' => ["super_admin"],
            ], 
        ];

        foreach($um_users as $row){
            $user = new \Modules\Users\Entities\User;
            $user->national_id = $row['national_id'];
            $user->first_name = $row['first_name'];
            $user->last_name = $row['last_name'];
            $user->address = $row['address'];
            $user->email = $row['email'];
            $user->mobile_no = $row['mobile_no'];
            $user->password = \Illuminate\Support\Facades\Hash::make("123456789");
            $user->save();
            $user->assignRole($row['roles']);
        }

        //  $user = \Modules\Users\Entities\User::first();
        //  $user->assignRole('super_admin');
    }
}
