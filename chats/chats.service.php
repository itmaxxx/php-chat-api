<?php
  
  class ChatsService
  {
    private $conn;
    
    function __construct($conn)
    {
      $this->conn = $conn;
    }

    function getChats(): array
    {
      $sql = "SELECT * FROM Chats";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        return [];
      }
    }

    function createChat($id, $name, $isPrivate, $inviteLink)
    {
      $sql = "INSERT INTO Chats (id, name, isPrivate, inviteLink) VALUES (:id, :name, :isPrivate, :inviteLink)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id", $id);
      $stmt->bindValue(":name", $name);
      $stmt->bindValue(":isPrivate", $isPrivate);
      $stmt->bindValue(":inviteLink", $inviteLink);
      $stmt->execute();
    }
    
    function findById($id)
    {
      $sql = "SELECT * FROM Chats WHERE id=:id AND isDeleted=FALSE";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":id", $id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
        return null;
      }
    }
    
    function isUserChatParticipant($userId, $chatId): bool
    {
      $sql = "SELECT * FROM ChatParticipants WHERE userId=:userId AND chatId=:chatId";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":userId", $userId);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->execute();
  
      return $stmt->rowCount() > 0;
    }
  
    function getChatParticipantByUserId($userId, $chatId)
    {
      $sql = "SELECT * FROM ChatParticipants WHERE userId=:userId AND chatId=:chatId";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":userId", $userId);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->execute();
  
      if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
      } else {
        return null;
      }
    }
  
    function createChatRO($chat)
    {
      $chatRO = $chat;
    
      unset($chatRO["isPrivate"]);
      unset($chatRO["inviteLink"]);
    
      return $chatRO;
    }
    
    function getUserChats($userId): array
    {
      $sql = "SELECT C.id AS id, C.image AS image, C.name AS name FROM Chats AS C, ChatParticipants AS CP WHERE CP.userId=:userId AND CP.chatId=C.id AND C.isDeleted=FALSE";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":userId", $userId);
      $stmt->execute();
      
      if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
        return [];
      }
    }
    
    function addParticipantToChat($userId, $chatId, $permission = 0)
    {
      $sql = "INSERT INTO ChatParticipants (userId, chatId, permission) VALUES (:userId, :chatId, :permission)";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":userId", $userId);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->bindValue(":permission", $permission);
      $stmt->execute();
    }
    
    function deleteChatById($chatId)
    {
      $sql = "UPDATE Chats SET isDeleted=TRUE WHERE id=:chatId";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindValue(":chatId", $chatId);
      $stmt->execute();
    }
  }
