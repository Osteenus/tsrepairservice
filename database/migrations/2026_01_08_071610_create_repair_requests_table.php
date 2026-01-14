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
        Schema::create('repair_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('contact_id');
            $table->integer('service_id');
            $table->tinyText('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_requests');
    }
};
