<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('message_chatbots', function (Blueprint $table) {
            $table->string("code_message",15)->primary();
            $table->string("code_user",15);
            $table->string("question_chatbot");
            $table->text("reponse_chatbot")->nullable();
            $table->foreign("code_user")->references("code_user")->on("utilisateurs")->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_chatbots');
    }
};
