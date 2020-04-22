<?php

namespace NguyenND\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NguyenND\Users\Http\Requests\UserCreateRequest;
use NguyenND\Users\Models\User;
use NguyenND\Users\Traits\ResponseTrait;

class AuthController extends Controller
{
    use ResponseTrait;
    
    
    /**
     * authenticated
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticated(Request $request)
    {
        $user = User::find(Auth::id());
        return $this->success($user->toArray(), trans('lang::messages.common.getInfoSuccess'), ['isContainByDataString' => true]);
    }

    /**
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserCreateRequest $request)
    {
        $credentials = $request->all();
        $credentials['password'] = bcrypt($credentials['password']);
        $user = User::create($credentials);
        $objToken = $user->createToken('name');

        // return token
        $dataToken = [
            'type'       => 'Token',
            'attributes' => [
                'access_token' => $objToken->accessToken,
                'token_type'   => 'Bearer',
                'expires_in'   => Carbon::parse($objToken->token->expires_at)->toDateTimeString()
            ]
        ];
        return $this->success($dataToken, trans('lang::messages.auth.registerSuccess'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->success([], trans('lang::messages.auth.logoutSuccess'), ['isShowData' => false]);
    }
}
