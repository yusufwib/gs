<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\MasterBank;
use App\Helper\ResponseHelper as JsonHelper;
use Validator, Carbon, DB;


class BankController extends Controller
{
    public function get () {
        $res = new JsonHelper;

        $data = Bank::where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function getMasterBank () {
        $res = new JsonHelper;

        $data = MasterBank::where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function getById (Request $request) {
        $res = new JsonHelper;

        $data = Bank::where('id', $request->input('id'))->where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function create (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $brand = Bank::create(array_merge(
            $validator->validated(),
            [
                'img' => $request->input('img'),
                'owner_name' => $request->input('owner_name'),
                'rekening' => $request->input('rekening'),
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responsePost(true, 201, null);
    }

    public function update (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100'
                    ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $brand = Bank::where('id', $request->input('id'))->update(array_merge(
            $validator->validated(),
            [
                'img' => $request->input('img'),
                'name' => $request->input('name'),
                'owner_name' => $request->input('owner_name'),
                'rekening' => $request->input('rekening'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responseUpdate(true, 200, null);
    }

    public function delete (Request $request) {
        $res = new JsonHelper;

        $data = Bank::where('id', $request->input('id'))->update([
                            'soft_delete' => 1
                        ]);
        return $res->responseDelete(true, 200, null);
    }
}
