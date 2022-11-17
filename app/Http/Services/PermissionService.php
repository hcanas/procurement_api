<?php

namespace App\Http\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionService
{
    private $permissions;
    
    private $permission;
    
    private $office;
    
    public function __construct()
    {
        $this->permissions = auth()->user()['permissions'];
    }
    
    public function authorize($name, $office_id = null)
    {
        $this->permission = $this->findPermission($name);
        
        if ($office_id) $this->office = $this->findOffice($this->permission['offices'] ?? [], $office_id);
        
        if (($office_id AND empty($this->office)) OR empty($this->permission)) {
            throw new HttpResponseException(response()->json('Forbidden.', 403));
        }
        
        return true;
    }
    
    public function offices($ids_only = false)
    {
        if ($ids_only) {
            return array_map(function ($office) {
                return $office['id'];
            }, $this->permission['offices'] ?? []);
        }
        
        return $this->permission['offices'] ?? [];
    }
    
    public function office()
    {
        return $this->office;
    }
    
    private function findPermission($name)
    {
        $res = array_filter($this->permissions, function ($permission) use ($name) {
            return $permission['name'] === $name;
        });
        
        return empty($res) ? [] : array_shift($res);
    }
    
    private function findOffice($offices, $office_id)
    {
        $res = array_filter($offices, function ($office) use ($office_id) {
            return $office['id'] === (int) $office_id;
        });
        
        return empty($res) ? null : array_shift($res);
    }
}