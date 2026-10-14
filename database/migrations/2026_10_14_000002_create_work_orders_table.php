<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // "jobs" is Laravel's queue table, so CRM jobs are stored as work orders.
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('appliance_id')->nullable()->constrained();
            $table->foreignId('repair_request_id')->nullable()->constrained();
            $table->text('reported_problem');
            $table->text('findings')->nullable();
            $table->string('status', 20)->default('new')->index();
            $table->timestamp('scheduled_at')->nullable()->index();
            $table->string('service_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('repair_requests', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->dropColumn('archived_at');
        });
        Schema::dropIfExists('work_orders');
    }
};
