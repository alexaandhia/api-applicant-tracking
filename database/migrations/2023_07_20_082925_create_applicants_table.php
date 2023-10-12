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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); //position title
	        $table->string('description')->nullable(); 
            $table->string('department')->nullable(); //
            $table->string('experience')->nullable(); //required
            $table->string('first_name'); //name table
            $table->string('last_name'); //name table
            $table->string('phone');
            $table->string('email');
            $table->string('resume');
            $table->string('employer')->nullable();
            $table->string('position')->nullable();
            $table->date('applied')->nullable();
            $table->date('interview')->nullable();
            $table->string('interviewer')->nullable();
            $table->string('score')->nullable();
            $table->string('status')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
