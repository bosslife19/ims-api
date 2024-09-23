<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::all();

        return response(['roles'=>$roles], 200);
    }

    public function show($id){
        $role = Role::find($id);

        return response(['role'=>$role], 200);
    }

    public function destroy($id){
        $role = Role::find($id);
        $role->destroy();
        return response('role deleted', 200);
    }
}
