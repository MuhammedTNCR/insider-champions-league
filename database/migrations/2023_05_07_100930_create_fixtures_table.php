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
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homeowner_id')->index();
            $table->unsignedBigInteger('guest_id')->index();
            $table->unsignedTinyInteger('week');
            $table->timestamps();

            $table->foreign('homeowner_id')->references('id')->on('teams');
            $table->foreign('guest_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
