<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostType extends Model
{
    use HasFactory;

    public function parent()
    {
        return $this->belongsTo(CostType::class,'parent_id')->with('parent');
    }
}
