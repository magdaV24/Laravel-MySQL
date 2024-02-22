<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('url')->nullable();
            $table->timestamps();

            $table->index('uuid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
