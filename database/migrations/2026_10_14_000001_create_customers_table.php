<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 40)->nullable();
            // Digits only, so "(805) 555-0100", "805-555-0100" and "8055550100" all match a search.
            $table->string('phone_digits', 20)->nullable()->index();
            $table->string('email')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('city', 120)->nullable();
            $table->string('state', 2)->default('CA');
            $table->string('zip', 10)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('name');
        });

        Schema::create('appliances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('type', 120);
            $table->string('brand', 120)->nullable();
            $table->string('model', 120)->nullable();
            $table->string('serial', 120)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appliances');
        Schema::dropIfExists('customers');
    }
};
