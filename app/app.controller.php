<?php
  
  @include_once "./vendor/autoload.php";
  @include_once "./utils/httpException.php";
  @include_once "./utils/request.php";
  @include_once "./users/users.controller.php";
  @include_once "./db/db.controller.php";
  @include_once "./auth/auth.controller.php";
  
  class AppController
  {
    # Connection
    private $conn;
    # Request object
    private $_req;
    # Parsed request
    private $req;
    # Controllers
    private $dbController;
    private $usersController;
    private $authController;
    
    function __construct($dbConfig)
    {
      # Setup headers and db
      $this->setHeaders();
      
      # Setup db
      $this->dbController = new DbController($dbConfig);
      $this->conn = $this->dbController->getConnection();
      
      # Initialize controllers
      $this->usersController = new UsersController($this->conn);
      $this->authController = new AuthController($this->conn);
      
      # Parse request
      $this->_req = new Request($_SERVER);
      $this->req = $this->_req->getRequest();
      
      # Routing
      $this->router();
    }
    
    private function setHeaders()
    {
      header('Content-Type: application/json');
    }
    
    private function router()
    {
      switch ($this->req['method']) {
        case 'GET':
          if ($this->req['resource'] === '/api/users/me') {
            $this->usersController->getMe($this->req['headers']['Authorization']);
            return;
          }
          // /users/:userId
          if (strpos($this->req['resource'], '/api/users/') === 0) {
            $this->usersController->getUserById($this->req);
            return;
          }
          if ($this->req['resource'] === '/api/users') {
            $this->usersController->getUsers();
            return;
          }
          
          httpException("Route not found " . $this->req['resource'], 404)['end']();
          logMessage("Route not found $this->req");
          
          break;
        
        case 'POST':
          $reqBody = $this->_req->parseBody();
          
          if ($this->req['resource'] === '/api/users') {
            $this->usersController->createUser($reqBody["data"]);
            return;
          }
          if ($this->req['resource'] === '/api/auth/sign-up') {
            $this->authController->signUp($reqBody["data"]);
            return;
          }
          if ($this->req['resource'] === '/api/auth/sign-in') {
            $this->authController->signIn($reqBody["data"]);
            return;
          }
          
          httpException("Route not found", 404)['end']();
          
          break;
        
        default:
          httpException("Method not supported", 404)['end']();
          
          break;
      }
    }
  }
