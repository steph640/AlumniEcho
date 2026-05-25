<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('chatbot_faqs', function (Blueprint $table) {
            $table->string("code_faq",15)->primary();
            $table->string("question_faq");
            $table->text("reponse_faq");
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('chatbot_faqs');
    }
};
