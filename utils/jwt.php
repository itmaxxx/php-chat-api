<?php
  
  use Firebase\JWT\JWT;
  use Firebase\JWT\Key;
  
  $key = "jwt_secret_key";
  
  function jwtEncode($payload): string
  {
    global $key;
    
    return JWT::encode($payload, $key);
  }
  
  function jwtDecode($jwt): object
  {
    global $key;
    
    $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    print_r($decoded);
    
    return $decoded;
  }

