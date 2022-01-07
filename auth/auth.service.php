<?php

class AuthService {
  private $conn;

  function __construct($conn) {
    $this->conn = $conn;
  }
}
