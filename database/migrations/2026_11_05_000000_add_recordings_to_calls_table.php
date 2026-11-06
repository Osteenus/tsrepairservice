<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            // Test calls from the owner's own phones (services.twilio.test_numbers).
            $table->boolean('is_internal')->default(false);
            $table->string('recording_sid', 64)->nullable();
            $table->unsignedInteger('recording_duration_seconds')->nullable();
            // Voice Intelligence transcript, created only when "Transcribe" is pressed.
            $table->string('transcript_sid', 64)->nullable()->unique();
            $table->string('transcript_status', 20)->nullable(); // pending, completed, failed
            $table->text('transcript')->nullable();
        });

        $testNumbers = config('services.twilio.test_numbers') ?? [];
        if ($testNumbers) {
            DB::table('calls')->where(fn ($query) => $query->whereIn('from_number', $testNumbers)->orWhereIn('to_number', $testNumbers))
                ->update(['is_internal' => true]);
        }
    }

    public function down(): void
    {
        Schema::table('calls', function (Blueprint $table) {
            $table->dropUnique(['transcript_sid']);
            $table->dropColumn(['is_internal', 'recording_sid', 'recording_duration_seconds', 'transcript_sid', 'transcript_status', 'transcript']);
        });
    }
};
