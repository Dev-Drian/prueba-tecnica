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
        Schema::create('pieces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->constrained()->onDelete('cascade'); 
            $table->string('codigo_pieza')->unique(); 
            $table->string('nombre');
            $table->decimal('peso_teorico', 8, 2); 
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); 
            $table->enum('estado', ['Pendiente', 'Fabricada', 'En_Proceso'])->default('Pendiente');        
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('pieces');
    }
};
