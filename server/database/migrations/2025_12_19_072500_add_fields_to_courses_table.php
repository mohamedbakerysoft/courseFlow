<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'slug')) {
                $table->string('slug')->unique()->after('id');
            }
            if (!Schema::hasColumn('courses', 'description')) {
                $table->longText('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('courses', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('thumbnail_path');
            }
            if (!Schema::hasColumn('courses', 'currency')) {
                $table->string('currency', 8)->default('USD')->after('price');
            }
            if (!Schema::hasColumn('courses', 'is_free')) {
                $table->boolean('is_free')->default(false)->after('currency');
            }
            if (!Schema::hasColumn('courses', 'language')) {
                $table->string('language', 12)->default('en')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropUnique('courses_slug_unique');
            $table->dropColumn([
                'slug',
                'description',
                'price',
                'currency',
                'is_free',
                'language',
            ]);
        });
    }
};
