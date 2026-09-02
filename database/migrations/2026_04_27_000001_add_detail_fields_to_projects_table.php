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
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'projectStartDate')) {
                $table->date('projectStartDate')->nullable()->after('projectManager');
            }

            if (!Schema::hasColumn('projects', 'projectEndDate')) {
                $table->date('projectEndDate')->nullable()->after('projectStartDate');
            }

            if (!Schema::hasColumn('projects', 'projectType')) {
                $table->string('projectType')->nullable()->after('projectEndDate');
            }

            if (!Schema::hasColumn('projects', 'projectCategory')) {
                $table->string('projectCategory')->nullable()->after('projectType');
            }

            if (!Schema::hasColumn('projects', 'projectCost')) {
                $table->string('projectCost')->nullable()->after('projectCategory');
            }

            if (!Schema::hasColumn('projects', 'projectTeamMembers')) {
                $table->text('projectTeamMembers')->nullable()->after('projectStatus');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $dropColumns = array_filter([
                Schema::hasColumn('projects', 'projectStartDate') ? 'projectStartDate' : null,
                Schema::hasColumn('projects', 'projectEndDate') ? 'projectEndDate' : null,
                Schema::hasColumn('projects', 'projectType') ? 'projectType' : null,
                Schema::hasColumn('projects', 'projectCategory') ? 'projectCategory' : null,
                Schema::hasColumn('projects', 'projectCost') ? 'projectCost' : null,
                Schema::hasColumn('projects', 'projectTeamMembers') ? 'projectTeamMembers' : null,
            ]);

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
