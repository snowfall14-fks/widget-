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
    Schema::create('documents', function (Blueprint $table) {
        $table->id();
        $table->string('nom_fichier');
        $table->string('type', 20);
        $table->string('statut')->default('En cours');
        $table->timestamps();
    });
}
};
