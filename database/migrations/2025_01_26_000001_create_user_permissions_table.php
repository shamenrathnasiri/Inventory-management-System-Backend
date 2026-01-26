<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('module'); // e.g., 'customer', 'supplier', 'invoices'
            $table->string('action'); // e.g., 'view', 'edit', 'delete', 'create', 'approve'
            $table->boolean('granted')->default(true);
            $table->timestamps();

            // Unique constraint to prevent duplicate permissions
            $table->unique(['user_id', 'module', 'action']);

            // Index for faster lookups
            $table->index(['user_id', 'module']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
