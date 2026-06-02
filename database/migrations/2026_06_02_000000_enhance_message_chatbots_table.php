<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('message_chatbots', function (Blueprint $table) {
            // Ajouter les colonnes si elles n'existent pas
            if (!Schema::hasColumn('message_chatbots', 'response_type')) {
                $table->enum('response_type', ['faq', 'ai', 'default'])->default('faq')->after('reponse_chatbot');
            }

            if (!Schema::hasColumn('message_chatbots', 'is_helpful')) {
                $table->boolean('is_helpful')->nullable()->after('response_type');
            }

            if (!Schema::hasColumn('message_chatbots', 'user_feedback')) {
                $table->text('user_feedback')->nullable()->after('is_helpful');
            }
        });
    }

    public function down(): void
    {
        Schema::table('message_chatbots', function (Blueprint $table) {
            $table->dropColumn(['response_type', 'is_helpful', 'user_feedback']);
        });
    }
};
