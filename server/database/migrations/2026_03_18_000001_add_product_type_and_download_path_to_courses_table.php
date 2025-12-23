<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('product_type')->default('course')->after('status');
            $table->string('download_file_path')->nullable()->after('thumbnail_path');
        });

        DB::table('courses')
            ->whereNull('product_type')
            ->update(['product_type' => 'course']);
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['product_type', 'download_file_path']);
        });
    }
};
