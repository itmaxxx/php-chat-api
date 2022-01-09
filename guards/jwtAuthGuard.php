<?php
  
  class JwtAuthGuard
  {
    private $usersService;
    
    public function __construct($usersService)
    {
      $this->usersService = $usersService;
    }
    
    public function canActivate(Request $req): bool
    {
      $bearer = $req->getRequest()["headers"]["Authorization"];
  
      $isValidBearer = strpos($bearer, "Bearer ");
      
      if (!$isValidBearer && $isValidBearer !== 0) {
        return false;
      }
  
      // 7 - is "Bearer " string length
      $jwt = substr($bearer, 7);
      
      if (strlen($jwt) <= 0) {
        return false;
      }
  
      $decodedJwt = [];
  
      try {
        $decodedJwt = jwtDecode($jwt);
      } catch (Exception $exception) {
        return false;
      }
  
      # Get user from db
      $user = $this->usersService->getUserById($decodedJwt->id);
      
      $req->setUser($user);
      
      return true;
    }
  }