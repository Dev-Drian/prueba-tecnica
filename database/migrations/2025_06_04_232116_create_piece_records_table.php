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
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('piece_id')->constrained()->onDelete('cascade'); 
            $table->decimal('real_weight', 8, 2); 
            $table->decimal('difference', 8, 2)->nullable(); 
            $table->timestamp('recorded_at')->useCurrent(); 
            $table->timestamps();
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('piece_records');
    }
};
