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
            $table->foreignId('work_order_id')->constrained();
            // Private disk (storage/app/private); served only through an authenticated route.
            $table->string('path');
            $table->string('mime', 50);
            $table->string('kind', 20)->default('other');
            $table->string('caption')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
