<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Carbon, DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Helper\ResponseHelper as JsonHelper;
use App\Models\Contest;
use App\Models\DetailContestOrganizer;
use App\Models\DetailContestCriteria;
use App\Models\DetailContestCriteriaContent;
use App\Models\DetailContestCriteriaContentChampionPrize;
use App\Models\BannerContest;
use App\Models\ContestSchedule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Role;
use App\Models\ContestJury;
use App\Models\ContestanScore;
use stdClass;

class ContestController extends Controller
{
    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function getCities () {
        $res = new JsonHelper;

        $data = DB::table('cities')->select('city_id', 'city_name_full')->get();
        return $res->responseGet(true, 200, $data, '');
    }

    public function getDetailContestContestant (Request $request) {
        $res = new JsonHelper;
        $data = [];
        $idCriteria = $request->input('id_criteria');

        if ($idCriteria == 'all') {
            $data = DetailContestCriteriaContent::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->get();
        } else {
            $data = DetailContestCriteriaContent::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->where('id_detail_contest_criteria', $request->input('id_criteria'))->get();
        }

        foreach ($data as $k => $v) {
            $criteriaName = DetailContestCriteria::where('id', $v->id_detail_contest_criteria)->where('soft_delete', 0)->first();
            $registered = Transaction::where('id_criteria_contents', $v->id)->where('status', 'done')->where('soft_delete', 0)->count();
            $data[$k]->criteria_name = $criteriaName->criteria_name;
            $data[$k]->registered_participants = $registered;
        }
        return $res->responseGet(true, 200, $data, '');
    }

    public function getDetailContestContestantJury (Request $request) {
        $res = new JsonHelper;

        $data = DetailContestCriteriaContent::where('id', $request->input('id_criteria_contents'))->where('soft_delete', 0)->get();

        foreach ($data as $k => $v) {
            $criteriaName = DetailContestCriteria::where('id', $v->id_detail_contest_criteria)->where('soft_delete', 0)->first();
            $registered = Transaction::where('id_criteria_contents', $v->id)->where('status', 'done')->where('soft_delete', 0)->count();
            $data[$k]->criteria_name = $criteriaName->criteria_name;
            $data[$k]->registered_participants = $registered;
        }

        $dataKoncerArranged = [];
        $dataJury = ContestJury::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->get();

        foreach ($dataJury as $key1 => $val1) {
            $juryDetail = User::where('id', $val1->id_jury)->where('soft_delete', 0)->get();
            $dataJury[$key1]->jury_name = $juryDetail[0]->name;
            $dataJury[$key1]->jury_email = $juryDetail[0]->email;
        }

        foreach ($dataJury as $k => $v) {
            $jury = User::where('id', $v->id_jury)->where('soft_delete', 0)->first();
            // $dataKoncer[$key]->jury_name = $jury->name;
            $koncer = ContestanScore::select('koncer_position', 'contestant_number')
                                        ->where('id_criteria_contents', $request->input('id_criteria_contents'))
                                        ->where('koncer', 1)
                                        ->where('id_jury', $v->id_jury)
                                        ->orderBy('koncer_position', 'ASC')
                                        ->where('soft_delete', 0)
                                        ->get();
            $arrTemp = [
                'jury_name' => $jury->name,
                'data_koncer' => $koncer
            ];

            array_push($dataKoncerArranged, $arrTemp);
        }

        $arrRes = [
            'detail_content' => $data[0],
            'detail_koncer' => $dataKoncerArranged,
            'data_jury' => $dataJury
        ];
        return $res->responseGet(true, 200, $arrRes, '');
    }

    //hcokkkkkkkkkkkk

