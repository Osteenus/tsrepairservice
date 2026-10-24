<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            // "website" (contact form) or "phone" (Twilio voice bot).
            $table->string('source', 20)->default('website');
            // One request per phone call, even when the hang-up callback also tries to save it.
            $table->string('call_sid', 64)->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->dropUnique(['call_sid']);
            $table->dropColumn(['source', 'call_sid']);
        });
    }
};
