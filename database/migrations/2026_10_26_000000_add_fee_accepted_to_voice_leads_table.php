<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voice_leads', function (Blueprint $table) {
            // The caller clearly agreed to the $95 service call fee (required for a visit).
            $table->boolean('fee_accepted')->default(false)->after('consent_to_callback');
        });
    }

    public function down(): void
    {
        Schema::table('voice_leads', function (Blueprint $table) {
            $table->dropColumn('fee_accepted');
        });
    }
};
