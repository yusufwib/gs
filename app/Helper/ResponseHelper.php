<?php

namespace App\Helper;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponseHelper
{

    public function responseGet ($success, $code, $data, $msg) {
        return response()->json([
            'status_code' => 200,
            'message' => isset($msg) ? $msg : ($success ? 'Success get data' : 'Failed get data'),
            'success' => $success,
            'data' => $data
        ], 200);
    }

    public function responsePost ($success, $code = 201, $msg) {
        return response()->json([
            'status_code' => 200,
            'message' => isset($msg) ? $msg : ($success ? 'Success post data' : 'Failed post data'),
            'success' => $success
        ], 200);
    }

    public function responseUpdate ($success, $code, $msg) {
        return response()->json([
            'status_code' => 200,
            'message' => isset($msg) ? $msg : ($success ? 'Success update data' : 'Failed update data'),
            'success' => $success
        ], 200);
    }

    public function responseDelete ($success, $code, $msg) {
        return response()->json([
            'status_code' => 200,
            'message' => isset($msg) ? $msg : ($success ? 'Success delete data' : 'Failed delete data'),
            'success' => $success
        ], 200);
    }

    function generateRandomString ($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

}
