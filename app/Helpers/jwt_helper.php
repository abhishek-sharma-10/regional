<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function getSignedJWTForUser(string $regNo){
    
    $now = time();  
 
    $payload = array( 
        "iat" => $now, //Time the JWT issued at
        "exp" => ($now + (60*2)), // Expiration time of token
        "sub" => $regNo,
    );
    
    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');
    return $jwt;
}
 

function getSignedRefreshToken(string $regNo){

    $EXPIRATION_TIME_FOR_1_YEAR = ((60 * 60 * 24) * 7);
    $now = time();  
    
    $payload = array( 
        "iat" => $now, //Time the JWT issued at
        "exp" => $now + $EXPIRATION_TIME_FOR_1_YEAR, // Expiration time of token
        "sub" => $regNo,
    );

    $jwt = JWT::encode($payload, Services::getRefreshTokenSecretKey(), 'HS256');
    return $jwt;
}

function verifyRefreshToken(string $token){ 
    try {  
        $decoded = JWT::decode($token, new Key(Services::getRefreshTokenSecretKey(), 'HS256')); 
        return $decoded->sub;
    } catch (\Exception $e) {
        // Invalid token
    }
    return null; 
}


