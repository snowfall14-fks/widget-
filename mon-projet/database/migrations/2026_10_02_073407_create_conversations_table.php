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
    Schema::create('conversations', function (Blueprint $table) {
        $table->id();
        $table->string('canal'); // "Chat" ou "Appel"
        $table->integer('duree_secondes')->nullable();
        $table->integer('nombre_messages')->nullable();
        $table->decimal('score', 2, 1)->nullable();
        $table->text('resume')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
