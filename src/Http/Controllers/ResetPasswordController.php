<?php

namespace NguyenND\Users\Http\Controllers;

use NguyenND\Users\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends BaseController
{
    use ResetsPasswords;

    protected function sendResetResponse($response) {
        return $this->success([], trans('lang::messages.auth.resetPasswordSuccess'), false);
    }

    protected function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ];
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->error(trans('lang::messages.auth.resetPasswordFail'), trans($response), config('constants.HTTP_STATUS_CODE.BAD_REQUEST'));
    }
}
