<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voice_leads', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid', 64)->unique();
            $table->string('caller_phone', 50);
            $table->string('caller_name')->nullable();
            $table->string('service_address')->nullable();
            $table->string('appliance_type', 120)->nullable();
            $table->string('appliance_brand', 120)->nullable();
            $table->text('reported_problem')->nullable();
            $table->text('agent_summary')->nullable();
            $table->boolean('consent_to_callback')->default(false);
            $table->string('preferred_callback_time')->nullable();
            $table->boolean('recording_notice_played')->default(true);
            // How the call ended: visit, callback, transfer, timeout, hangup.
            $table->string('outcome', 20)->nullable();
            $table->boolean('transferred')->default(false);
            // new, callback_due, callback_done, overdue
            $table->string('status', 20)->default('new')->index();
            $table->timestamp('callback_due_at')->nullable();
            $table->unsignedSmallInteger('followup_count')->default(0);
            $table->timestamp('followed_up_at')->nullable();
            $table->text('followup_notes')->nullable();
            $table->foreignId('repair_request_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            // Leads are never hard-deleted.
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_leads');
    }
};
