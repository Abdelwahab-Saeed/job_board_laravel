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
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('location');
            $table->string('linkedin_profile')->nullable();
            $table->string('title');
            $table->string('profile_photo')->nullable();
            $table->string('experience_level');
            $table->text('skills');

            $table->text('experience')->nullable();
            $table->text('education')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
