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
    Schema::table('todos', function (Blueprint $table) {
        $table->date('due_date')->nullable();
        $table->string('priority')->default('Medium');
        $table->string('status')->default('Pending');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            //
        });
    }
};
