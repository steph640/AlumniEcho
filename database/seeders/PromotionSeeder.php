<?php

namespace Database\Seeders;

use App\Models\Filiere;
use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $filieres = Filiere::all();

        foreach($filieres as $fil){
            Promotion::factory(10)->create([
                'code_fil' => $fil->code_fil,
            ]);
        };
    }
}
