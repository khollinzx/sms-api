<?php

namespace App\Models;

use App\Traits\HasRepositoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SMS extends Model
{
    use HasFactory, HasRepositoryTrait;

    /**
     * @var string[]
     */
    protected $fillable = [
        'recipient',
        'message',
        'is_scheduled',
        'scheduled_at',
        'sent_at',
        'status',
        'public_id',
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * @var array
     */
    public array $relationships = [];

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->public_id) {
                $model->public_id = (string) Str::uuid();
            }
        });
    }
}
