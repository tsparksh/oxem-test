<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Проверка, может ли пользователь выполнить запрос.
     *
     * @return bool
     */
    public function authorize()
    {
        return Category::exists($this->route('id'));
    }

    /**
     * Валидация входных данных.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:200',
            'parent_category_id' => 'exists:categories,id',
            'external_id' => 'integer|min:1'
        ];
    }

    /* По-хорошему, нужно переписать это в самом ядре, но
       1. Тогда нужно паблишить вендор
       2. Придумывать способ резолва одновременно и обычных и апи запросов
       3. Это немного выходит за рамки тестового задания :)
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
