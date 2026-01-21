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
        Schema::create('tenant_informations', function (Blueprint $table) {
            $table->id();

            $table->string('tenant_name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('RIB')->nullable();
            $table->string('SIRET')->nullable();
            $table->string('VAT_number')->nullable();

            $table->string('logo_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_informations');
    }
};
