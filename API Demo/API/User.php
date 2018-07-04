<?php
class User {

    // Connection instance
    private $connection;

    // table name
    private $table_name = "User";

    // table columns
    public $Id;
    public $FirstName;
    public $LastName;
    public $Email;
    public $DOB;
    public $Gender;

    public function __construct($connection){
        $this->connection = $connection;
    }

    //C
    public function create() {

    }
    //R
    public function read($id = "0") {
        if ($id == "0") {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY DOB";
        }
        else {
            $query = "SELECT * FROM " . $this->table_name . " WHERE Id = $id";
        }
        $stmt = $this->connection->prepare($query);

        $stmt->execute();

        return $stmt;
    }
    //U
    public function update(){}
    //D
    public function delete(){}
}