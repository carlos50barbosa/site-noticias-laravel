<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de linha única (singleton, id = 1) com as configurações do site.
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Site de Notícias');
            $table->string('logo_url')->nullable();
            $table->string('favicon_url')->nullable(); // ícone do site (favicon)
            $table->string('adsense_client')->nullable(); // ID do Google AdSense (ca-pub-...)
            $table->unsignedBigInteger('visits')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
