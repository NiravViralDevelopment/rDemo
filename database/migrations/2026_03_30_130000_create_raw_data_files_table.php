<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_data_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_data_files');
    }
};

