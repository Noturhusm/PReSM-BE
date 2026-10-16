<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->renameColumnIfNeeded('users', 'emailId', 'email');
        $this->renameColumnIfNeeded('users', 'id', 'user_id');
        $this->renameColumnIfNeeded('password_reset_tokens', 'emailId', 'email');
    }

    public function down(): void
    {
        $this->renameColumnIfNeeded('password_reset_tokens', 'email', 'emailId');
        $this->renameColumnIfNeeded('users', 'user_id', 'id');
        $this->renameColumnIfNeeded('users', 'email', 'emailId');
    }

    private function renameColumnIfNeeded(string $table, string $oldName, string $newName): void
    {
        if (!Schema::hasTable($table)) {
            throw new \RuntimeException("Cannot normalize {$table}.{$oldName}: table does not exist.");
        }

        $hasOldColumn = Schema::hasColumn($table, $oldName);
        $hasNewColumn = Schema::hasColumn($table, $newName);

        if ($hasOldColumn && $hasNewColumn) {
            throw new \RuntimeException("Cannot normalize {$table}: both {$oldName} and {$newName} exist.");
        }

        if (!$hasOldColumn && $hasNewColumn) {
            return;
        }

        if (!$hasOldColumn) {
            throw new \RuntimeException("Cannot normalize {$table}: neither {$oldName} nor {$newName} exists.");
        }

        Schema::table($table, function (Blueprint $blueprint) use ($oldName, $newName): void {
            $blueprint->renameColumn($oldName, $newName);
        });
    }
};
