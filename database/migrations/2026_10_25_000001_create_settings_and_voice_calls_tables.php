<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Small key/value store for settings the owner changes in the CRM.
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->json('value');
            $table->timestamps();
        });

        // Every incoming call on the business line, for the A/B statistics.
        Schema::create('voice_calls', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid', 64)->unique();
            $table->string('caller_phone', 50)->nullable();
            $table->string('variant', 20)->nullable()->index(); // ai, transfer
            $table->boolean('rate_limited')->default(false);
            $table->boolean('reached_ai')->default(false);
            $table->string('transfer_status', 20)->nullable(); // answered, missed
            $table->boolean('lead_created')->default(false);
            $table->boolean('timed_out')->default(false);
            $table->unsignedSmallInteger('ai_turns')->default(0);
            $table->unsignedInteger('input_tokens')->default(0);
            $table->unsignedInteger('output_tokens')->default(0);
            $table->string('call_status', 20)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_calls');
        Schema::dropIfExists('settings');
    }
};
