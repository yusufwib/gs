<?php

namespace App\Http\Controllers\Api;
use Validator, Carbon, DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Helper\ResponseHelper as JsonHelper;

class RoleController extends Controller
{
    //
    public function get () {

        $res = new JsonHelper;

        $data = Role::where('role_type', 'user')->where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function getById (Request $request) {
        $res = new JsonHelper;

        $data = Role::where('id', $request->input('id'))->where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function create (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'max_buy' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $brand = Role::create(array_merge(
            $validator->validated(),
            [
                'role_type' => 'user',
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responsePost(true, 201, null);
    }

    public function update (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'max_buy' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $brand = Role::where('id', $request->input('id'))->update(array_merge(
            $validator->validated(),
            [
                'name' => $request->input('name'),
                'max_buy' => $request->input('max_buy'),
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responseUpdate(true, 200, null);
    }

    public function delete (Request $request) {
        $res = new JsonHelper;

        $data = Role::where('id', $request->input('id'))->update([
                            'soft_delete' => 1
                        ]);
        return $res->responseDelete(true, 200, null);
    }
}
