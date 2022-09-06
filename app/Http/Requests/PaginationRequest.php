<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PaginationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'perPage' => 'numeric|min:-1|max:100',
            'sortBy' => 'array',
            'sortBy.' => 'boolean',
            'sortDesc' => 'array',
            'sortDesc.' => 'boolean',
            'search' => 'nullable|string',
            'withCampaigns' => 'nullable|numeric',
        ];
    }

    protected function prepareForValidation()
    {
        $sortDesc = [];

        if ($this->sortDesc) {
            foreach ($this->sortDesc as $key => $sort) {
                $sortDesc[$key] = (string) $sort === 'true' || $sort == '1' ? 1 : 0;
            }

            $this->merge(
                [
                    'sortDesc' => $sortDesc,
                ]
            );
        }
    }
}
