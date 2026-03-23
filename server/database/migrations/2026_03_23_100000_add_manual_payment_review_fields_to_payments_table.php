<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->text('payment_reference')->nullable()->after('external_reference');
            $table->timestamp('submitted_at')->nullable()->after('proof_path');
            $table->text('review_notes')->nullable()->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('review_notes');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_reference',
                'submitted_at',
                'review_notes',
                'rejected_at',
            ]);
        });
    }
};
