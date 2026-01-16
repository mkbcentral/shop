<?php

namespace App\Http\Controllers\Api;

use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\UpdateCategoryAction;
use App\Dtos\Category\CreateCategoryDto;
use App\Dtos\Category\UpdateCategoryDto;
use App\Exceptions\Category\CategoryHasProductsException;
use App\Exceptions\Category\CategoryNotFoundException;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * API Controller for Category Management
 *
 * RESTful API endpoints for CRUD operations on categories.
 */
class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    /**
     * Display a listing of categories.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 15);

        $categories = $this->categoryRepository->paginate($search, $perPage);

        return response()->json($categories);
    }

    /**
     * Store a newly created category.
     *
     * @param Request $request
     * @param CreateCategoryAction $action
     * @return JsonResponse
     */
    public function store(Request $request, CreateCategoryAction $action): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dto = CreateCategoryDto::fromArray($validator->validated());
            $category = $action->execute($dto);

            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->findOrFail($id);

            return response()->json([
                'data' => $category->load('products')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * Update the specified category.
     *
     * @param Request $request
     * @param int $id
     * @param UpdateCategoryAction $action
     * @return JsonResponse
     */
    public function update(Request $request, int $id, UpdateCategoryAction $action): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:500',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $dto = UpdateCategoryDto::fromArray($validator->validated());
            $category = $action->execute($id, $dto);

            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category
            ]);

        } catch (CategoryNotFoundException $e) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified category.
     *
     * @param int $id
     * @param DeleteCategoryAction $action
     * @return JsonResponse
     */
    public function destroy(int $id, DeleteCategoryAction $action): JsonResponse
    {
        try {
            $action->execute($id);

            return response()->json([
                'message' => 'Category deleted successfully'
            ]);

        } catch (CategoryNotFoundException $e) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);

        } catch (CategoryHasProductsException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get popular categories.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function popular(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);

        $categories = $this->categoryRepository->all()
            ->loadCount('products')
            ->sortByDesc('products_count')
            ->take($limit)
            ->values();

        return response()->json([
            'data' => $categories
        ]);
    }
}
