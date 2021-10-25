<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CashFlow;
use App\Helper\ResponseHelper as JsonHelper;
use Validator, Carbon, DB;
use App\Models\Contest;

class CashFlowController extends Controller
{
    public function get (Request $request) {
        $res = new JsonHelper;

        $data = CashFlow::where('type', $request->type)->where('soft_delete', 0)->get();

        foreach ($data as $k => $v) {
            $contestName = Contest::where('id', $v->id_contest)->where('soft_delete', 0)->first();
            $data[$k]->contest_name = $contestName->name;
        }

        return $res->responseGet(true, 200, $data, null);
    }

    public function getById (Request $request) {
        $res = new JsonHelper;

        $data = CashFlow::where('id', $request->input('id'))->where('soft_delete', 0)->get();
        foreach ($data as $k => $v) {
            $contestName = Contest::where('id', $v->id_contest)->where('soft_delete', 0)->first();
            $data[$k]->contest_name = $contestName->name;
        }
        return $res->responseGet(true, 200, $data[0], null);
    }

    public function getByContest (Request $request) {
        $res = new JsonHelper;

        $data = CashFlow::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->where('type', $request->type)->get();
        return $res->responseGet(true, 200, $data, null);
    }

    public function create (Request $request) {
        $res = new JsonHelper;

        $brand = CashFlow::create(array_merge(
            $request->all(),
            [
                'date' => Carbon\Carbon::parse($request->input('date'))->format('Y-m-d\TH:i:s') . '.000000Z',
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responsePost(true, 201, null);
    }

    public function update (Request $request) {
        $res = new JsonHelper;

        $brand = CashFlow::where('id', $request->input('id'))->update(array_merge(
            $request->all(),
            [
                'date' => Carbon\Carbon::parse($request->input('date'))->format('Y-m-d\TH:i:s') . '.000000Z',
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));
        return $res->responsePost(true, 201, null);
    }

    public function delete (Request $request) {
        $res = new JsonHelper;

        $data = CashFlow::where('id', $request->input('id'))->update([
                            'soft_delete' => 1
                        ]);
        return $res->responseDelete(true, 200, null);
    }
}
