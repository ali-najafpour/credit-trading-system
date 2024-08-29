<?php

namespace App\Http\Requests\Product;

use App\Services\Cert;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Cert $auth)
    {
        return ($auth->check());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:191',
            'description' => 'sometimes|nullable|string|max:4096',
            'price' => [
                'bail',
                'required',
                'numeric',
                'decimal:0,2',
                'between:0,100000000',
            ],
            'total_count' => 'required|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'visible_in_store' =>  'sometimes|boolean',
            'created_by' => 'required',
        ];
    }

    protected function prepareForValidation()
    {
        $auth = resolve('Cert');
        $this->merge(['created_by' => $auth->userId()]);
    }
}
