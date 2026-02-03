<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_can_client_sends', function (Blueprint $table) {
            $table->id();

            // Nombre de mails autorisés
            $table->unsignedInteger('mail_limit')->default(0);

            // Actif / Inactif
            $table->boolean('is_active')->default(true);

            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_can_client_sends');
    }
};
