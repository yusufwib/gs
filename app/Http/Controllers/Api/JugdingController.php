<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator, Carbon, DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Helper\ResponseHelper as JsonHelper;
use App\Models\Contest;
use App\Models\RandomNumberTemplate;
use App\Models\DetailContestCriteriaContent;
use App\Models\User;
use App\Models\Transaction;
use App\Models\ContestanScore;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;

class JugdingController extends Controller
{
    //

    // public function sanitizeArrayRandomNumber ()
    
    public function arrangeContestant (Request $request) {
        //validate is contest is close
        $res = new JsonHelper;
        $idContest = $request->input('id_contest');
        $id_random_template = $request->input('id_random_template');

        //need to validate
        $validatePastEvent = Contest::where('id', $idContest)->where('soft_delete', 0)->first();

        if ($validatePastEvent->is_open == 1) {
            return $res->responseGet(false, 200, 'This contest is not closed order by now', '');
        }

        $id_random_template = $request->input('id_random_template');
        $templateRandomNumber = RandomNumberTemplate::where('id', $id_random_template)->where('soft_delete', 0)->first();
        $arrayRandNum = explode(",", $templateRandomNumber->number);
        //neeed to validate
        // if (count($arrayRandNum) )
        shuffle($arrayRandNum);

        $contestContentAll = DetailContestCriteriaContent::where('id_contest', $idContest)->where('soft_delete', 0)->get();
        // return $arrayRandNum;
        foreach($contestContentAll as $key => $val) {
            $transaction =  Transaction::where('id_criteria_contents', $val->id)->where('soft_delete', 0)->get();
            if (count($arrayRandNum) >= count($transaction)) {
                foreach ($transaction as $k => $v) {
                    $updateNumber = Transaction::where('id', $v->id)->update([
                        'contestant_number' => $arrayRandNum[$k]
                    ]);
                }    
            } else {
                return $res->responsePost(false, 200, 'Fail arrange', 'Fail arrange');
            }
        }

        Contest::where('id', $idContest)->update([
            'id_template_number' => $id_random_template
        ]);
        
        return $res->responsePost(true, 200, 'Success', 'Success');

    }

    public function setBlock (Request $request) {
        $res = new JsonHelper;

        $updateNumber = Transaction::where('contestant_number', $request->input('contestant_number'))->where('id_contest', $request->input('id_contest'))->update([
            'contestant_block' => $request->input('contestant_block')
        ]);

        Contest::where('id', $request->input('id_contest'))->update([
            'ready_to_jugde' => 1
        ]);

        return $res->responsePost(true, 200, 'Success', 'Success');
    }

    
    public function getCurrentBlockByContest (Request $request) {
        $res = new JsonHelper;
        $idContest = $request->input('id_contest');

        $contest = Contest::where('id', $idContest)->first();
        $templateRandomNumber = RandomNumberTemplate::where('id', $contest->id_template_number)->where('soft_delete', 0)->first();

        $arrayRandNum = explode(",", $templateRandomNumber->number);
        $data = [];
        // $data = Transaction::where('id_contest', $idContest)->get();
        foreach ($arrayRandNum as $k => $v) {
            $transaction = Transaction::where('id_contest', $idContest)->where('contestant_number', $v)->where('status', 'done')->where('soft_delete', 0)->get();

            if (count($transaction) > 0) {
                array_push($data, [
                    'number' => $v,
                    'block' => $transaction[0]->contestant_block
                ]);
            } else {
                array_push($data, [
                    'number' => $v,
                    'block' => null
                ]);
            }

        }


        return $res->responseGet(true, 200, $data, '');
    }

    public function getBlock (Request $request) {
        $res = new JsonHelper;
        // $id_criteria_contents = $request->input('id_criteria_contents');

        // $transaction = Transaction::where('id_criteria_contents', $id_criteria_contents)->get();
        $id_random_template = $request->input('id_random_template');

        $templateRandomNumber = RandomNumberTemplate::where('id', $id_random_template)->where('soft_delete', 0)->first();
        $arrayRandNum = explode(",", $templateRandomNumber->number);

        return $res->responseGet(true, 200, $arrayRandNum, 'Success');
    }

    // logic for jury
    // create record from scratch (initialize)
    // check if created or not
    // get by jury id
    // done

