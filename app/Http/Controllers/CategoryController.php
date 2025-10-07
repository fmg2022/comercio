<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
		$category->update($request->validated());
		return redirect()->back();
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
}
