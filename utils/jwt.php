<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = "jwt_secret_key";

function jwtEncode($payload) {
  $jwt = JWT::encode($payload, $key, 'HS256');
  return $jwt;
}

function jwtDecode($jwt) {
  $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
  print_r($decoded);
  return $decoded;
}

