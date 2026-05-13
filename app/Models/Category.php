<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'parent_id'];
    protected $guarded = ['id', 'nivel'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id')->with('parent');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->orderBy('name');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeTree(Builder $query): void
    {
        $query->select('id', 'name', 'nivel', 'parent_id')->whereNull('parent_id')->with('childrenTree')->orderBy('name');
    }

    public function childrenTree(): HasMany
    {
        return $this->children()->select('id', 'name', 'nivel', 'parent_id')->with('childrenTree')->orderBy('name');
    }

    public static function getFullTree()
    {
        return static::tree()->get();
    }

    public function breadcrumbs(): array
    {
        $breadcrumbs = [];
        $category = $this;

        while ($category) {
            $breadcrumbs = [$category->id => $category->name] + $breadcrumbs;
            $category = $category->parent;
        }

        return $breadcrumbs;
    }
}
