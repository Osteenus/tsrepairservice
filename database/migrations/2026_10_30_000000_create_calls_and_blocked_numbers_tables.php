<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Call log of the business line, incoming and outgoing. Rows are never deleted.
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid', 64)->unique();
            $table->string('direction', 10); // inbound, outbound
            $table->string('from_number', 50)->nullable()->index();
            $table->string('to_number', 50)->nullable()->index();
            $table->string('status', 30)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamp('started_at')->index();
            $table->timestamp('ended_at')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Callers rejected before the phone line answers. Unblocking soft-deletes the row.
        Schema::create('blocked_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('phone_e164', 50)->unique();
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_numbers');
        Schema::dropIfExists('calls');
    }
};
