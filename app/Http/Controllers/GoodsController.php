<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGoodsRequest;
use App\Http\Requests\IndexGoodsRequest;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Models\Goods;

class GoodsController extends Controller
{
    /**
     * Получение списка всех товаров.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexGoodsRequest $request)
    {
        $query = Goods::query();
        if ($request->sortBy && $request->sortDirection) {
            $query->orderBy($request->sortBy, $request->sortDirection);
        }

        return $query->with('categories:id,name')->paginate(50);
    }

    /**
     * Создание нового товара.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateGoodsRequest $request)
    {
        try {

            $goods = Goods::create($request->validated());
            $goods->categories()->attach($request->categories);
            return ['success' => true, "payload" => $goods->load('categories:id,name')];

        } catch (QueryException $exception) {

            // Получаем детали ошибки через `errorInfo`:
            return ['success' => false, "error" => $exception->errorInfo];
        }
    }

    /**
     * Поиск товара по id.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Goods::with('categories:id,name')->findOrFail($id);
    }

    /**
     * Удаление товара по id.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Goods::delete($id);
        return ['success' => true, "payload" => []];
    }
}
