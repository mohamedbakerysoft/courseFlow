<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('provider')->default('stripe');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 8)->default('USD');
            $table->string('status')->default('pending')->index(); // pending|paid|failed
            $table->string('stripe_session_id')->nullable()->unique();
            $table->timestamps();
            $table->unique(['user_id', 'course_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
