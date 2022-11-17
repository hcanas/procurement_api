<?php

namespace App\Http\Requests;

use App\Http\Services\PermissionService;
use App\Models\FundSource;

class CreateWfp extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $fund_source = FundSource::find($this->input('fund_source_id'));
        $max_cost = $fund_source->amount - $fund_source
            ->wfps()
            ->whereIn('status', ['for eval:l1', 'for eval:l2', 'approved'])
            ->sum('cost');
        
        $permission_service = new PermissionService();
        $permission_service->authorize('wfps:manage', $fund_source->office_id);
        
        $rules = config('rulebank.wfp');
        $rules['cost'][] = 'max:'.$max_cost;
        
        return $rules;
    }
}
