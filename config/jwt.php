<?php

return [
    'jwt_token' => env('JWT_TOKEN', base64_encode(\Illuminate\Support\Str::random())),
];
