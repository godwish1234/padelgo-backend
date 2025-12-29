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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained()->onDelete('cascade');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('match_date_time');
            $table->unsignedInteger('max_players')->default(4);
            $table->unsignedInteger('current_players')->default(1);
            $table->enum('skill_level', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->enum('match_type', ['friendly', 'competitive'])->default('friendly');
            $table->enum('status', ['open', 'full', 'ongoing', 'finished', 'cancelled'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('court_id');
            $table->index('creator_id');
            $table->index('match_date_time');
            $table->index('status');
            $table->index('skill_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
