<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('tag');
            $table->string('name');
            $table->float('height');
            $table->float('weight');
            $table->foreignId('location_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->boolean('is_active')->default('0');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
};
