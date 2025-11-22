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
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
};
