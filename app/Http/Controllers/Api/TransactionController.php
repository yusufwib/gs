<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Carbon, DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Helper\ResponseHelper as JsonHelper;
use App\Models\Contest;
use App\Models\Role;
use App\Models\BannerContest;
use App\Models\ContestSchedule;
use App\Models\DetailContestOrganizer;
use App\Models\DetailContestCriteriaContentChampionPrize;

use App\Models\DetailContestCriteriaContent;
use App\Models\ContestanScore;
use App\Models\DetailContestCriteria;
use App\Models\User;
use App\Models\Bank;

use App\Models\Transaction;


class TransactionController extends Controller
{

    private function sendWhatsappNotificationConfirm( string $event, string $ticket, string $criteria, string $bird, string $name, string $confirmedAt)
    {
        $url = "https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp";
        // $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i');
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->addHours(3)->format('H.i');
        // return $hour;
        $header = array(
            'Content-Type:application/json',
            'API-key: 797ddd68908bae79b19e0909e2520a142d4961b2cc5ed1981a6aab29f593d8d8'
        );
        $data = json_encode(array(
            'phone' => $recipient,
            'messageType' => 'text',
            'body' => ""
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close ($ch);

    }

    private function sendWhatsappNotificationCancel( string $event, string $ticket, string $criteria, string $bird, string $name)
    {
        $url = "https://sendtalk-api.taptalk.io/api/v1/message/send_whatsapp";
        // $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i');
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->addHours(3)->format('H.i');
        // return $hour;
        $header = array(
            'Content-Type:application/json',
            'API-key: 797ddd68908bae79b19e0909e2520a142d4961b2cc5ed1981a6aab29f593d8d8'
        );
        $data = json_encode(array(
            'phone' => $recipient,
            'messageType' => 'text',
            'body' => ""
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close ($ch);

    }

    public function getSlot (Request $request) {
        
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id_criteria_contents' => 'required',
            'id_contest' => 'required'
        ]);
        
        if ($validator->fails()) {
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        //validate start register

        $registerValidate = Contest::where('id', $request->input('id_contest'))->first();

        $startTime = strtotime(\Carbon\Carbon::parse($registerValidate->start_register));
        $now = strtotime(\Carbon\Carbon::now('Asia/Jakarta'));

        if ($now < $startTime) {
            return $res->responseGet(false, 200, null, 'Contest is unopenned');
        }

        $participantsGet = DetailContestCriteriaContent::
                            where('id', $request->input('id_criteria_contents'))
                            ->first();


        $criteriaContentId = $request->input('id_criteria_contents');

        $paid = Transaction::
                                where('id_criteria', $criteriaContentId)
                                ->where('status', 'done')
                                ->count();
        $response = [
            'participants' => (int)$participantsGet->participants,
            'paid' => (int)$paid,
            'slot' => (int)$participantsGet->participants - (int)$paid
        ];
        return $res->responseGet(true, 200, $response, '');
    }


    public function getCurrentBuy (Request $request) {
        
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id_criteria' => 'required',
            'id_contest' => 'required',
            'id_criteria_contents' => 'required'
        ]);
        
        if ($validator->fails()) {
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        //validate start register

        $registerValidate = Contest::where('id', $request->input('id_contest'))->first();

        $startTime = strtotime(\Carbon\Carbon::parse($registerValidate->start_register));
        $now = strtotime(\Carbon\Carbon::now('Asia/Jakarta'));

        if ($now < $startTime) {
            return $res->responseGet(false, 200, null, 'Contest is unopenned');
        }

        $getMember = User::
                            where('id', auth()->user()->id)
                            ->first();
        $getMaxBuy = Role::where('id', $getMember->role_id)->first();
        $maxBuy = $getMaxBuy->max_buy;

        $criteriaId = $request->input('id_criteria');

        $currentBuy = Transaction::
                                    where('id_user', auth()->user()->id)
                                ->where('id_criteria', $criteriaId)
                                ->where('status', '!=','cancelled')
                                ->count();

        $paid = Transaction::
                                where('id_criteria', $criteriaId)
                                ->where('status', 'done')
                                ->count();
        $response = [
            'user_current_buy' => $currentBuy,
            'user_max_buy' => $maxBuy
        ];
        return $res->responseGet(true, 200, $response, '');
    }

    public function autoCancelOrder (Request $request) {
        $data = Transaction::get();
        $count = 0;
        foreach ($data as $k => $v) {
            $expTime = strtotime(\Carbon\Carbon::parse($v->exp_time));
            $now = strtotime(\Carbon\Carbon::now('Asia/Jakarta'));
            if ($now > $expTime && $v->status == 'unpaid') {
                Transaction::where('id', $v->id)->update([
                    'status' => 'cancelled',
                    'cancelled_at' => Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:s') . '.000000Z',
                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                ]);

                $ts = Transaction::where('id', $v->id)->first();

                $contest = Contest::where('id', $ts->id_contest)->first();

                $user = User::where('id', $ts->id_user)->first();

                $criteria = DetailContestCriteria::where('id', $ts->id_criteria)->first();

                $bird = DetailContestCriteriaContent::where('id', $ts->id_criteria_contents)->first();

                $this->sendWhatsappNotificationCancel($contest->name, $v->id_ticket, $criteria->criteria_name, $bird->bird_name, $user->name);
                $count += 1;
            }
        }
        return "$count transaction affected";
    }

    public function buyTicket (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id_criteria' => 'required',
            'id_contest' => 'required',
            'id_criteria_contents' => 'required',
            'price' => 'required',
            'bird_name_contestant' => 'required'
        ]);
        if ($validator->fails()) {
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $registerValidate = Contest::where('id', $request->input('id_contest'))->first();

        // $startTime = strtotime(\Carbon\Carbon::parse($registerValidate->start_register));
        // $now = strtotime(\Carbon\Carbon::now('Asia/Jakarta'));

        // if ($now < $startTime) {
        //     return $res->responseGet(true, 200, null, 'Contest is unopenned');
        // }

        if ($registerValidate->is_open == 0) {
            return $res->responseGet(false, 200, null, 'Contest is unopenned');
        }

        $getMember = User::
                            where('id', auth()->user()->id)
                            ->first();
                            // return $getMember;
        $getMaxBuy = Role::where('id', $getMember->role_id)->first();
        // return $getMaxBuy;
        $maxBuy = $getMaxBuy->max_buy;

        $criteriaId = $request->input('id_criteria');
        $criteriaContentId = $request->input('id_criteria_contents');

        $currentBuy = Transaction::
                                    where('id_user', auth()->user()->id)
                                ->where('id_criteria', $criteriaId)
                                ->where('status', '!=','cancelled')
                                ->count();

        $valid = false;

        $paid = Transaction::
                                where('id_criteria', $criteriaId)
                                ->where('status', '!=','cancelled')
                                ->count();

        $participantsGet = DetailContestCriteriaContent::
                                where('id', $request->input('id_criteria_contents'))
                                ->first();

        $participants = $participantsGet->participants;
        // return $paid;
        if ($currentBuy < $maxBuy && $participants > $paid) {
            $contentsOffset = Transaction::where('id_user', auth()->user()->id)
                                    ->where('id_criteria_contents', $criteriaContentId)
                                    ->where('status', '!=','cancelled')
                                    ->count();
            if ($contentsOffset == 1) {
                $valid = false;
            } else {
                $valid = true;
            }
        }
        
        $transaction = [];
        if ($valid) {
            $transaction = Transaction::create(
            array_merge($validator->validated(),
            [
                'id_ticket' => $request->input('id_contest') . $request->input('id_criteria') . 99 . $request->input('id_criteria_contents') . auth()->user()->id,
                'id_user' => auth()->user()->id,
                'status' => 'unpaid',
                'exp_time' => Carbon\Carbon::now('Asia/Jakarta')->addHours(24)->format('Y-m-d\TH:i:s') . '.000000Z',
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));

        return $res->responseGet(true, 201, [
            'id_transaction' => $transaction->id
        ], 'Success');  

        } else {
            return $res->responsePost(false, 400, 'Offset buy', 'Offset buy');
        }
    }

    public function buyTicketFromAdmin (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id_criteria' => 'required',
            'id_contest' => 'required',
            'id_criteria_contents' => 'required'
        ]);

        if ($validator->fails()) {
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $valid = false;

        // NEED TO VVALIDATE MAX STOCK
        $criteriaContentId = $request->input('id_criteria_contents');
        
        $paid = Transaction::
                                where('id_criteria', $criteriaContentId)
                                ->where('status', '!=','cancelled')
                                ->count();

        $participantsGet = DetailContestCriteriaContent::
                                where('id', $request->input('id_criteria_contents'))
                                ->first();

        $participants = $participantsGet->participants;
        // return $paid;
        if ($participants > $paid) {
            $valid = true;
        }
        
        $transaction = [];
        if ($valid) {
            $user = User::create([
                'name' => $request->input('name_user'),
                'password' => bcrypt('password123'),
                'role_id' => 0,
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]);

            $priceCriteria= DetailContestCriteria::where('id', $request->input('id_criteria'))->first();

            $transaction = Transaction::create(
            array_merge($validator->validated(),
            [
                'id_ticket' => $request->input('id_contest') . $request->input('id_criteria') . 99 . $request->input('id_criteria_contents') . $user->id,
                'id_user' => $user->id,
                'price' => $priceCriteria->registration_fee,
                'status' => 'done',
                'exp_time' => Carbon\Carbon::now('Asia/Jakarta')->addHours(24)->format('Y-m-d\TH:i:s') . '.000000Z',
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));

        return $res->responseGet(true, 201, [
            'id_transaction' => $transaction->id
        ], 'Success');

        } else {
            return $res->responsePost(false, 400, 'Offset buy', 'Offset buy');
        }
    }

    public function uploadPayment (Request $request) {
        $res = new JsonHelper;
        
        $validator = Validator::make($request->all(), [
            'id_bank' => 'required',
            'payment_image' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
        ]);

        if ($validator->fails()) {
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $image = $request->file('payment_image');

        $paymentImage = 'payment-' . uniqid() . $res->generateRandomString(30) . '.'.$image->getClientOriginalExtension();
        Storage::disk('public')->put($paymentImage,File::get($image));

        $update = Transaction::where('id', $request->input('id_transaction'))->update(array_merge(
            $validator->validated(),
            [
                'status' => 'waiting',
                'payment_image' => '/storage/' . $paymentImage,
                'paid_at' => Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:s') . '.000000Z',
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ]
        ));

        return $res->responsePost(true, 200, 'Success', 'Success');
    }

    public function confirmTransaction (Request $request) {
        $res = new JsonHelper;
        $transaction = Transaction::where('id', $request->input('id_transaction'))->first();

        if ($request->input('status') == 'cancelled') {
            $update = Transaction::where('id', $request->input('id_transaction'))->update(
                [
                    'status' => $request->input('status'),
                    'cancelled_at' => Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:s') . '.000000Z',
                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                ]
            );

            $ts = Transaction::where('id', $request->input('id_transaction'))->first();

            $contest = Contest::where('id', $ts->id_contest)->first();

            $user = User::where('id', $ts->id_user)->first();

            $criteria = DetailContestCriteria::where('id', $ts->id_criteria)->first();

            $bird = DetailContestCriteriaContent::where('id', $ts->id_criteria_contents)->first();

            $this->sendWhatsappNotificationCancel($contest->name, $ts->id_ticket, $criteria->criteria_name, $bird->bird_name, $user->name);

        } else {
            $update = Transaction::where('id', $request->input('id_transaction'))->update(
                [
                    'status' => $request->input('status'),
                    'confirmed_at' => Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d\TH:i:s') . '.000000Z',
                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                ]
            );

            $ts = Transaction::where('id', $request->input('id_transaction'))->first();

            $contest = Contest::where('id', $ts->id_contest)->first();

            $user = User::where('id', $ts->id_user)->first();

            $criteria = DetailContestCriteria::where('id', $ts->id_criteria)->first();

            $bird = DetailContestCriteriaContent::where('id', $ts->id_criteria_contents)->first();

            $this->sendWhatsappNotificationConfirm($contest->name, $ts->id_ticket, $criteria->criteria_name, $bird->bird_name, $user->name, $ts->confirmed_at);
        }

        return $res->responsePost(true, 200, 'Success', 'Success');
    }

    public function getMobileTicketHistory (Request $request) {
        $res = new JsonHelper;
        $transaction =[];
        $status = $request->input('status');

        if ($status == 'all') {
            $transaction = Transaction::
            where('id_user', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)->get();
        } else {
            $transaction = Transaction::
            where('id_user', auth()->user()->id)
            ->where('status', $status)
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)->get();
        }

        foreach($transaction as $key => $val) {
            $eventName = Contest::where('id', $val->id_contest)->first();
            $criteriaName = DetailContestCriteria::where('id', $val->id_criteria)->first();
            $birdName = DetailContestCriteriaContent::where('id', $val->id_criteria_contents)->first();
            // return $birdName;
            $transaction[$key]->criteria_name = $criteriaName->criteria_name;
            $transaction[$key]->contest_name = $eventName->name;
            $transaction[$key]->location_name = $eventName->location_name;
            $transaction[$key]->location_address = $eventName->location_address;
            $transaction[$key]->bird_name = $birdName->bird_name;
            $transaction[$key]->buyers_name = auth()->user()->name;
            $transaction[$key]->contest_date = $eventName->contest_date;
            $transaction[$key]->contest_time = $eventName->contest_time;

            $organizer = DetailContestOrganizer::where('id_contest', $val->id_contest)->where('soft_delete', 0)->get();
            $transaction[$key]->organizer = $organizer;
        }
        $response = $res->paginate($transaction);
        return $res->responseGet(true, 200, $response, '');
    }

    
    public function getUserProjectById (Request $request) {
        $res = new JsonHelper;

        $idUser = $request->id_user;

        $data = User::select('users.*', 'roles.name as role_name', 'roles.max_buy')
                        ->join('roles', 'users.role_id', '=', 'roles.id')
                        ->where('users.id', $idUser)
                        ->where('role_id', '!=', 0)
                        ->where('users.soft_delete', 0)
                        ->where('roles.soft_delete', 0)
                        ->first();

        $transaction = Transaction::
        where('id_user', $idUser)
        ->where('status', 'done')
        ->orderBy('created_at', 'DESC')
        ->where('soft_delete', 0)->get();


        foreach($transaction as $key => $val) {
            $eventName = Contest::where('id', $val->id_contest)->first();
            $criteriaName = DetailContestCriteria::where('id', $val->id_criteria)->first();
            $birdName = DetailContestCriteriaContent::where('id', $val->id_criteria_contents)->first();
            // return $birdName;
            $transaction[$key]->criteria_name = $criteriaName->criteria_name;
            $transaction[$key]->contest_name = $eventName->name;
            $transaction[$key]->location_name = $eventName->location_name;
            $transaction[$key]->city = $eventName->city;

            $transaction[$key]->location_address = $eventName->location_address;
            $transaction[$key]->bird_name = $birdName->bird_name;
            $transaction[$key]->contest_date = $eventName->contest_date;
            $transaction[$key]->contest_time = $eventName->contest_time;

        }
        $arrRes = [
            'detail_user' => $data,
            'detail_contest' => $transaction
        ];
        
        return $res->responseGet(true, 200, $arrRes, '');
    }


    public function getMobileTicketById (Request $request) {
        $res = new JsonHelper;
        $id_transaction = $request->input('id_transaction');
        $transaction = Transaction::
                        where('id', $id_transaction)
                        ->orderBy('created_at', 'DESC')
                        ->where('soft_delete', 0)->get();

        foreach($transaction as $key => $val) {
            $eventName = Contest::where('id', $val->id_contest)->first();
            $criteriaName = DetailContestCriteria::where('id', $val->id_criteria)->first();
            $birdName = DetailContestCriteriaContent::where('id', $val->id_criteria_contents)->first();
            // return $birdName;
            $transaction[$key]->criteria_name = $criteriaName->criteria_name;
            $transaction[$key]->contest_name = $eventName->name;
            $transaction[$key]->location_name = $eventName->location_name;
            $transaction[$key]->location_address = $eventName->location_address;
            $transaction[$key]->bird_name = $birdName->bird_name;
            $transaction[$key]->buyers_name = auth()->user()->name;
            $transaction[$key]->contest_date = $eventName->contest_date;
            $transaction[$key]->contest_time = $eventName->contest_time;

            $organizer = DetailContestOrganizer::where('id_contest', $val->id_contest)->where('soft_delete', 0)->get();
            $transaction[$key]->organizer = $organizer;

            $bank = Bank::where('id', $val->id_bank)->where('soft_delete', 0)->get();
            $transaction[$key]->bank = new class{};     
            if (count($bank) > 0) {
                $transaction[$key]->bank = $bank[0];
            }
        }

        return $res->responseGet(true, 200, $transaction[0], '');
    }

    public function getMobileHistory (Request $request) {
        $res = new JsonHelper;

        $contest =[];
        $transaction = Transaction::
                                where('id_user', auth()->user()->id)
                                ->where('status', 'done')
                                ->orderBy('created_at', 'DESC')
                                ->where('soft_delete', 0)->get();
        
        foreach ($transaction as $k => $val) {
            $contestTemp = Contest::
                            orderBy('contest_status')
                            ->orderBy('created_at', 'DESC')
                            ->where('id', $val->id_contest)
                            ->where('soft_delete', 0)->get();
            $contestTemp[0]->id_transaction = $val->id;
            $contestTemp[0]->bird_name_contestant = $val->bird_name_contestant;
            $contestTemp[0]->id_criteria = $val->id_criteria;
            $contestTemp[0]->id_criteria_contents = $val->id_criteria_contents;

            $criteria = DetailContestCriteria::where('id', $val->id_criteria)->first();
            $criteriaContent = DetailContestCriteriaContent::where('id', $val->id_criteria_contents)->first();

            $contestTemp[0]->bird_name = $criteriaContent->bird_name;
            $contestTemp[0]->criteria_name = $criteria->criteria_name;
            
            $numberContest = "-";
            $criteriaNumberGet = Transaction::where('id', $val->id)->get();
            if (count($criteriaNumberGet) > 0) {
                $numberContest = (string)$criteriaNumberGet[0]->contestant_number;
            }
            $contestTemp[0]->contestant_number = $numberContest;

            array_push($contest, $contestTemp[0]);
        }

        foreach($contest as $key => $val) {

            $banner = BannerContest::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            $contest[$key]->banner = $banner;
            
        }

        $response = $res->paginate($contest);

        return $res->responseGet(true, 200, $response, '');
    }

    public function getListPayment (Request $request) {
        $res = new JsonHelper;
        $transaction =[];
        $status = $request->input('status');

        if ($status == 'all') {
            $transaction = Transaction::
            orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)->get();
        } else {
            $transaction = Transaction::
            where('status', $status)
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)->get();
        }

        foreach($transaction as $key => $val) {
            $eventName = Contest::where('id', $val->id_contest)->first();
            $criteriaName = DetailContestCriteria::where('id', $val->id_criteria)->first();
            $birdName = DetailContestCriteriaContent::where('id', $val->id_criteria_contents)->first();
            $user = User::where('id', $val->id_user)->first();
            // return $birdName;
            $transaction[$key]->criteria_name = $criteriaName->criteria_name;
            $transaction[$key]->contest_name = $eventName->name;
            $transaction[$key]->bird_name = $birdName->bird_name;
            $transaction[$key]->buyers_name = $user->name;
            $transaction[$key]->contest_date = $eventName->contest_date;
            $transaction[$key]->contest_time = $eventName->contest_time;

            $bank = Bank::where('id', $val->id_bank)->where('soft_delete', 0)->get();
            $transaction[$key]->bank = new class{};
            if (count($bank) > 0) {
                $transaction[$key]->bank = $bank[0];
            }

        }

        return $res->responseGet(true, 200, $transaction, '');
    }

}
