<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->string('location', 120)->nullable();
            $table->string('brand', 120)->nullable();
            $table->string('model', 120)->nullable();
            $table->text('description')->change();
        });
    }

    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->dropColumn(['location', 'brand', 'model']);
            // Retain TEXT to avoid truncating existing request descriptions.
        });
    }
};
