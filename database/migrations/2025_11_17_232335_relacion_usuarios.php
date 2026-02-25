<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('lider_id')->nullable()->after('id');
        $table->unsignedBigInteger('coordinadora_id')->nullable()->after('lider_id');

        $table->foreign('lider_id')
            ->references('id')->on('users')
            ->onDelete('set null');

        $table->foreign('coordinadora_id')
            ->references('id')->on('users')
            ->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropForeign(['lider_id']);
        $table->dropForeign(['coordinadora_id']);
        $table->dropColumn(['lider_id', 'coordinadora_id']);
    });
}

};
