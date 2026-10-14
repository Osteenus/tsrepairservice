<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per document type and year; locked while a number is handed out.
        Schema::create('document_counters', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);
            $table->unsignedSmallInteger('year');
            $table->unsignedInteger('last_number')->default(0);
            $table->unique(['type', 'year']);
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained();
            $table->string('type', 20);
            // Assigned when the document is issued, so deleted drafts never leave gaps.
            $table->string('number', 30)->nullable()->unique();
            $table->string('status', 20)->default('draft');
            $table->foreignId('revision_of_id')->nullable()->constrained('documents');
            $table->foreignId('estimate_id')->nullable()->constrained('documents');
            $table->date('issued_on')->nullable();
            $table->date('valid_until')->nullable();
            $table->date('service_date')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->unsignedInteger('discount_cents')->default(0);
            $table->boolean('charge_tax')->default(false);
            $table->decimal('tax_rate', 6, 4)->nullable();
            $table->unsignedInteger('labor_cents')->default(0);
            $table->unsignedInteger('parts_cents')->default(0);
            $table->unsignedInteger('tax_cents')->default(0);
            $table->unsignedInteger('total_cents')->default(0);
            $table->text('notes')->nullable();
            $table->text('work_performed')->nullable();
            $table->string('completed_by')->nullable();
            $table->string('performed_by')->nullable();
            // Customer, appliance and company details frozen at issue time to reproduce the PDF later.
            $table->json('snapshot')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['work_order_id', 'type']);
        });

        Schema::create('document_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained();
            $table->unsignedSmallInteger('position')->default(0);
            $table->string('kind', 20);
            $table->string('description');
            $table->string('part_number', 80)->nullable();
            $table->decimal('qty', 8, 2)->default(1);
            $table->unsignedInteger('unit_cents')->default(0);
            $table->boolean('is_warranty')->default(false);
            $table->boolean('is_used_or_exchanged')->default(false);
            $table->timestamps();
        });

        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained();
            $table->string('role', 20);
            $table->string('path');
            $table->string('signed_name');
            $table->timestamp('signed_at');
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();
        });

        // Customer consent to charges above the previous estimate (BHGS: written, or oral with details).
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained();
            $table->string('method', 10);
            $table->string('authorized_by');
            $table->string('phone', 40)->nullable();
            $table->timestamp('consented_at');
            $table->text('description');
            $table->unsignedInteger('new_total_cents');
            $table->foreignId('signature_id')->nullable()->constrained();
            $table->foreignId('recorded_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consents');
        Schema::dropIfExists('signatures');
        Schema::dropIfExists('document_items');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_counters');
    }
};
