<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Получение списка всех категорий.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Создание новой категории.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return ['success' => true, "payload" => $category];
    }


    /**
     * Обновление существующей категории.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id)->update($request->validated());
        return ['success' => true, "payload" => $category];
    }

    /**
     * Удаление существующей категории.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::delete($id);
        return ['success' => true, "payload" => []];
    }

    /**
     * Получение списка товаров конкретной категории
     *
     * @param $id
     * @return mixed
     */
    public function goods($id)
    {
        return Category::findOrFail($id)->goods;
    }
}
