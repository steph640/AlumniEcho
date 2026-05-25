<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->string("code_com",15)->primary();
            $table->text("contenu_com");
            $table->string("code_user",15);
            $table->string("code_souv",15);
            $table->foreign("code_user")->references("code_user")->on("utilisateurs")->onDelete("cascade");
            $table->foreign("code_souv")->references("code_souv")->on("souvenirs")->onDelete("cascade");
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('commentaires');
    }
};
