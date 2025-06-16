<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TExcludeEsd extends Model
{
    //
    protected $table = 'TExcludeEsd_Tbl'; // Specify the table name if it differs from the model name
    protected $primaryKey = 'id'; // Specify the primary key if it differs from 'id'
    public $timestamps = false; // Set to true if your table has created_at and updated_at columns
    protected $fillable = [
        'TExcludeEsd_EmpCd',
        'TExcludeEsd_EsdTy',
    ]; // Specify the fillable attributes if needed
}
