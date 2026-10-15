<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            // Photos sent with a website request belong to the request until it becomes a job.
            $table->foreignId('work_order_id')->nullable()->change();
            $table->foreignId('repair_request_id')->nullable()->after('work_order_id')->constrained();
            $table->string('source', 20)->default('technician')->after('kind');
        });
    }

    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('repair_request_id');
            $table->dropColumn('source');
        });
    }
};
