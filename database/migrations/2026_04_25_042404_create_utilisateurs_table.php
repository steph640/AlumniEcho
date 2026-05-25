<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->string("code_user", 15)->primary();
            $table->string('nom_user');
            $table->string('prenom_user');
            $table->string('login_user')->unique();
            $table->string('password_user');
            $table->string("tel_user");
            $table->enum("sexe_user", ["M", "F"]);
            $table->enum("role_user", ["admin", "alumni", "visiteur"])->default("visiteur");
            $table->enum("etat_user", ["actif", "inactif",])->default("actif");
            $table->string("code_promo", 15)->nullable();
            $table->string("code_fil", 15)->nullable();
            $table->timestamps();

            $table->foreign("code_promo")->references("code_promo")->on("promotions")->onDelete("set null");
            $table->foreign("code_fil")->references("code_fil")->on("filieres")->onDelete("set null");
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
