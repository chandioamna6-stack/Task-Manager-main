<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_teams', function (Blueprint $table) {
            // First, drop the existing foreign key for project_id
            $table->dropForeign(['project_id']);

            // Recreate it with cascade delete
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('project_teams', function (Blueprint $table) {
            // Drop the cascade constraint
            $table->dropForeign(['project_id']);

            // Recreate the original (no cascade)
            $table->foreign('project_id')
                ->references('id')
                ->on('projects');
        });
    }
};
