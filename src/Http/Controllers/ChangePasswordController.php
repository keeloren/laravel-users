<?php

namespace NguyenND\Users\Http\Controllers;

use NguyenND\Users\Http\Controllers\BaseController;
use NguyenND\Users\Repositories\Contracts\UserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use NguyenND\Users\Http\Requests\ChangePasswordRequest;
use Hash;

class ChangePasswordController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    
    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Change password
     *
     * @param ChangePasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->error(trans('lang::messages.auth.changePasswordFail'), trans('lang::messages.auth.currentPasswordNotMatch'), config('constants.HTTP_STATUS_CODE.BAD_REQUEST'));
        }
        $this->userRepository->update(['password' => Hash::make($request->password)], Auth::id());
        $user->token()->revoke();
        $objToken          = $user->createToken('name');
        // return new token
        $dataSuccess = [
            'type'       => 'Token',
            'attributes' => [
                'id'           => $objToken->token->id,
                'access_token' => $objToken->accessToken,
                'token_type'   => config('constants.TOKEN.TYPE'),
                'expires_in'   => Carbon::parse($objToken->token->expires_at)->toDateTimeString()
            ]
        ];
        return $this->success($dataSuccess, trans('lang::messages.auth.changePasswordSuccess'));
    }
}
