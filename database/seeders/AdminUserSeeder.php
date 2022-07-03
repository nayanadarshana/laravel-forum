<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj = new User();
        $obj->name = "Nayana Darshana";
        $obj->email = "admin@admin.com";
        $obj->password = bcrypt('123456789');
        $obj->save();
        $obj->assignRole('Admin');
    }
}
