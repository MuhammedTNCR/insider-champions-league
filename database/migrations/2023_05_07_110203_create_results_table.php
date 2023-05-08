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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fixture_id')->index();
            $table->unsignedBigInteger('winner_id')->index()->nullable();
            $table->unsignedBigInteger('loser_id')->index()->nullable();
            $table->boolean('drawn')->default(false);
            $table->unsignedTinyInteger('goals_for')->nullable();
            $table->unsignedTinyInteger('goals_against')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fixture_id')->references('id')->on('fixtures')
            ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('winner_id')->references('id')->on('teams');
            $table->foreign('loser_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
