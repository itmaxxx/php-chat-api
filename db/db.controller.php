<?php
  
  @include_once("./fixtures/users.php");
  @include_once("./fixtures/chats.php");
  @include_once("./fixtures/chatParticipants.php");
  
  class DbController
  {
    private $conn;
    
    public function __construct($dbConfig)
    {
      $this->connectToDb($dbConfig);
      
      // TODO: Don't run this if we are in production mode
      $this->dropTables(['ChatParticipants', 'Users', 'Chats']);
      
      $this->initialize();
      
      # Include fixtures from global scope here
      global $usersFixtures, $chatsFixtures, $chatParticipantsFixtures;
      $this->seed('Users', $usersFixtures);
      $this->seed('Chats', $chatsFixtures);
      $this->seed('ChatParticipants', $chatParticipantsFixtures);
    }
    
    private function connectToDb(array $dbConfig)
    {
      try {
        $this->conn = new PDO(
          "{$dbConfig['type']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}",
          $dbConfig['user'],
          $dbConfig['pass']
        );
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (Exception $ex) {
        httpException($ex->getMessage(), 500)['end']();
      }
    }
    
    /**
     *  Drop specified table in DB
     * @param {string[]} $tables Array of tables names
     */
    private function dropTables($tables)
    {
      try {
        foreach ($tables as $table) {
          $sql = "DROP TABLE $table";
          $this->conn->exec($sql);
        }
      } catch (Exception $ex) {
        httpException("Failed to drop db tables", 500);
      }
    }
    
    /**
     *  Create all tables with hardcoded sql script
     */
    private function initialize()
    {
      try {
        # Create Users table
        $users = <<<SQL
        CREATE TABLE IF NOT EXISTS Users (
          id BIGINT PRIMARY KEY,
          username VARCHAR(20) UNIQUE NOT NULL,
          fullname VARCHAR(30) NULL,
          password VARCHAR(30) NOT NULL,
          profileImage VARCHAR(128) NULL,
          description VARCHAR(256) NULL
        );

        CREATE TABLE IF NOT EXISTS Chats (
          id BIGINT PRIMARY KEY,
          image VARCHAR(128) NULL,
          name VARCHAR(30) NOT NULL,
          isPrivate BOOLEAN NOT NULL DEFAULT TRUE,
          inviteLink VARCHAR(30) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS ChatParticipants (
          chatId BIGINT NOT NULL,
          userId BIGINT NOT NULL,
          permission TINYINT NOT NULL DEFAULT 0,
          FOREIGN KEY (chatId) REFERENCES Chats (id),
          FOREIGN KEY (userId) REFERENCES Users (id)
        );
        
        -- ALTER TABLE ChatParticipants DROP CONSTRAINT unique_chat_user;
        ALTER TABLE ChatParticipants ADD UNIQUE unique_chat_user(chatId, userId);
      SQL;
        $this->conn->exec($users);
      } catch (Exception $ex) {
        httpException("Failed to initialize db", 500);
      }
    }
    
    /**
     *  Seeds data from array in specified table and columns
     * @param {string}   $table    Table name where we want to seed data
     * @param {string[]} $columns  Array of fields names we want to insert
     * @param {object[]} $fixtures Array of fixtures with fields from $fields in same order
     */
    public function seed(string $table, array $fixtures)
    {
      try {
        foreach ($fixtures as $fixture) {
          $fixtureKeys = array_keys($fixture);
          $columnsString = implode(",", $fixtureKeys);
          $fixtureKeysString = ":" . implode(", :", $fixtureKeys);
          
          $sql = "INSERT INTO $table ($columnsString) VALUES ($fixtureKeysString)";
          
          $this->conn->prepare($sql)->execute($fixture);
        }
      } catch (Exception $ex) {
        httpException("Failed to seed db, table '$table'", 500)['end']();
      }
    }
    
    public function getConnection()
    {
      return $this->conn;
    }
  }
