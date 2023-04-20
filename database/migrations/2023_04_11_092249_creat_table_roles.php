<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'Quản Trị Hệ Thống', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'student', 'description' => 'Học Viên', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'teacher', 'description' => 'Giáo Viên', 'created_at' => date('Y-m-d H:i:s')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
