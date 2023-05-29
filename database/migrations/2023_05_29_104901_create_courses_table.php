<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('courses', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('duration')->nullable();
            $table->integer('lesson_count')->nullable();
            $table->string('course_type')->nullable();
            $table->string('status')->default(1);
            $table->string('level')->nullable();
            $table->string('price')->nullable();
            $table->integer('like_count')->nullable();
            $table->timestamps();
        });
        DB::table('courses')->insert([
            ['name' => 'IELTS luyện đề sơ cấp 1', 'duration' => 1, 'lesson_count' => 5, 'course_type' => 'IELTS', 'level' => 'sơ cấp', 'price' => '600000', 'like_count' => 30, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'IELTS luyện đề sơ cấp 2', 'duration' => 2, 'lesson_count' => 10, 'course_type' => 'IELTS', 'level' => 'sơ cấp', 'price' => '1000000', 'like_count' => 50, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'IELTS luyện đề trung cấp', 'duration' => 3, 'lesson_count' => 15, 'course_type' => 'IELTS', 'level' => 'trung cấp', 'price' => '1400000', 'like_count' => 35, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'IELTS luyện đề cao cấp', 'duration' => 4, 'lesson_count' => 20, 'course_type' => 'IELTS', 'level' => 'cao cấp', 'price' => '30000000', 'like_count' => 44, 'created_at' => date('Y-m-d H:i:s')],

            ['name' => 'Toeic cơ bản', 'duration' => 2, 'lesson_count' => 5, 'course_type' => 'TOEIC', 'level' => 'sơ cấp', 'price' => '7000000', 'like_count' => 17, 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'Toeic sơ cấp', 'duration' => 2, 'lesson_count' => 10, 'course_type' => 'TOEIC', 'level' => 'sơ cấp', 'price' => '1300000', 'like_count' => 30, 'created_at' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
