<?php

namespace App\Http\Requests;

use App\Http\Services\PermissionService;
use App\Models\Ppmp;
use App\Models\Wfp;
use Illuminate\Validation\Rule;

class UpdatePpmp extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $wfp = Wfp::addSelect(['allocated' => Ppmp::query()
                ->whereColumn('wfps.id', 'ppmps.wfp_id')
                ->selectRaw('SUM(abc * quantity)')
            ])
            ->find($this->input('wfp_id'));
        
        $ppmp = Ppmp::find($this->route('ppmp'));
    
        (new PermissionService())->authorize('ppmps:manage', $wfp?->fundSource()->first()->office_id);
        
        $total_milestones = $this->input('milestone_1', 0)
            + $this->input('milestone_2', 0)
            + $this->input('milestone_3', 0)
            + $this->input('milestone_4', 0)
            + $this->input('milestone_5', 0)
            + $this->input('milestone_6', 0)
            + $this->input('milestone_7', 0)
            + $this->input('milestone_8', 0)
            + $this->input('milestone_9', 0)
            + $this->input('milestone_10', 0)
            + $this->input('milestone_11', 0)
            + $this->input('milestone_12', 0);
        
        $rules = config('rulebank.ppmp');
        $rules['abc'][] = 'max:'.($wfp?->cost - $wfp?->allocated + ($ppmp->abc * $total_milestones))
            / ($total_milestones > 0 ? $total_milestones : 1);
        $rules['quantity'][] = 'max:'.$total_milestones;
        
        if ($this->input('wfp_id') && $this->input('item_id')) {
            $rules['item_id'][] = Rule::unique('ppmps', 'item_id')
                ->ignore($ppmp->id)
                ->where('wfp_id', $this->input('wfp_id'));
        }
        
        
        return $rules;
    }
}
