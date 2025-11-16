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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->enum("role" , [1, 2, 3]);
            /**
             * ملاحظة
             * ال 1 مشان ال admin
             * 2 مشان ال employee
             * 3 مشان ال citizen
             * او اذا بدكن منبدلهم بنصوص (admin , employee , citizen)
             * متل ما بتحبو
             */
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