    public function checkData (Request $request) {
        $res = new JsonHelper;

        $isAvailRoom = DetailContestCriteriaContent::where('jury_code', $request->input('jury_code'))->where('soft_delete', 0)->get();

        if (count($isAvailRoom) == 0) {
            return $res->responseGet(false, 400, null, 'Jury code not found!');
        } 

        $checkIsCreated = ContestanScore::where('id_criteria_contents', $request->input('id_criteria_contents'))
                                        ->where('id_jury', auth()->user()->id)
                                        ->where('soft_delete', 0)
                                        ->get();
                                        // $this->generateData($request->input('id_criteria_contents'), $request->input('id_jury'));

        if (count($checkIsCreated) == 0) {
            $this->generateData($request->input('id_criteria_contents'), auth()->user()->id);
            $checkIsCreatedAfter = ContestanScore::where('id_criteria_contents', $request->input('id_criteria_contents'))
                                                ->where('id_jury', auth()->user()->id)
                                                ->where('soft_delete', 0)
                                                ->get();
            return $res->responseGet(true, 200, $checkIsCreatedAfter, 'Success');   
        } else {
            return $res->responseGet(true, 200, $checkIsCreated, 'Success');
        }
        // $this->generateData($request->input('id_criteria_contents'), 666);

    }

    public function generateData ($idCriteriaContent, $idJury) {
        $res = new JsonHelper;  


        // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        // $firebase = (new Factory)
        //     ->withServiceAccount($serviceAccount)
        //     ->withDatabaseUri('https://gantangan-bc1f8-default-rtdb.asia-southeast1.firebasedatabase.app/')
        //     ->create();
        // $database = $firebase->getDatabase();
        // $newPost = $database
        // ->getReference(env('STAGE', 'dev') . '/livescore/' . 1 . '/' . 5)
        // // ->set($contestData[0]);
        // ->set([[
        //     'cok'
        // ]]);
        $getTransaction = Transaction::where('id_criteria_contents', $idCriteriaContent)
                                        ->where('status', 'done')
                                        ->where('soft_delete', 0)
                                        ->get();
        
        foreach ($getTransaction as $k => $v) {
            $contest = ContestanScore::create([
                'id_user' => $v->id_user,
                'id_contest' => $v->id_contest,
                'id_criteria' => $v->id_criteria,
                'id_criteria_contents' => $v->id_criteria_contents,
                'id_jury' => $idJury,
                'id_transaction' => $v->id,
                'contestant_number' => $v->contestant_number,
                'contestant_block' => $v->contestant_block
            ]);
            
            $contestData = ContestanScore::where('id', $contest->id)->where('soft_delete', 0)->get();

            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->withDatabaseUri('https://gantangan-bc1f8-default-rtdb.asia-southeast1.firebasedatabase.app/')
                ->create();
            $database = $firebase->getDatabase();
            $newPost = $database
            ->getReference(env('STAGE', 'dev') . '/livescore/' . $v->id_transaction .'/'. $idJury)
            ->set($contestData[0]);
            // ->set([
            //     'cok' => [
            //         'cok'
            //     ],
            //     'cok' => [
            //         'cok'
            //     ]
            // ]);
        }
        

    }

    public function updateDataContestant (Request $request) {
        $res = new JsonHelper;

        $update = ContestanScore::where('id', $request->input('id'))->update([
            'score_irama_lagu_roll' => $request->input('score_irama_lagu_roll'),
            'score_irama_lagu_tembak' => $request->input('score_irama_lagu_tembak'),
            'score_durasi' => $request->input('score_durasi'),
            'score_volume' => $request->input('score_volume'),
            'score_gaya' => $request->input('score_gaya'),
            'score_fisik' => $request->input('score_fisik'),
            'score' => $request->input('score'),
            'score_description' => $request->input('score_description'),
        ]);
        
        $contestData = ContestanScore::where('id', $request->input('id'))->where('soft_delete', 0)->get();
        // return $contestData;
        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/FirebaseKey.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://gantangan-bc1f8-default-rtdb.asia-southeast1.firebasedatabase.app/')
            ->create();
        $database = $firebase->getDatabase();
        $newPost = $database
        ->getReference(env('STAGE', 'dev') . '/livescore/' . $contestData[0]->id_transaction . '/' . $contestData[0]->id_jury)
        ->set($contestData[0]);

        return $res->responseGet(true, 200, null, 'Success');
    }

    public function updateDataContestantKoncer (Request $request) {
        $res = new JsonHelper;

        $payload = $request->input('koncer');
        $payloadKoncer = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $payload), true );

        usort($payloadKoncer, function($a, $b) {
            return strcmp($a['koncer'], $b['koncer']);
        });

        // return $payloadKoncer;
        foreach ($payloadKoncer as $k => $v) {
            $update = ContestanScore::where('id', $v['id'])->update([
                'koncer' => $v['koncer'],
                'koncer_position' => $v['koncer_position']
            ]);
        }

        return $res->responseGet(true, 200, null, 'Success');
    }

    public function koncerDetailsWeb (Request $request) {
        $res = new JsonHelper;

        $idCriteriaContent = $request->input('id_criteria_contents');

        $data = ContestanScore::where('id_criteria_contents', $idCriteriaContent)->where('koncer', 1)->get();

        foreach ($data as $k => $v) {
            $jury = User::where('id', $v->id_jury)->first();
            $data[$k]->jury_name = $jury->name;
        }

        return $res->responseGet(true, 200, null, 'Success');
    }

}
