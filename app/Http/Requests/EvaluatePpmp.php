<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class EvaluatePpmp extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'ppmp_id' => ['required', Rule::exists('ppmps', 'id')->whereIn('status', ['for eval:l1', 'for eval:l2'])],
            'evaluation' => ['required', 'in:approved,rejected'],
            'remarks' => ['nullable'],
        ];
    }
}
