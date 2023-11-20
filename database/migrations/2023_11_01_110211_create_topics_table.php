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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('header', 64);
            $table->text('body');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
            $table->boolean('is_restricted')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_closed')->default(false);

            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('creator_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
