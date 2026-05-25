<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('souvenirs', function (Blueprint $table) {
            $table->string("code_souv",15)->primary();
            $table->string("titre_souv");
            $table->text("description_souv")->nullable();
            $table->string("url_photo_souv")->nullable();
            $table->string("code_user",15);
            $table->string("code_promo",15)->nullable();
            $table->foreign("code_user")->references("code_user")->on("utilisateurs")->onDelete("cascade");
            $table->foreign("code_promo")->references("code_promo")->on("promotions")->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('souvenirs');
    }
};
