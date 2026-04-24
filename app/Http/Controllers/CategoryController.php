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
		$category = Category::withTrashed()->with('childrenTree:id,parent_id')->find($id, ['id', 'name', 'nivel', 'parent_id']);

		$category->setAttribute(
			'children',
			$category->childrenTree->pluck('id')
		);
		$category->children[] = $category->id;
		$category->unsetRelation('childrenTree');

		return response()->json($category, 200);
	}

	public function getCategories(): JsonResponse
	{
		return response()->json($this->formatFlat(Category::getFullTree()), 200);
	}

	private function formatFlat($categories, &$result = []): array
	{
		foreach ($categories as $category) {
			$result[] = [
				'id' => $category->id,
				'name' => $category->name,
				'nivel' => $category->nivel,
				'parent_id' => $category->parent_id
			];

			if ($category->childrenTree->isNotEmpty()) {
				$this->formatFlat($category->childrenTree, $result);
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
