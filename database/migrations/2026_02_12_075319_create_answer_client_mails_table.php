<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answer_client_mails', function (Blueprint $table) {
            $table->id();

            // Relation avec contact
            $table->foreignId('contact_id')
                  ->constrained('contacts')
                  ->cascadeOnDelete();

            // Contenu du mail
            $table->string('subject');
            $table->text('content');

            // Pièce jointe facultative
            $table->string('media_path')->nullable();

            // Qui envoie le message (client ou entreprise)
            $table->string('sender_type')->default('company'); 
            // valeurs possibles : client | company

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answer_client_mails');
    }
};
