<?php

namespace NguyenND\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use NguyenND\Users\Repositories\Contracts\UserRepository;
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
        $user = $this->userRepository->skipPresenter()->create($credentials);
        $objToken = $user->createToken(config('constants.API.PERSONAL_ACCESS_CLIENT_NAME'));
        // return token
        $dataToken = [
            'type'       => 'Token',
            'attributes' => [
                'access_token' => $objToken->accessToken,
                'token_type'   => config('constants.TOKEN.TYPE'),
                'expires_in'   => Carbon::parse($objToken->token->expires_at)->toDateTimeString()
            ]
        ];
        return $this->success($dataToken, trans('lang::messages.auth.registerSuccess'));
    }
    
    public function issueToken(ServerRequestInterface $request)
    {
        try {
            $username = $request->getParsedBody()['username'];
            $user = User::where('email', '=', $username)->firstOrFail();
            //generate token
            $tokenResponse = parent::issueToken($request);
            //convert response to json string
            $content = $tokenResponse->getContent();
            $data = json_decode($content, true);
            if (isset($data['error'])) {
                throw new OAuthServerException('The user credentials were incorrect.', 6, 'invalid_credentials', 401);
            }
            return Response::json(collect($data));
        } catch (ModelNotFoundException $e) {
            // email notfound
            if ($e instanceof ModelNotFoundException) {
//                return response(['error' => 'Invalid_credentials', 'message' => 'User does not exist. Please try again'], 404);
                return $this->error('Invalid_credentials', 'User does not exist. Please try again', 401);
            }
        } catch (OAuthServerException $e) {
            //password not correct..token not granted
            return $this->error('Invalid_credentials', 'Password is not correct', 401);
        } catch (Exception $e) {
            return response(['error' => 'unsupported_grant_type', 'message' => 'The authorization grant type is not supported by the authorization server.', 'hint' => 'Check that all required parameters have been provided'], 400);
        }
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
