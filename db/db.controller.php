<?php
  
  @include_once("./fixtures/users.php");
  
  class DbController
  {
    private $conn;
    
    public function __construct($dbConfig)
    {
      $this->connectToDb($dbConfig);
      
      $this->drop(['Users']);
      
      $this->initialize();
      
      # Include $usersFixtures from global scope here
      global $usersFixtures;
      $this->seed('Users', ["id", "username", "password"], $usersFixtures);
    }
    
    public function getConnection()
    {
      return $this->conn;
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
     *  Seeds data from array in specified table and columns
     * @param {string}   $table    Table name where we want to seed data
     * @param {string[]} $columns  Array of fields names we want to insert
     * @param {object[]} $fixtures Array of fixtures with fields from $fields in same order
     */
    public function seed(string $table, array $columns, array $fixtures)
    {
      try {
        $columnsString = implode(",", $columns);
        
        foreach ($fixtures as $fixture) {
          $fixtureKeys = array_keys($fixture);
          $fixtureKeysString = ":" . implode(", :", $fixtureKeys);
          
          $sql = "INSERT INTO $table ($columnsString) VALUES ($fixtureKeysString)";
          
          $this->conn->prepare($sql)->execute($fixture);
        }
      } catch (Exception $ex) {
        httpException("Failed to seed db, table '$table'", 500)['end']();
      }
    }
    
    /**
     *  Drop specified table in DB
     * @param {string[]} $tables Array of tables names
     */
    private function drop($tables)
    {
      try {
        foreach ($tables as $table) {
          $sql = "DROP TABLE $table";
          $this->conn->exec($sql);
        }
      } catch (Exception $ex) {
        httpException("Failed to drop db", 500);
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
          private BOOLEAN NOT NULL DEFAULT TRUE,
          inviteLink VARCHAR(30) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS ChatParticipants (
          chatFk BIGINT NOT NULL,
          userFk BIGINT NOT NULL,
          permission TINYINT NOT NULL DEFAULT 0,
          FOREIGN KEY (chatFk) REFERENCES Chats (id),
          FOREIGN KEY (userFk) REFERENCES Users (id)
        );
        
        ALTER TABLE ChatParticipants DROP CONSTRAINT unique_chat_user;
        ALTER TABLE ChatParticipants ADD UNIQUE unique_chat_user(chatFk, userFk);
      SQL;
        $this->conn->exec($users);
      } catch (Exception $ex) {
        httpException("Failed to initialize db", 500);
      }
    }
  }
