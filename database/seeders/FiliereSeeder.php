<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FiliereSeeder extends Seeder
{

    public function run(): void
    {
        Filiere::factory(5)->create();
    }
}
