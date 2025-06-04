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
        Schema::create('piece_records', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_registro')->unique(); 
            $table->foreignId('proyecto_id')->constrained('proyectos');
            $table->foreignId('bloque_id')->constrained('bloques');
            $table->foreignId('pieza_id')->constrained('piezas');
            $table->decimal('peso_real', 8, 2); 
            $table->decimal('diferencia', 8, 2); 
            $table->timestamp('fecha_hora'); 
            $table->foreignId('user_id')->constrained('users'); 
            $table->text('observaciones')->nullable();        
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('piece_records');
    }
};
