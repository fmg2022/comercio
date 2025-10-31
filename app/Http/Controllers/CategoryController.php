<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CategoryController extends Controller
{
	public function index(): View
	{
		return view('pages.dashboard.category.index', [
			'categories' => Category::withTrashed()->orderByRaw('deleted_at IS NULL DESC')->orderBy('parent_id')->paginate(10),
			'categoriesList' => $this->formatFlat(Category::getFullTree())
		]);
	}

	public function store(CategoryRequest $request): RedirectResponse
	{
		Category::create($request->validated());
		return redirect()->back();
	}

	public function update(CategoryRequest $request, Category $category): RedirectResponse
	{
		$validated = $request->validated();

		try {
			DB::transaction(function () use ($validated, $category) {
				// Actualizar categoría principal
				$category->update([
					'name' => $validated['name'],
					'parent_id' => $validated['parent_id'] ?? null
				]);

				// Actualizar subcategorías
				if (isset($validated['children'])) {
					$this->updateChildrenParent($category, $validated['children']);
				}
			});

			return redirect()->back();
		} catch (\Throwable $th) {
			return redirect()->back()->withErrors(['error' => 'Error al actualizar la categoría. ' . $th->getMessage()]);
		}
	}

	public function destroy(Category $category): RedirectResponse
	{
		$category->delete();
		return redirect()->back();
	}

	public function restore($id): RedirectResponse
	{
		$category = Category::withTrashed()->findOrFail($id);
		$category->restore();
		return redirect()->back();
	}

	public function fetch(String $id): JsonResponse
	{
		$category = Category::withTrashed()->findOrFail($id, ['id', 'name', 'parent_id']);

		$category->load(['parent:id,name', 'children' => fn($query) => $query->select('id', 'name', 'nivel', 'parent_id')->orderBy('name')]);

		return response()->json($category, 200);
	}

	public function getCategories(): JsonResponse
	{
		return response()->json($this->formatFlat(Category::getFullTree()), 200);
	}

	private function formatFlat($categories)
	{
		$result = [];

		foreach ($categories as $category) {
			$prefix = str_repeat('-- ', $category->nivel);
			$result[] = [
				'id' => $category->id,
				'name' => $prefix . $category->name,
				'level' => $category->nivel
			];

			if ($category->childrenTree->isNotEmpty()) {
				$result = array_merge(
					$result,
					$this->formatFlat($category->childrenTree)
				);
			}
		}

		return $result;
	}

	private function updateChildrenParent(Category $category, array $newChildren): void
	{
		$oldChildrenID = $category->children()->pluck('id')->toArray();
		$newSub = array_diff($newChildren, $oldChildrenID);
		$removedSub = array_diff($oldChildrenID, $newChildren);

		if (!empty($newSub)) {
			Category::whereIn('id', $newSub)->update(['parent_id' => $category->id]);
		}

		if (!empty($removedSub)) {
			Category::whereIn('id', $removedSub)->update(['parent_id' => null]);
		}
	}
}
