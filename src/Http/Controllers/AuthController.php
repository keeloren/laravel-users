<?php

namespace NguyenND\Users\Http\Controllers;

use NguyenND\Users\Http\Controllers\BaseController;
use NguyenND\Users\Repositories\Contracts\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NguyenND\Users\Http\Requests\UserCreateRequest;
use NguyenND\Users\Models\User;
use NguyenND\Users\Traits\ResponseTrait;
use Hash;
use Mail;

class AuthController extends BaseController
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
     * authenticated
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function authenticated(Request $request)
    {
        $user = $this->userRepository->find(Auth::id());
        return $this->success($user, trans('lang::messages.common.getInfoSuccess'));
    }

    /**
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserCreateRequest $request)
    {
        $credentials = $request->all();
        $credentials['password'] = bcrypt($credentials['password']);
        $user = $this->userRepository->skipPresenter()->create($credentials);
        return $this->success([], trans('lang::messages.auth.registerSuccess'));
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
