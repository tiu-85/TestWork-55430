<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    /**
     * Сортировка
     * @var string[]
     */
    private $sortOrder = [
        'ascending' => 'asc',
        'descending' => 'desc'
    ];

    /**
     * Условии валидации
     *
     * @param null $id
     * @return string[]
     */
    protected function rules($id = null)
    {
        return [
            'sku' => 'required|max:255|unique:products,sku,' . $id,
            'name' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'max:65535',
            'image' => 'string|nullable|max:255',
            'file' => 'required|mimes:jpg,jpeg,png,gif|max:2048'
        ];
    }

    /**
     * Отображает список всех товаров
     *
     * @param Request $request
     * @return Products
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 5);
        $offset = $request->query('offset', 0);
        $sort = $request->query('sort', 'name');
        $order = $request->query('order', 'asc');
        $filter = $request->query('filter', null);

        $query = Product::query()->select(
            'id',
            'sku',
            'name',
            'price',
            'description',
            DB::raw('DATE_FORMAT(created_at, "%d-%m-%y %H:%i:%s") as created'),
            DB::raw('DATE_FORMAT(updated_at, "%d-%m-%y %H:%i:%s") as updated')
        );
        if (isset($sort, $this->sortOrder[$order])) {
            $query->orderBy($sort, $this->sortOrder[$order]);
        }
        if ($filter) {
            $query->where('sku', 'like', "%{$filter}%");
        }
        $query->limit($limit)->offset($offset);

        return new Products($query->get());
    }

    /**
     * Возвращает информацию о товаре с заданным ИД
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function get($id)
    {
        $product = Product::query()->findOrFail($id);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Добавляет новый товар
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate($this->rules());

        $file = $request->file('file');
        if ($file && $path = $file->store('public')) {
            $validatedData['image'] = '/storage/' . pathinfo($path)['basename'];
        }

        $product = Product::create($validatedData);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Обновляет товар с заданным ИД
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function update($id, Request $request)
    {
        $validatedData = $request->validate($this->rules($id));

        $file = $request->file('file');
        if ($file && $path = $file->store('public')) {
            $validatedData['image'] = '/storage/' . pathinfo($path)['basename'];
        }

        $product = Product::query()->findOrFail($id);
        $product->update($validatedData);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Удаляет товар с заданным ИД
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete($id)
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }
}
