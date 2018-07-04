<?php
require_once("Rest.php");
require_once("User.php");

class API extends REST {
    public $data = "";
    //Enter details of your database
    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DB = "Demo";
     
    private $db = NULL;
 
    public function __construct() {
        parent::__construct();              // Init parent contructor
        $this->dbConnect();                 // Initiate Database connection
    }
     
    private function dbConnect() {
        try {
                $this->db = new PDO("mysql:host=" . self::DB_SERVER . ";dbname=" . self::DB, self::DB_USER, self::DB_PASSWORD);
                $this->db->exec("set names utf8");
            } catch(PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }
    }
     
    /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */
    public function processApi() {
        $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
        if((int)method_exists($this,$func) > 0)
            $this->$func();
        else
            $this->response("",404);   // If the method not exist with in this class, response would be "Page not found".
    }    
    
    Private function hello() {
        echo $this->_request['id'];
        $this->response(json_encode(array("Message" => "Hello")), 200,"Success.");
    }

    private function getUsers() {    
    // Cross validation if the request method is Post else it will return "Not Acceptable" status
        if($this->get_request_method() != "POST") {
            $this->response('',406);
        }

        $myDatabase= $this->db;// variable to access your database
        $param = "0";
        if (isset($this->_request['id'])) {
            $param = $this->_request['id'];
        }
        // initialize object
        $user = new User($myDatabase);
        
        // query products
        $stmt = $user->read($param);
        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if ($param != "0" && $num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            extract($row);

            $user= array("data" => array(
                "id" => $Id,
                "firstName" => $First_Name,
                "lastName" => $Last_Name,
                "email" => $Email,
                "dob" => $DOB,
                "gender" => $Gender,
            ));

            $this->response(json_encode($user), 200);
        }
        else if($num>0) {
            // products array
            $user_arr=array();
            $user_arr["data"]=array();
        
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                $user=array(
                    "id" => $Id,
                    "firstName" => $First_Name,
                    "lastName" => $Last_Name,
                    "email" => $Email,
                    "dob" => $DOB,
                    "gender" => $Gender,
                );
        
                array_push($user_arr["data"], $user);
            }
            $this->response(json_encode($user_arr), 200);
        }
        else {
                $this->response('', 404,"No user found.");
        }
    }
}
 
// Initiiate Library
$api = new API;
$api->processApi();

?>