    public function getWinnerPosition (Request $request) {
        $res = new JsonHelper;
        $koncer = ContestanScore::where('id_criteria_contents', $request->input('id_criteria_contents'))
                                        ->where('is_winner', 1)
                                        ->where('soft_delete', 0)
                                        ->get();
        if (count($koncer) == 0) {
            $idCriteriaContent = $request->input('id_criteria_contents');

            $koncer = ContestanScore::select('contestant_number', DB::raw('COUNT(contestant_number) as count')) 
                                            ->where('id_criteria_contents', $request->input('id_criteria_contents'))
                                            ->where('koncer', 1)
                                            ->orderBy('koncer_position', 'ASC')
                                            ->where('soft_delete', 0)
                                            ->groupBy('contestant_number')
                                            ->get();
    
            return $res->responseGet(true, 200, $koncer, 'Success');            
        } else {
            
            $idCriteriaContent = $request->input('id_criteria_contents');

            $koncer = ContestanScore::select('contestant_number', DB::raw('COUNT(contestant_number) as count'))
                                            ->where('id_criteria_contents', $request->input('id_criteria_contents'))
                                            ->where('is_winner', 1)
                                            ->orderBy('winner_position', 'ASC')
                                            ->where('soft_delete', 0)
                                            ->groupBy('contestant_number')
                                            ->get();
    
            return $res->responseGet(true, 200, $koncer, 'Success');
        }

    }

