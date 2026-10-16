<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocuments extends Model
{
    protected $table = 'documents';

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'project_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];
}