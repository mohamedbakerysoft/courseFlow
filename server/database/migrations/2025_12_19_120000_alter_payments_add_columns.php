<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('external_reference')->nullable()->after('stripe_session_id');
            $table->string('proof_path')->nullable()->after('external_reference');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('proof_path');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['external_reference', 'proof_path', 'approved_by', 'approved_at']);
        });
    }
};

