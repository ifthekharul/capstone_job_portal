<?php

namespace Database\Seeders;

use App\Models\User;

use App\Models\Category;
use App\Models\Job_details;
use App\Models\JobType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => 'user@1234',
            'role'=>'user',
        ]);
        User::factory()->create([
            'name' => 'Niloy',
            'email' => 'niloy@user.com',
            'password' => 'niloy@1234',
            'role'=>'admin',
        ]);
        User::factory()->create([
            'name' => 'Rohan Ahamed',
            'email' => 'rohan@user.com',
            'password' => 'rohan@1234',
            'role'=>'user',
        ]);
        User::factory()->create([
            'name' => 'Admin 2',
            'email' => 'admin2@admin.com',
            'password' => 'admin2@1234',
            'role'=>'admin',
        ]);
        User::factory()->create([
            'name' => 'Asif',
            'email' => 'asif@user.com',
            'password' => 'asif@1234',
            'role'=>'user',
        ]);

        //Category::factory(5)->create();
        //JobType::factory(5)->create();


        //Category factory

        Category::factory()->create([
             'name' => 'Engineering',
        ]);

        Category::factory()->create([
            'name' => 'IT Consultant',
        ]);
        Category::factory()->create([
            'name' => 'Doctor',
        ]);
        Category::factory()->create([
            'name' => 'Architect',
        ]);
        Category::factory()->create([
            'name' => 'Bank',
        ]);
        Category::factory()->create([
            'name' => 'Real State',
        ]);
        Category::factory()->create([
            'name' => 'Education',
        ]);
        Category::factory()->create([
            'name' => 'Mechanic',
        ]);
        Category::factory()->create([
            'name' => 'Others',
        ]);


        //Job Type factory
        JobType::factory()->create([
            'name' => 'Full Time',
        ]);

        JobType::factory()->create([
            'name' => 'Part Time',
        ]);

        JobType::factory()->create([
            'name' => 'Remote',
        ]);

        JobType::factory()->create([
            'name' => 'Freelance',
        ]);
        JobType::factory()->create([
            'name' => 'Contactual',
        ]);


        Job_details::factory(50)->create();

    }
}
