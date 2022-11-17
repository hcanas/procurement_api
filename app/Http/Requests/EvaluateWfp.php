<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class EvaluateWfp extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'wfp_id' => ['required', Rule::exists('wfps', 'id')->whereIn('status', ['for eval:l1', 'for eval:l2'])],
            'evaluation' => ['required', 'in:approved,rejected'],
            'remarks' => ['nullable'],
        ];
    }
}
