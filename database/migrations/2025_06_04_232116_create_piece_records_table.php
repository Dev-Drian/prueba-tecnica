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
            $table->foreignId('pieza_id')->constrained('pieces')->onDelete('cascade');
            $table->decimal('peso_real', 8, 2);
            $table->decimal('diferencia', 8, 2); 
            $table->timestamp('fecha_hora'); 
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); 

            $table->text('observaciones')->nullable();
          $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('piece_records');
    }
};
