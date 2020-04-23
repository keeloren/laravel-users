<?php

namespace NguyenND\Users\Http\Controllers;

use NguyenND\Users\Http\Controllers\BaseController;
use NguyenND\Users\Repositories\Contracts\UserRepository;
use NguyenND\Users\Http\Requests\ResetPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use NguyenND\Users\Mail\ResetPassword;
use Mail;

class ForgotPasswordController extends BaseController
{
    use SendsPasswordResetEmails;
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
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getResetToken(ResetPasswordRequest $request)
    {
        $this->userRepository->skipPresenter();
        $email = $request->email;
        $user  = $this->userRepository->findByField('email', $email)->first();
        // create reset password token
        $token = $this->broker()->createToken($user);
        // send mail
        $email        = $user->email;
        $name         = $user->name;
        $domainClient = env('RESET_PASSWORD_URL', 'http://localhost:9001/password/reset');
        $urlClient    = $domainClient . '/' . $token;

        try {
            Mail::to($email, $name)->send(new ResetPassword(compact('name', 'urlClient')));
        } catch (\Exception $e) {
            return $this->error(trans('lang::messages.auth.resetPasswordFail'), $e->getMessage(), config('constants.HTTP_STATUS_CODE.SERVER_ERROR'));
        }
        return $this->success([], trans('lang::messages.auth.sendLinkResetPasswordSuccess'), false);
    }
}
