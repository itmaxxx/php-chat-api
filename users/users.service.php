<?php
  
  class UsersService
  {
    private $conn;
    
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
    
    function createUser($user): bool
    {
      var_dump($user);
      
      // TODO: Maybe use db seed func here?
      
      # $sql = "INSERT INTO Users (name) VALUES ('Max')";
      # $conn->exec($sql);
      
      return true;
    }
  }
