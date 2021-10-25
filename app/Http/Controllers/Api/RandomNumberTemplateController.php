<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RandomNumberTemplate;
use App\Helper\ResponseHelper as JsonHelper;
use Validator, Carbon, DB;

class RandomNumberTemplateController extends Controller
{
    //
    public function get () {

        $res = new JsonHelper;

        $data = RandomNumberTemplate::where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function getById (Request $request) {
        $res = new JsonHelper;

        $data = RandomNumberTemplate::where('id', $request->input('id'))->where('soft_delete', 0)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function create (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'number' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $brand = RandomNumberTemplate::create(array_merge(
            $validator->validated(),
            [
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
            'number' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        $brand = RandomNumberTemplate::where('id', $request->input('id'))->update(array_merge(
            $validator->validated(),
            [
                'name' => $request->input('name'),
                'number' => $request->input('number'),
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responseUpdate(true, 200, null);
    }

    public function delete (Request $request) {
        $res = new JsonHelper;

        $data = RandomNumberTemplate::where('id', $request->input('id'))->update([
                            'soft_delete' => 1
                        ]);
        return $res->responseDelete(true, 200, null);
    }
}
