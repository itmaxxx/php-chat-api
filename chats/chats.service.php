<?php
  
  class ChatsService
  {
    private $conn;
    
    function __construct($conn)
    {
      $this->conn = $conn;
    }
  }
