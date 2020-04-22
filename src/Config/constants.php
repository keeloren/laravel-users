<?php
return [
    'GENDER_VALUES' => [
        'COLLECTIONS' => [1, 2],
        'MALE'        => 1,
        'FEMALE'      => 2
    ],
    'TOKEN' => [
        'TOKEN_EXPIRE_IN'         => 15,
        'REFRESH_TOKEN_EXPIRE_IN' => 30,
        'TOKEN_VERIFY_LENGTH'     => 50,
        'REMEMBER_TOKEN_LENGTH'   => 10,
        'TYPE'                    => 'Bearer',
    ],
    'HTTP_STATUS_CODE' => [
        'NOT_FOUND'            => 404,
        'BAD_REQUEST'          => 400,
        'SERVER_ERROR'         => 500,
        'METHOD_NOT_ALLOWED'   => 405,
        'UNAUTHORIZED'         => 401,
        'PERMISSION_DENIED'    => 403,
        'UNPROCESSABLE_ENTITY' => 422,
        'NOT_ACCEPTABLE'       => 406,
        'SUCCESS'              => 200,
    ],
    'PASSWORD' => [
        'MIN_LENGTH' => 8,
    ],
    'PASSPORT_CLIENT_ID' => 2
];
