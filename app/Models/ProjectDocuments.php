<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDocuments extends Model
{
    // Important: Use the table name you created in MySQL
    protected $table = 'projects_document';

    protected $fillable = [
        'projectCode', 
        'file_name', 
        'file_path'];

   
}