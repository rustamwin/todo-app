<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title', 'position', 'done', 'parent_id'];
    protected $casts = ['done' => 'boolean', 'done_at' => 'datetime'];

    public function parent()
    {
        return $this->belongsTo(Todo::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Todo::class, 'parent_id')
                    ->with('children')
                    ->orderBy('position');
    }
}
