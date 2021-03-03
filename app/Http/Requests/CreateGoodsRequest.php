<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateGoodsRequest extends FormRequest
{
    /**
     * Проверка, может ли пользователь выполнить запрос.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Валидация входных данных.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:200',
            'description' => 'required|max:1000',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
            'external_id' => 'required|integer|min:1',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
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
