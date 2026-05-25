<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->string("code_promo",15)->primary();
            $table->string("nom_promo");
            $table->year("annee_promo");
            $table->string("code_fil",15);
            $table->timestamps();
            $table->foreign("code_fil")->references("code_fil")->on("filieres")->onDelete("cascade");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
