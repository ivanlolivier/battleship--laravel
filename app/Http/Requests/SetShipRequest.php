<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetShipRequest extends FormRequest
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
            'ship_name'   => ['required'],
            'row'         => ['required'],
            'col'         => ['required'],
            'orientation' => ['required', 'in:v,h'],
        ];
    }
}
