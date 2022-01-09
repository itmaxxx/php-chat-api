<?php
  
  @include_once __DIR__ . "/httpException.php";
  @include_once __DIR__ . "/../locale/en/messages.php";
  
  class Request
  {
    private $contentType, $method, $url, $resource, $headers, $user = null;
    
    public function __construct($req)
    {
      $this->parseRequest($req);
    }
    
    private function parseRequest($req)
    {
      $this->method = $req['REQUEST_METHOD'];
      # Request url with query params
      $this->url = $req['REQUEST_URI'];
      
      # Request url without query params
      # When we have query params in URL they won't be shown here
      $this->resource = '/';
      if (isset($req['REDIRECT_URL'])) {
        $this->resource = $req['REDIRECT_URL'];
      }
      
      if (isset($req['CONTENT_TYPE'])) {
        $this->contentType = strtolower(trim($req['CONTENT_TYPE']));
      }
      
      $this->headers = getallheaders();
    }
    
    public function getRequest(): array
    {
      return [
        "content-type" => $this->contentType,
        "method" => $this->method,
        "url" => $this->url,
        "resource" => $this->resource,
        "headers" => $this->headers,
        "user" => $this->user,
      ];
    }
    
    public function parseBody(): array
    {
      # Parsed body
      $body = [];
      # Parsed json from body
      $data = [];
      
      if ($this->contentType == 'application/json') {
        $body = file_get_contents("php://input");
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
          httpException("Error parsing json")['end']();
        }
      } else if ($this->contentType == 'application/x-www-form-urlencoded') {
        httpException("Form content type not supported yet")['end']();
      } else {
        httpException("Unsupported Content-Type $this->contentType")['end']();
      }
      
      return ["body" => $body, "data" => $data];
    }
    
    public function setUser($user)
    {
      $this->user = $user;
    }
    
    public function useGuard($guard)
    {
      global $messages;
      
      $isAuthorized = $guard->canActivate($this);
      
      if (!$isAuthorized) {
        httpException($messages["not_authenticated"], 401)['end']();
      }
    }
  }
