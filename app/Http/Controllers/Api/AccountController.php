<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use Validator;
use Twilio\Rest\Client;
use App\Helper\ResponseHelper as JsonHelper;

class AccountController extends Controller
{

    public function getByRoleType (Request $request) {
        $res = new JsonHelper;

        $roleType = $request->role_type;

        $data = User::select('users.*', 'roles.name as role_name', 'roles.max_buy')
                        ->join('roles', 'users.role_id', '=', 'roles.id')
                        ->where('roles.role_type', $roleType)
                        ->where('role_id', '!=', 0)
                        ->where('users.soft_delete', 0)
                        ->where('roles.soft_delete', 0)
                        ->get();
        
        return $res->responseGet(true, 200, $data, '');
    }

    public function delAccount (Request $request) {
        $res = new JsonHelper;

        $roleType = $request->role_type;

        $userAcc = User::where('id', $request->id)->first();
        $data = User::where('id', $request->id)
                        ->update([
                            'soft_delete' => 1,
                            'email' => "$userAcc->email" . 'deleted'
                        ]);
        
        return $res->responseGet(true, 200, $data, '');
    }

    public function updateMemberPrevilege (Request $request) {
        $res = new JsonHelper;

        $roleType = $request->role_type;

        $data = User::where('id', $request->id)
                ->update([
                    'role_id' => $request->role_id
                ]);
        
        return $res->responseGet(true, 200, $data, '');
    }


    public function updateMemberName (Request $request) {
        $res = new JsonHelper;


        $data = User::where('id', auth()->user()->id)
                ->update([
                    'name' => $request->input('name')
                ]);
        
        return $res->responseGet(true, 200, $data, '');
    }

    public function registerAdminJury(Request $request) {

        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $user = User::create(array_merge(
                    $validator->validated(),
                    [
                        'phone' => $request->phone,
                        'password' => bcrypt($request->password),
                        'password_show' => ($request->password),
                        'otp' => 99999
                    ]
                ));

        return $res->responseGet(true, 201, $user, 'successfully registered');
    }

    public function updateAccount(Request $request) {
        $res = new JsonHelper;
        $user = User::where('id', $request->id)->update(array_merge(
            $request->all(),
            [
                'password' => bcrypt($request->password),
                'password_show' => ($request->password),
            ]
        ));
        return $res->responseGet(true, 200, $user, 'successfully updated');
    }

    public function updateVerifyAdmin(Request $request) {
        $res = new JsonHelper;
        $user = User::where('id', $request->id)->update(array_merge(
            $request->all(),
            [
                'verified_jury' => $request->verified_jury,
            ]
        ));
        return $res->responseGet(true, 200, $user, 'successfully updated');
    }

    public function masterMember (Request $request) {
        $res = new JsonHelper;
        $user = Role::where('id' , '>', 3)->where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $user, 'successfully updated');
    }

    public function masterMemberUpdate (Request $request) {
        $res = new JsonHelper;
        $user = Role::where('id', $request->id)->update(array_merge(
            $request->all(),
            [
                'name' => ($request->name),
                'role_type' => ($request->role_type),
                'max_buy' => ($request->max_buy),
            ]
        ));
        return $res->responseGet(true, 200, $user, 'successfully updated');
    }

    public function masterMemberCreate (Request $request) {
        $res = new JsonHelper;
        $user = Role::create(array_merge(
            $request->all(),
            [
                'name' => ($request->name),
                'role_type' => ($request->role_type),
                'max_buy' => ($request->max_buy),
            ]
        ));
        return $res->responseGet(true, 200, $user, 'successfully created');
    }

    public function masterMemberDelete (Request $request) {
        $res = new JsonHelper;
        $user = Role::where('id', $request->id)->update([
            'soft_delete' => 1
        ]);
        return $res->responseGet(true, 200, $user, 'successfully deleted');
    }

}
