<?php
  
  class MessagesService
  {
    private PDO $conn;
  
    function __construct($conn)
    {
      $this->conn = $conn;
    }
  
    function createMessage($id, $chatId, $userId, $content, $contentType)
    {
      $sql = "INSERT INTO Messages (id, chatId, userId, content, contentType, createdAt) VALUES (:id, :chatId, :userId, :content, :contentType, :createdAt)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id", $id);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->bindValue(":userId", $userId);
      $stmt->bindValue(":content", $content);
      $stmt->bindValue(":contentType", $contentType);
      $stmt->bindValue(":createdAt", time());
      $stmt->execute();
    }
  
    function getChatMessages($chatId): array
    {
      $sql = "SELECT * FROM Messages WHERE chatId=:chatId";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->execute();
      
      if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        return [];
      }
    }
  }