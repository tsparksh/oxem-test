<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndexGoodsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sortBy' => Rule::in(['price', 'created_at']),
            'sortDirection' => Rule::in(['asc', 'desc', 'ASC', 'DESC']),
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
