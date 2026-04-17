<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects'; 

    protected $fillable = [
        'projectName', 
        'clientName', 
        'projectManager', 
        'projectCode',
        'projectDetail'
  
    ];

    public function documents()
{
    // Second argument is the foreign key on the documents table
    // Third argument is the local key on the projects table
    return $this->hasMany(ProjectDocuments::class, 'projectCode', 'projectCode');
}
}
