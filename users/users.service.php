<?php
  
  class UsersService
  {
    private PDO $conn;
    
    function __construct($conn)
    {
      $this->conn = $conn;
    }
    
    function getUsers(): array
    {
      $sql = "SELECT * FROM Users";
      $result = $this->conn->query($sql);
      
      $users = [];
      
      while ($user = $result->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $user;
      }
      
      return $users;
    }
    
    function createUserRO($user)
    {
      $userRO = $user;
      
      unset($userRO["password"]);
      
      return $userRO;
    }
    
    function getUserById($id)
    {
      $sql = "SELECT * FROM Users WHERE id=:userid";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":userid", $id);
      $stmt->execute();
      
      if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
        return null;
      }
    }
    
    function getUserByUsername($username)
    {
      $sql = "SELECT * FROM Users WHERE username=:username";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":username", $username);
      $stmt->execute();
      
      if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
        return null;
      }
    }
    
    function createUser($id, $username, $password)
    {
      $sql = "INSERT INTO Users (id, username, password) VALUES (:id, :username, :password)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id", $id);
      $stmt->bindValue(":username", $username);
      $stmt->bindValue(":password", $password);
      $stmt->execute();
    }
  }
