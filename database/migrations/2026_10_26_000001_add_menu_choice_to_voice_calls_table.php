<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voice_calls', function (Blueprint $table) {
            // "Caller chooses" mode: request (1), technician (2) or none (no key pressed).
            $table->string('menu_choice', 20)->nullable()->after('variant');
        });
    }

    public function down(): void
    {
        Schema::table('voice_calls', function (Blueprint $table) {
            $table->dropColumn('menu_choice');
        });
    }
};
