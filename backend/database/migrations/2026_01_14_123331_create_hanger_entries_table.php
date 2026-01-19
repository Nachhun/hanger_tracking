<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hanger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // New required fields
            $table->string('outlet_name');
            $table->string('province');
            $table->string('hanger_id');

            // Optional legacy fields (can be null)
            $table->string('customer_name')->nullable();
            $table->integer('quantity')->nullable();
            $table->string('location')->nullable(); // text location description
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();

            // GPS coordinates (from salesman location)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hanger_entries');
    }
};
