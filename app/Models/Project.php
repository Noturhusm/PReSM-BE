<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $primaryKey = 'project_id';

    protected $hidden = [
        'Project_description',
    ];

    protected $appends = [
        'projectDetail',
    ];

    protected $fillable = [
        'projectName',
        'clientName',
        'projectManager',
        'projectCode',
        'projectDetail',
        'projectStartDate',
        'projectEndDate',
        'projectType',
        'projectCategory',
        'projectCost',
        'projectStatus',
        'projectTeamMembers',
    ];

    public function getProjectDetailAttribute(): ?string
    {
        return $this->attributes['Project_description'] ?? null;
    }

    public function setProjectDetailAttribute(?string $value): void
    {
        $this->attributes['Project_description'] = $value;
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocuments::class, 'project_id', 'project_id');
    }
}
