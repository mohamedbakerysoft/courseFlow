<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('position')->default(1);
            $table->timestamps();
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->foreignId('module_id')->nullable()->after('course_id')->constrained('course_modules')->nullOnDelete();
        });

        $courses = DB::table('courses')->select('id', 'title')->get();

        foreach ($courses as $course) {
            $lessonIds = DB::table('lessons')
                ->where('course_id', $course->id)
                ->orderBy('position')
                ->pluck('id');

            if ($lessonIds->isEmpty()) {
                continue;
            }

            $moduleId = DB::table('course_modules')->insertGetId([
                'course_id' => $course->id,
                'title' => 'Module 1',
                'description' => null,
                'position' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('lessons')
                ->whereIn('id', $lessonIds)
                ->update(['module_id' => $moduleId]);
        }
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('module_id');
        });

        Schema::dropIfExists('course_modules');
    }
};
