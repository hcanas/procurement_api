<?php

namespace App\Http\Requests;

use App\Http\Services\PermissionService;
use App\Models\FundSource;
use App\Models\Wfp;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateWfp extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $wfp = Wfp::find($this->route('wfp'));
        
        if ($wfp->status === 'for eval:l2') {
            throw new HttpResponseException(response()->json('WFP can not be updated.', 409));
        }
        
        $fund_source = FundSource::find($this->input('fund_source_id'));
        
        $max_cost = $fund_source->amount - $fund_source
            ->wfps()
            ->whereIn('status', ['for eval:l1', 'for eval:l2', 'approved'])
            ->sum('cost');
        
        $max_cost += ($wfp->fund_source_id === $this->input('fund_source_id')) ? $wfp->cost : 0;
    
        $permission_service = new PermissionService();
        $permission_service->authorize('wfps:manage', $fund_source->office_id);
        
        $rules = config('rulebank.wfp');
        $rules['cost'][] = 'max:'.$max_cost;
        $rules['id'] = ['required', 'exists:wfps,id'];
        
        return $rules;
    }
    
    /**
     * Inject record id for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge(['id' => $this->route('wfp')]);
    }
}
