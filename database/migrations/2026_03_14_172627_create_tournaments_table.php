<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sport')->default('cricket');
            $table->integer('total_teams')->default(2);
            $table->decimal('budget_per_team', 12, 2)->default(500000);
            $table->integer('max_squad_size')->default(15);
            $table->enum('auction_mode', ['manual', 'live', 'both'])->default('manual');
            $table->enum('status', ['draft', 'active', 'auction', 'completed'])->default('draft');
            $table->boolean('registration_open')->default(false);
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};