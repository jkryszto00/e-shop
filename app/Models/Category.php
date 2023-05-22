<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'child_id'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_child', 'child_id', 'parent_id')->withTimestamps();
    }

    public function childs(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_child', 'parent_id', 'child_id')->withTimestamps();
    }
}
