<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryObserver
{
	/**
	 * Handle the Category (before)"create" event.
	 */
	public function creating(Category $category): void
	{
		$this->asignNivel($category);
	}

	/**
	 * Handle the Category (before)"update" event.
	 */
	public function updating(Category $category): void
	{
		if ($category->isDirty('parent_id')) {
			$this->asignNivel($category);
		}
	}

	/**
	 * Handle the Category "update"(after) event.
	 */
	public function updated(Category $category): void
	{
		if ($category->isDirty('nivel')) {
			$this->updateChildLevels($category);
		}
	}

	/**
	 * Handle the Category "deleted" event.
	 */
	public function deleted(Category $category): void
	{
		//
	}

	/**
	 * Handle the Category "restored" event.
	 */
	public function restored(Category $category): void
	{
		//
	}

	/**
	 * Handle the Category "force deleted" event.
	 */
	public function forceDeleted(Category $category): void
	{
		//
	}

	protected function asignNivel(Category $category): void
	{
		DB::transaction(function () use ($category) {
			if (!is_null($category->parent_id)) {
				$parent = Category::where('id', $category->parent_id)
					->lockForUpdate()
					->first();

				$category->nivel = $parent->nivel + 1;
			} else {
				$category->nivel = 0;
			}
		}, 3);
	}

	protected function updateChildLevels(Category $parent): void
	{
		foreach ($parent->children() as $child) {
			$child->nivel = $parent->nivel + 1;
			$child->saveQuietly();

			$this->updateChildLevels($child);
		}
	}
}
