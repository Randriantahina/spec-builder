<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SpecificationPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'order',
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }
}
