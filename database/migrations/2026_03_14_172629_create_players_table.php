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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('phone', 15)->nullable();
            $table->string('email')->nullable();
            $table->integer('age')->nullable();
            $table->string('city')->nullable();
            $table->enum('batting_style', ['right_hand', 'left_hand'])->nullable();
            $table->enum('bowling_style', ['right_arm_fast', 'right_arm_spin', 'left_arm_fast', 'left_arm_spin', 'none'])->nullable();
            $table->enum('role', ['batsman', 'bowler', 'all_rounder', 'wicket_keeper'])->nullable();
            $table->string('photo')->nullable();
            $table->decimal('base_price', 12, 2)->default(0);
            $table->decimal('sold_price', 12, 2)->nullable();
            $table->enum('status', ['registered', 'available', 'sold', 'unsold'])->default('registered');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
