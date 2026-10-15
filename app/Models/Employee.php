<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{

   protected $table = 'employees';
    
    protected $fillable = [
          'name',
          'age',
          'department_id',
          'email',


    ];

 public function department(): BelongsTo
 {
    return $this->belongsTo(Department::class);
 }   
}