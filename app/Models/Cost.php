<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cost extends Model
{
    use HasFactory;

    public function project()
    {
        return $this->belongsTo(Project::class)->select(array('id', 'title as name', 'client_id'));
    }

    public function type()
    {
        return $this->belongsTo(CostType::class,'cost_type_id');
    }
}