    public function setUpdateWinner (Request $request) {
        $res = new JsonHelper;

        $payload = $request->input('winner');
        $payloadKoncer = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $payload), true );

        usort($payloadKoncer, function($a, $b) {
            return strcmp($a['is_winner'], $b['is_winner']);
        });

        // return $payloadKoncer;
        foreach ($payloadKoncer as $k => $v) {
            $update = ContestanScore::where('contestant_number', $v['contestant_number'])->where('id_criteria_contents', $v['id_criteria_contents'])->update([
                'is_winner' => $v['is_winner'],
                'winner_position' => $v['winner_position']
            ]);
        }

        return $res->responseGet(true, 200, null, 'Success');
    }
    public function getDetailContestContestantJuryScore (Request $request) {
        $res = new JsonHelper;

        $data = ContestanScore::where('id_jury', $request->input('id_jury'))->where('id_criteria_contents', $request->input('id_criteria_contents'))->where('soft_delete', 0)->get();
        $jury = User::where('id', $request->input('id_jury'))->where('soft_delete', 0)->first();

        $arrRes = [
            'jury_name' => $jury->name,
            'score' => $data
        ];

        return $res->responseGet(true, 200, $arrRes, '');
    }

    public function getDetailContestContestantDetailContent (Request $request) {
        $res = new JsonHelper;

        $data = Transaction::select('id', 'id_ticket', 'id_criteria', 'id_user', 'id_criteria_contents', 'id_contest', 'contestant_number', 'contestant_block', 'created_at')->where('soft_delete', 0)->where('id_criteria_contents', $request->input('id_criteria_contents'))->get();
        foreach ($data as $k => $v) {
            $criteriaName = DetailContestCriteria::where('id', $v->id_criteria)->where('soft_delete', 0)->first();
            $content = DetailContestCriteriaContent::where('id', $request->input('id_criteria_contents'))->where('soft_delete', 0)->first();
            $data[$k]->criteria_name = $criteriaName->criteria_name;
            $userName = User::where('id', $v->id_user)->where('soft_delete', 0)->first();
            $data[$k]->user_name = $userName->name;
            $data[$k]->bird_name = $content->bird_name;

        }
        return $res->responseGet(true, 200, $data, '');
    }

    public function updateRegisterOpen (Request $request) {
        $res = new JsonHelper;

        Contest::where('id', $request->input('id_contest'))->update([
            'is_open' => $request->input('is_open')
        ]);

        return $res->responseGet(true, 200, null, '');
    }

    public function updateContestStatus (Request $request) {
        $res = new JsonHelper;

        Contest::where('id', $request->input('id_contest'))->update([
            'contest_status' => $request->input('contest_status')
        ]);

        return $res->responseGet(true, 200, null, '');
    }

    public function updateDraftStatus (Request $request) {
        $res = new JsonHelper;

        Contest::where('id', $request->input('id_contest'))->update([
            'drafted' => $request->input('drafted')
        ]);

        return $res->responseGet(true, 200, null, '');
    }

    public function createContest (Request $request) {

        // $int = range(1,25);

        // foreach ($int as $k => $v) {
        //     Contest::where('id', $v)->update([
        //         'name' => 'Gantangan Sulton' . $v
        //     ]);
        // }
        $res = new JsonHelper;
        $payload = $request->all();
        // return $this->generateRandomString(10);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $i = $request->file('banner');
        // return count($i);
        try {
            $contest = Contest::create(array_merge(
                $validator->validated(),
                [
                    'name' => $payload['name'],
                    'city' => $payload['city'],
                    'start_register' => $payload['start_register'],
                    // 'end_register' => $payload['end_register'],
                    'contest_date' => $payload['contest_date'],
                    'contest_time' => $payload['contest_time'],
                    'location_name' => $payload['location_name'],
                    'location_address' => $payload['location_address'],
                    'contest_terms' => $payload['contest_terms'],
                    'drafted' => $payload['drafted'],
                    'contest_status' => 'upcoming',
                    'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                ]
            ));

            if ($contest) {

                if ($request->input('fill_jury') == 1) {
                    $arrayListJury = $payload['list_jury'];
                    $listJury = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $arrayListJury), true );

                    foreach ( $listJury as $key => $val) {
                        ContestJury::insert([
                            'id_contest' => $contest->id,
                            'id_jury' => $val['id_jury']
                        ]);
                    }
                }
                //banner
                if (count($i) > 0) {
                    foreach($i as $key => $val) {
                        // return $val;
                        $banner_name = 'banner-' . uniqid() . $res->generateRandomString(30) . '.'.$val->getClientOriginalExtension();
                        Storage::disk('public')->put($banner_name,File::get($val));
                        // return $val;
                        BannerContest::insert([
                            'url' => '/storage/' . $banner_name,
                            'id_contest' => $contest->id
                        ]);
                        // return $val;
                    }

                } else {
                    Contest::where('id', $contest->id)->update([
                            'soft_delete' => 1
                        ]);
                    return $res->responseGet(false, 400, null, 'please fill the img first');
                }
                //organizer
                $arrayContestOrganizer = $payload['contest_organizer'];
                $contestOrganizer = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $arrayContestOrganizer), true );
                // return $contestOrganizer;

                foreach ($contestOrganizer as $key => $v) {
                    $detailContestOrganizer =  new DetailContestOrganizer;
                    $detailContestOrganizer->id_contest = $contest->id;
                    $detailContestOrganizer->name = $v['name'];
                    $detailContestOrganizer->phone = $v['phone'];
                    $detailContestOrganizer->created_at = Carbon\Carbon::now('Asia/Jakarta');
                    $detailContestOrganizer->updated_at = Carbon\Carbon::now('Asia/Jakarta');
                    $detailContestOrganizer->save();
                }

                //criteria
                $arrayDetailContestCriteria = $payload['contest_criteria'];
                $contestCriteria = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $arrayDetailContestCriteria), true );
                foreach ($contestCriteria as $key => $v) {
                    $detailContestCriteria = DetailContestCriteria::create(
                        [
                            'id_contest' => $contest->id,
                            'registration_fee' => $v['registration_fee'],
                            'fixed_price' => $v['fixed_price'],
                            'criteria_name' => $v['criteria_name'],
                            'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                            'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                        ]
                    );

                    if ($detailContestCriteria) {

                        foreach ($v['birds'] as $birdKey => $birdValue) {
                            // return $birdValue['bird_name'];
                            $otp = rand(1000, 9999);
                            DetailContestCriteriaContent::create(
                                [
                                    'id_detail_contest_criteria' => $detailContestCriteria->id,
                                    'bird_name' => $birdValue['bird_name'],
                                    'participants' => $birdValue['participants'],
                                    'jury_code' => $this->generateRandomString(10),
                                    'id_contest' => $contest->id,
                                    'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                                ]
                            );
                            // return '1';
                        }
                        foreach ($v['prizes'] as $prizeKey => $prizeValue) {
                            DetailContestCriteriaContentChampionPrize::create(
                                [
                                    'id_detail_contest_criteria' => $detailContestCriteria->id,
                                    'champion_prize' => $prizeValue['champion_prize'],
                                    'champion_title' => $prizeValue['champion_title'],
                                    'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                                ]
                            );
                        }
                    }
                }
            }
            return $res->responsePost(true, 201, null);
        } catch (Exception $e) {
            return $res->responsePost(false, 400, 'Error when creating.');
        }
    }


    public function updateContest (Request $request) {

        $res = new JsonHelper;
        $payload = $request->all();
        // return $this->generateRandomString(10);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100'
        ]);
        
        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        $i = $request->file('banner');
        try {
            $contest = Contest::where('id', $payload['id_contest'])->update(array_merge(
                $validator->validated(),
                [
                    'name' => $payload['name'],
                    'city' => $payload['city'],
                    'start_register' => $payload['start_register'],
                    // 'end_register' => $payload['end_register'],
                    'contest_date' => $payload['contest_date'],
                    'contest_time' => $payload['contest_time'],
                    'location_name' => $payload['location_name'],
                    'location_address' => $payload['location_address'],
                    'contest_terms' => $payload['contest_terms'],   
                    'drafted' => $payload['drafted'],
                    'contest_status' => 'upcoming',
                    'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
                ] 
            ));
    
            if ($payload['id_contest'] != 0) {                
                
                //banner
                if (count($i) > 0) {
                    BannerContest::where('id_contest', $payload['id_contest'])->delete();
                    foreach($i as $key => $val) {
                        // return $val;
                        $banner_name = 'banner-' . uniqid() . $res->generateRandomString(30) . '.'.$val->getClientOriginalExtension();
                        Storage::disk('public')->put($banner_name,File::get($val));
                        // return $val;
                        BannerContest::insert([
                            'url' => '/storage/' . $banner_name,
                            'id_contest' => $payload['id_contest']
                        ]);
                        // return $val;
                    }
        
                }
            }
            return $res->responsePost(true, 201, "Success update contest");
        } catch (Exception $e) {
            return $res->responsePost(false, 400, 'Error when updating.');
        } 
    }

    public function addContestJury (Request $request) {
        $res = new JsonHelper;
        $payload = $request->all();
        $arrayListJury = $payload['list_jury'];
        $listJury = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $arrayListJury), true );

        foreach ( $listJury as $key => $val) {
            ContestJury::insert([
                'id_contest' => $val['id_contest'],
                'id_jury' => $val['id_jury']
            ]);
        }

        return $res->responsePost(true, 201, null);
    }

    public function getScheduleMaster (Request $request) {
        $res = new JsonHelper;

        $dataRes = [];
        $data = DetailContestCriteriaContent::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->orderBy('number', 'ASC')->get();

        foreach ($data as $k => $v) {
            $criteriaName = DetailContestCriteria::where('id', $v->id_detail_contest_criteria)->first();
            // return $criteriaName;
            $strComb = $v->bird_name . ' - ' . $criteriaName->criteria_name;
            array_push($dataRes, $strComb);
        }

        return $res->responseGet(true, 200, $dataRes, '');
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

        $getMember = User::
                            where('id', auth()->user()->id)
                            ->first();
                            // return $getMember;
        $getMaxBuy = Role::where('id', $getMember->role_id)->first();
        // return $getMaxBuy;
        $maxBuy = $getMaxBuy->max_buy;

        $criteriaId = $request->input('id_criteria');

        $currentBuy = Transaction::
                                    where('id_user', auth()->user()->id)
                                ->where('id_criteria', $criteriaId)
                                ->count();

        $paid = Transaction::
                                where('id_criteria', $criteriaId)
                                ->where('status', 'done')
                                ->count();

        $participantsGet = DetailContestCriteriaContent::
                                where('id', $request->input('id_criteria_contents'))
                                ->first();

        $participants = $participantsGet->participants;

        $response = [
            'user_current_buy' => (int)$currentBuy,
            'user_max_buy' => (int)$maxBuy,
            'criteria_participants_total' => (int)$participants,
            'criteria_participants_paid' => (int)$paid,
        ];
        return $res->responseGet(true, 200, $response, '');
    }

    public function getMobile (Request $request) {
        $res = new JsonHelper;
        $search_query = $request->input('search_query');
        $status = $request->input('status');

        $contest =[];

        if ($status == 'both') {
            $contest = Contest::
                // where('contest_status', $status)
                where('contests.name', 'like', '%' . $search_query . '%')
                ->orderBy('contest_status')
                ->orderBy('created_at', 'DESC')
                ->where('soft_delete', 0)
                ->paginate(10);
        } else {
            $contest = Contest::
                where('contest_status', $status)
                ->where('contests.name', 'like', '%' . $search_query . '%')
                ->orderBy('contest_status')
                ->orderBy('created_at', 'DESC')
                ->where('soft_delete', 0)
                ->paginate(10);
        }

        foreach($contest as $key => $val) {
            $banner = BannerContest::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            $contest[$key]->banner = $banner;

            $organizer = DetailContestOrganizer::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            $contest[$key]->organizer = $organizer;

            $schedule = ContestSchedule::where('id_contest', $val->id)->where('soft_delete', 0)->orderBy('number', 'ASC')->get();
            $contest[$key]->schedule = $schedule;

            $criteria = DetailContestCriteria::where('id_contest', $val->id)->where('soft_delete', 0)->get();

            foreach ($criteria as $key1 => $val1) {
                $criteriaContent = DetailContestCriteriaContent::where('id_detail_contest_criteria', $val1->id)->where('soft_delete', 0)->get();
                $criteria[$key1]->content = $criteriaContent;
            }

            foreach ($criteria as $key1 => $val1) {
                $prize = DetailContestCriteriaContentChampionPrize::where('id_detail_contest_criteria', $val1->id)->where('soft_delete', 0)->get();
                $criteria[$key1]->prize = $prize;
            }
            $contest[$key]->criteria = $criteria;
        }

        // if (count($contest) == 0) {
        //     $contest = $res->paginate($contest);
        // }

        return $res->responseGet(true, 200, $contest, '');
    }

    public function getMobileById (Request $request) {
        $res = new JsonHelper;
        $id_contest = $request->input('id');

        $contest = Contest::
            where('id', $id_contest)
            ->orderBy('contest_status')
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)
            ->get();

        foreach($contest as $key => $val) {
            $banner = BannerContest::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            $contest[$key]->banner = $banner;

            $organizer = DetailContestOrganizer::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            $contest[$key]->organizer = $organizer;

            $schedule = ContestSchedule::where('id_contest', $val->id)->where('soft_delete', 0)->orderBy('number', 'ASC')->get();
            $contest[$key]->schedule = $schedule;

            $criteria = DetailContestCriteria::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            
            foreach ($criteria as $key1 => $val1) {
                $criteriaContent = DetailContestCriteriaContent::where('id_detail_contest_criteria', $val1->id)->where('soft_delete', 0)->get();
                foreach ($criteriaContent as $key2 => $val2) {
                    $koncer = ContestanScore::select(DB::raw("DISTINCT contestant_number, winner_position, users.name as username"))
                            ->join('users', 'contestan_scores.id_user', '=', 'users.id')
                            ->where('id_criteria_contents', $val2->id)
                            ->where('is_winner', 1)
                            ->orderBy('winner_position', 'ASC')
                            ->where('contestan_scores.soft_delete', 0)
                            // ->groupBy('contestant_number')
                            ->get();

                    $criteriaContent[$key2]->winner = $koncer;
                }

                
                $criteria[$key1]->content = $criteriaContent;

            }

            foreach ($criteria as $key1 => $val1) {
                $prize = DetailContestCriteriaContentChampionPrize::where('id_detail_contest_criteria', $val1->id)->where('soft_delete', 0)->get();
                $criteria[$key1]->prize = $prize;
            }
            $contest[$key]->criteria = $criteria;

            $jurycontest = ContestJury::where('id_contest', $val->id)->where('soft_delete', 0)->get();
            $contest[$key]->list_jury = $jurycontest;

            // $koncer = [];

            // foreach ($criteria as $key1 => $val1) {
            //     $koncer = ContestanScore::select('contestant_number', DB::raw('COUNT(contestant_number) as count'), 'winner_position', 'users.name as username')
            //                             ->join('users', 'contestant_score.id_user', '=', 'users.id')
            //                             ->where('id_criteria_contents', $val1->id)
            //                             ->where('is_winner', 1)
            //                             ->orderBy('winner_position', 'ASC')
            //                             ->where('soft_delete', 0)
            //                             ->groupBy('contestant_number')
            //                             ->get();
                
            //     $criteria[$key1]->prize = $prize;

            // }
            // foreach ($koncer as $key1 => $val1) {
            // }

            // $contest[$key]->winner = $koncer;
                        
                        
        }

        return $res->responseGet(true, 200, $contest[0], '');
    }

    public function getWeb (Request $request) {
        $res = new JsonHelper;

        $status = $request->input('status');

        if ($status == 'all') {
            $contest = Contest::
                            orderBy('created_at', 'DESC')
                            ->where('soft_delete', 0)
                            ->get();
        } else {
            $contest = Contest::
            where('contest_status', $status)
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)
            ->get();
        }


        foreach($contest as $key => $val) {
            $totalCriteria = DetailContestCriteria::where('id_contest', $val->id)->count();
            // return $totalCriteria;
            $contest[$key]->total_criteria = $totalCriteria;
        }

        return $res->responseGet(true, 200, $contest, '');
    }

    public function getWebDetail (Request $request) {
        $res = new JsonHelper;

        $id_contest = $request->input('id_contest');

        $contest = Contest::
            where('id', $id_contest)
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)
            ->get();
        if (count($contest) > 0) {
            foreach($contest as $key => $val) {
                $banner = BannerContest::where('id_contest', $val->id)->where('soft_delete', 0)->get();
                $contest[$key]->banner = $banner;
    
                $organizer = DetailContestOrganizer::where('id_contest', $val->id)->where('soft_delete', 0)->get();
                $contest[$key]->organizer = $organizer;
    
                $criteria = DetailContestCriteria::where('id_contest', $val->id)->where('soft_delete', 0)->get();
    
                $schedule = ContestSchedule::where('id_contest', $val->id)->where('soft_delete', 0)->orderBy('number', 'ASC')->get();
                $contest[$key]->schedule = $schedule;
    
                $jurycontest = ContestJury::where('id_contest', $val->id)->where('soft_delete', 0)->get();
                if (count($jurycontest) != 0) {
                    foreach ($jurycontest as $key1 => $val1) {
                        $juryDetail = User::where('id', $val1->id_jury)->where('soft_delete', 0)->get();
                        // $jurycontest[$key1]->jury = $juryDetail;
                        $jurycontest[$key1]->name = $juryDetail[0]->name;
                        $jurycontest[$key1]->jury_email = $juryDetail[0]->email;
                    }
                }
    
                // $jurycontest = ContestJury::where('id_contest',$val->id)->where('soft_delete', 0)->get();
                // if (count($jurycontest) > 0) {
                //     $juryDetail = User::where('id', $jurycontest->id_user)->where('soft_delete', 0)->first();
                //     $jurycontest[0]->jury_name = $juryDetail->name;
                // }
    
                $contest[$key]->jury = $jurycontest;
    
                foreach ($criteria as $key1 => $val1) {
                    $criteriaContent = DetailContestCriteriaContent::where('id_detail_contest_criteria', $val1->id)->where('soft_delete', 0)->get();
                    $criteria[$key1]->content = $criteriaContent;
                }
    
                foreach ($criteria as $key1 => $val1) {
                    $prize = DetailContestCriteriaContentChampionPrize::where('id_detail_contest_criteria', $val1->id)->where('soft_delete', 0)->get();
                    $criteria[$key1]->prize = $prize;
                }
                $contest[$key]->criteria = $criteria;
            }
            return $res->responseGet(true, 200, $contest[0], '');
        } else {
            return $res->responseGet(false, 400, [], 'contest not found');
        }



    }

    public function getWebDraft (Request $request) {
        $res = new JsonHelper;

        $contest = Contest::
            where('drafted', 1)
            ->orderBy('created_at', 'DESC')
            ->where('soft_delete', 0)
            ->get();

        return $res->responseGet(true, 200, $contest, '');
    }

    public function addSchedule (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id_contest' => 'required',
            'number' => 'required',
            'hours' => 'required',
            'criteria_content' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }

        ContestSchedule::insert(
            array_merge($validator->validated(),
            [
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ])
        );

        return $res->responsePost(true, 201, 'Success', '');

    }

    public function updateSchedule (Request $request) {
        $res = new JsonHelper;

        $validator = Validator::make($request->all(), [
            'id_contest' => 'required',
            'number' => 'required',
            'hours' => 'required',
            'criteria_content' => 'required'
        ]);

        if($validator->fails()){
            return $res->responseGet(false, 400, null, $validator->errors()->first());
        }
        ContestSchedule::where('id', $request->input('id'))->update(
            array_merge($validator->validated(),
            [
                'created_at' => Carbon\Carbon::now('Asia/Jakarta'),
                'updated_at' => Carbon\Carbon::now('Asia/Jakarta')
            ])
        );

        return $res->responsePost(true, 201, 'Success', '');

    }

    public function deleteSchedule (Request $request) {
        $res = new JsonHelper;

        ContestSchedule::where('id', $request->input('id'))->update([
                            'soft_delete' => 1
                        ]);

        return $res->responsePost(true, 201, 'Success', '');
    }






    public function getJuryByContest (Request $request) {
        $res = new JsonHelper;

        $data = ContestJury::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->get();
        foreach ($data as $key1 => $val1) {
            $juryDetail = User::where('id', $val1->id_jury)->where('soft_delete', 0)->get();
            $data[$key1]->jury_name = $juryDetail[0]->name;
            $data[$key1]->jury_email = $juryDetail[0]->email;
            // $data[$key1]->jury_name = $juryDetail;

        }
        return $res->responseGet(true, 200, $data, '');
    }

    public function createJuryByContest (Request $request) {
        $res = new JsonHelper;

        $data = ContestJury::insert([
            'id_jury' => $request->input('id_jury'),
            'id_contest' => $request->input('id_contest')
        ]);

        return $res->responseGet(true, 200, $data, '');
    }    

    public function updateJuryByContest (Request $request) {
        $res = new JsonHelper;

        $data = ContestJury::where('id', $request->input('id'))->update([
            'id_jury' => $request->input('id_jury'),
            'id_contest' => $request->input('id_contest')
        ]);

        return $res->responseGet(true, 200, $data, '');
    }    

    public function deleteJuryByContest (Request $request) {
        $res = new JsonHelper;

        $data = ContestJury::where('id', $request->input('id'))->update([
            'soft_delete' => 1,
        ]);

        return $res->responseGet(true, 200, $data, '');
    }    





    
    public function getJuryByOrganizer (Request $request) {
        $res = new JsonHelper;

        $data = DetailContestOrganizer::where('id_contest', $request->input('id_contest'))->where('soft_delete', 0)->get();

        return $res->responseGet(true, 200, $data, '');
    }

    public function createJuryByOrganizer (Request $request) {
        $res = new JsonHelper;

        $data = DetailContestOrganizer::insert([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'id_contest' => $request->input('id_contest')
        ]);

        return $res->responseGet(true, 200, $data, '');
    }    

    public function updateJuryByOrganizer (Request $request) {
        $res = new JsonHelper;

        $data = DetailContestOrganizer::where('id', $request->input('id'))->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),

            'id_contest' => $request->input('id_contest')
        ]);

        return $res->responseGet(true, 200, $data, '');
    }    

    public function deleteJuryByOrganizer (Request $request) {
        $res = new JsonHelper;

        $data = DetailContestOrganizer::where('id', $request->input('id'))->update([
            'soft_delete' => 1,
        ]);
        

        return $res->responseGet(true, 200, $data, '');
    }    


    //CRUD cok
}
