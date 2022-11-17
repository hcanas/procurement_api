<?php

namespace App\Http\Requests;

use App\Http\Services\PermissionService;
use Illuminate\Validation\Rule;

class CreateFundSource extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $permission_service = new PermissionService();
        $permission_service->authorize('fund_sources:manage', $this->input('office_id'));
        $year = $this->input('year');
        
        $rules = config('rulebank.fund_source');
        $rules['office_id'][] = Rule::in($permission_service->offices(true));
        $rules['name'][] = Rule::unique('fund_sources')->where(function ($query) use ($year) {
            if ($year) {
                $query->where('year', $year);
            }
        });
        
        return $rules;
    }
}
