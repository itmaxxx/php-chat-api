<?php
  
  class MessagesService
  {
    private PDO $conn;
  
    function __construct($conn)
    {
      $this->conn = $conn;
    }
  
    function createMessage($id, )
    {
      $sql = "INSERT INTO Chats (id, ) VALUES (:id, )";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id", $id);
      $stmt->execute();
    }
  
    function getChatMessages($chatId)
    {
      $sql = "SELECT * FROM Messages WHERE chatId=:chatId";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->execute();
    
      return $stmt->rowCount() > 0;
    }
  }