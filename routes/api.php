<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ContestController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\CashFlowController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\RandomNumberTemplateController;
use App\Http\Controllers\Api\JugdingController;
use App\Http\Controllers\Api\AnalyzerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/optimize', function () {
    Artisan::call('optimize');
    return true;
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/login-user', [AuthController::class, 'loginUser']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
        Route::post('/verifiy-otp', [AuthController::class, 'otpVerify']);
        Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
        Route::post('/change-phone-number', [AuthController::class, 'changePhoneNumber']);
        Route::post('/change-name', [AuthController::class, 'changeUserName']);
        Route::post('/reset-password', [AuthController::class, 'resetPasswordUser']);
        Route::post('/reset-password-resend-otp', [AuthController::class, 'resetPasswordUser']);
        Route::post('/reset-password-verification', [AuthController::class, 'resetPasswordUserVerification']);
        Route::post('/reset-password-change', [AuthController::class, 'resetPasswordUserChange']);
    });

    Route::group(['prefix' => 'contest'], function () {
        Route::post('/create', [ContestController::class, 'createContest']);
        Route::post('/update', [ContestController::class, 'updateContest']);
        Route::post('/get-mobile', [ContestController::class, 'getMobile']);
        Route::post('/get-mobile-by-id', [ContestController::class, 'getMobileById']);
        Route::post('/get-web', [ContestController::class, 'getWeb']);
        Route::post('/get-web-detail', [ContestController::class, 'getWebDetail']);
        Route::post('/get-web-detail-participants', [ContestController::class, 'getDetailContestContestant']);
        Route::post('/get-web-drafted', [ContestController::class, 'getWebDraft']);
        Route::post('/get-web-detail-participants-content', [ContestController::class, 'getDetailContestContestantDetailContent']);
        Route::post('/get-web-detail-participants-jury', [ContestController::class, 'getDetailContestContestantJury']);
        Route::post('/get-web-detail-participants-jury-score', [ContestController::class, 'getDetailContestContestantJuryScore']);
        Route::post('/get-web-detail-participants-jury-winner', [ContestController::class, 'getWinnerPosition']);

        Route::post('/get-web-detail-jury-score', [JugdingController::class, 'koncerDetailsWeb']);

        Route::post('/set-winner', [ContestController::class, 'setUpdateWinner']);

        Route::post('/cities', [ContestController::class, 'getCities']);
        Route::post('/add-jury', [ContestController::class, 'addContestJury']);

        Route::post('/add-schedule', [ContestController::class, 'addSchedule']);
        Route::post('/update-schedule', [ContestController::class, 'updateSchedule']);
        Route::post('/delete-schedule', [ContestController::class, 'deleteSchedule']);
        Route::post('/get-contest-schedule-master', [ContestController::class, 'getScheduleMaster']);

        Route::post('/current-buy', [TransactionController::class, 'getCurrentBuy']);

        Route::group(['prefix' => 'jugding'], function () {
            Route::post('/arrange-contestant', [JugdingController::class, 'arrangeContestant']);
            Route::post('/get-block-contestant', [JugdingController::class, 'getBlock']);
            Route::post('/set-block-contestant', [JugdingController::class, 'setBlock']);
            Route::post('/current-block', [JugdingController::class, 'getCurrentBlockByContest']);
        });


        Route::group(['prefix' => 'jury'], function () {
            Route::post('/get-by-contest', [ContestController::class, 'getJuryByContest']);
            Route::post('/update-by-contest', [ContestController::class, 'updateJuryByContest']);
            Route::post('/create-by-contest', [ContestController::class, 'createJuryByContest']);
            Route::post('/delete-by-contest', [ContestController::class, 'deleteJuryByContest']);
        });

        Route::group(['prefix' => 'organizer'], function () {
            Route::post('/get-by-contest', [ContestController::class, 'getJuryByOrganizer']);
            Route::post('/update-by-contest', [ContestController::class, 'updateJuryByOrganizer']);
            Route::post('/create-by-contest', [ContestController::class, 'createJuryByOrganizer']);
            Route::post('/delete-by-contest', [ContestController::class, 'deleteJuryByOrganizer']);
        });

        Route::post('/update-register-open', [ContestController::class, 'updateRegisterOpen']);
        Route::post('/update-contest-status', [ContestController::class, 'updateContestStatus']);
        Route::post('/update-draft-status', [ContestController::class, 'updateDraftStatus']);

    });

    Route::group(['prefix' => 'role'], function () {
        Route::post('/get-by-id', [RoleController::class, 'getById']);
        Route::post('/get', [RoleController::class, 'get']);
        Route::post('/create', [RoleController::class, 'create']);
        Route::post('/update', [RoleController::class, 'update']);
        Route::post('/delete', [RoleController::class, 'delete']);
    });

    Route::group(['prefix' => 'random-number-template'], function () {
        Route::post('/get-by-id', [RandomNumberTemplateController::class, 'getById']);
        Route::post('/get', [RandomNumberTemplateController::class, 'get']);
        Route::post('/create', [RandomNumberTemplateController::class, 'create']);
        Route::post('/update', [RandomNumberTemplateController::class, 'update']);
        Route::post('/delete', [RandomNumberTemplateController::class, 'delete']);
    });

    Route::group(['prefix' => 'account'], function () {
        Route::post('/register-admin-or-jury', [AccountController::class, 'registerAdminJury']);
        Route::post('/get-by-role-type', [AccountController::class, 'getByRoleType']);
        Route::post('/update-member-previlege', [AccountController::class, 'updateMemberPrevilege']);
        Route::post('/delete', [AccountController::class, 'delAccount']);
        Route::post('/update', [AccountController::class, 'updateAccount']);

        Route::post('/master-member', [AccountController::class, 'masterMember']);
        Route::post('/master-member-update', [AccountController::class, 'masterMemberUpdate']);
        Route::post('/master-member-delete', [AccountController::class, 'masterMemberDelete']);
        Route::post('/master-member-create', [AccountController::class, 'masterMemberCreate']);

        Route::post('/update-name', [AccountController::class, 'updateMemberName']);

        Route::post('/verify-jury', [AccountController::class, 'updateVerifyAdmin']);

        Route::post('/history-contest-by-id-user', [TransactionController::class, 'getUserProjectById']);
    });

    Route::group(['prefix' => 'bank'], function () {
        Route::post('/create', [BankController::class, 'create']);
        Route::post('/get', [BankController::class, 'get']);
        Route::post('/get-masterbank', [BankController::class, 'getMasterBank']);
        Route::post('/update', [BankController::class, 'update']);
        Route::post('/delete', [BankController::class, 'delete']);
        Route::post('/get-by-id', [BankController::class, 'getById']);
    });

    Route::group(['prefix' => 'cash-flow'], function () {
        Route::post('/create', [CashFlowController::class, 'create']);
        Route::post('/get', [CashFlowController::class, 'get']);
        Route::post('/update', [CashFlowController::class, 'update']);
        Route::post('/delete', [CashFlowController::class, 'delete']);
        Route::post('/get-by-id', [CashFlowController::class, 'getById']);
        Route::post('/get-by-contest', [CashFlowController::class, 'getByContest']);
    });

    Route::group(['prefix' => 'transaction'], function () {
        Route::post('/check-slot', [TransactionController::class, 'getSlot']);
        Route::post('/buy', [TransactionController::class, 'buyTicket']);
        Route::post('/buy-from-admin', [TransactionController::class, 'buyTicketFromAdmin']);
        Route::post('/upload-payment', [TransactionController::class, 'uploadPayment']);
        Route::post('/confirm-payment', [TransactionController::class, 'confirmTransaction']);

        Route::post('/list-payment', [TransactionController::class, 'getListPayment']);

        Route::post('/ticket', [TransactionController::class, 'getMobileTicketHistory']);
        Route::post('/ticket-by-id', [TransactionController::class, 'getMobileTicketById']);
        Route::post('/history', [TransactionController::class, 'getMobileHistory']);

        Route::get('/auto-cancel', [TransactionController::class, 'autoCancelOrder']);
    });

    Route::group(['prefix' => 'jury'], function () {
        Route::post('/check-data', [JugdingController::class, 'checkData']);
        Route::post('/set-koncer', [JugdingController::class, 'updateDataContestantKoncer']);
        Route::post('/update-data', [JugdingController::class, 'updateDataContestant']);
    });

    Route::group(['prefix' => 'analyzer'], function () {
        Route::post('/get', [AnalyzerController::class, 'analyzer']);
    });
});
