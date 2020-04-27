<?php

namespace NguyenND\Users\Http\Controllers;

use \Laravel\Passport\Http\Controllers\AccessTokenController as ATController;
use NguyenND\Users\Models\User;
use NguyenND\Users\Traits\ResponseTrait;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Response;

class AccessTokenController extends ATController
{
    use ResponseTrait;

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
                return $this->error('Invalid_credentials', 'User does not exist. Please try again', 401);
            }
        } catch (OAuthServerException $e) {
            //password not correct..token not granted
            return $this->error('Invalid_credentials', 'Password is not correct', 401);
        } catch (Exception $e) {
            return response(['error' => 'unsupported_grant_type', 'message' => 'The authorization grant type is not supported by the authorization server.', 'hint' => 'Check that all required parameters have been provided'], 400);
        }
    }
}
