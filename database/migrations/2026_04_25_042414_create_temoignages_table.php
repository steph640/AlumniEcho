<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('temoignages', function (Blueprint $table) {
            $table->string("code_tem",15)->primary();
            $table->text("contenu_tem");
            $table->boolean("valide_tem")->default(false);
            $table->string("code_user",15);
            $table->string("code_promo",15)->nullable();
            $table->foreign("code_user")->references("code_user")->on("utilisateurs")->onDelete("cascade");
            $table->foreign("code_promo")->references("code_promo")->on("promotions")->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temoignages');
    }
};
