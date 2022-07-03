<?php

namespace App\Models;

use App\Utils\Constant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;


    protected $table = "posts";
    protected $fillable = ['user_id', 'title', 'content', 'status'];
    protected $appends = ['status_name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comment(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getStatusNameAttribute()
    {
        return Constant::$postStatus[array_search($this->attributes['status'], array_column(Constant::$postStatus, 'key'))]['value'];
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
