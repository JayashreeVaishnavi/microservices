<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserDetailRequest;
use App\Http\Requests\UpdateUserDetailRequest;
use App\Http\Resources\UserDetailResource;
use App\Models\Account;
use App\Models\UserDetail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        try {
            $limit = request('limit');
            if (!$limit) {
                return UserDetailResource::collection(UserDetail::get());
            }

            return UserDetailResource::collection(UserDetail::paginate($limit));
        } catch (\Throwable $exception) {
            logError($exception, 'Error while loading user details', 'UserDetailController@index');

            return response()->json(['error' => ['common_error' => [['code' => 'INTERNAL_ERROR', 'message' => 'Something went wrong.']]]], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return UserDetailResource
     */
    public function store(StoreUserDetailRequest $request)
    {
        $input = $request->only(UserDetail::REQUEST_ONLY_INPUT);
        $accountInput = [];
        foreach ($request->accounts as $account) {
            $accountInput[] = Arr::only($account, Account::REQUEST_ONLY_INPUT);
        }

        try {
            DB::beginTransaction();
            $detail = UserDetail::create($input);
            $detail->accounts()->createMany($accountInput);
            DB::commit();

            return new UserDetailResource($detail->load('accounts'));
        } catch (\Throwable $exception) {
            DB::rollBack();
            logError($exception, 'Error while storing user details', 'UserDetailController@store', $request->all());

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param UserDetail $detail
     * @return UserDetailResource
     */
    public function show(UserDetail $detail)
    {
        try {
            return new UserDetailResource($detail->load('accounts'));
        } catch (\Throwable $exception) {
            logError($exception, 'Error while displaying user details', 'UserDetailController@show');

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserDetailRequest $request
     * @param UserDetail $detail
     * @return UserDetailResource
     */
    public function update(UpdateUserDetailRequest $request, UserDetail $detail)
    {
        $input = $request->only(UserDetail::REQUEST_ONLY_INPUT);
        $userDetail = $detail;
        try {
            DB::beginTransaction();
            if ($request->accounts) {
                foreach ($request->accounts as $account) {
                    if ($account['id']) {
                        $detail->accounts()->update(Arr::only($account, Account::REQUEST_ONLY_INPUT_FOR_UPDATE));
                    }
                }
            }

            if ($input) {
                $detail->update($input);
            }
            DB::commit();

            return new UserDetailResource($userDetail->fresh('accounts'));
        } catch (\Throwable $exception) {
            DB::rollBack();
            logError($exception, 'Error while updating user details', 'UserDetailController@update', $request->all());

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserDetail $detail
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDetail $detail)
    {
        try {
            DB::beginTransaction();
            $detail->accounts()->delete();
            $detail->delete();
            DB::commit();

            return response()->json(['message' => 'User detail deleted successfully'], STATUS_CODE_SUCCESS);
        } catch (\Throwable $exception) {
            DB::rollBack();
            logError($exception, 'Error while deleting user details', 'UserDetailController@destroy');

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }

    /**
     * List the account details along with users
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function listUserDetailsWithTotal()
    {
        try {
            $limit = request('limit');
            if (!$limit) {
                return UserDetailResource::collection(UserDetail::with('accounts')->get());
            }

            return UserDetailResource::collection(UserDetail::with('accounts')->paginate($limit));
        } catch (\Throwable $exception) {
            logError($exception, 'Error while loading user details', 'UserDetailController@listUserDetailsWithTotal');

            return errorResponse('INTERNAL_ERROR', 'Something went wrong.', STATUS_CODE_INTERNAL_ERROR);
        }
    }
}
