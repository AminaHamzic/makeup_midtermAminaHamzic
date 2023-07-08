<?php

class BaseDao {

    public $conn;

    /**
    * constructor of dao class
    */
    public function __construct(){
        try {

        /** TODO
        * List parameters such as servername, username, password, schema. Make sure to use appropriate port
        */
        
        $host = '127.0.0.1';
        $username = 'root';
        $password = '';
        $port = '3306';
        $schema = 'final-midterm2-2023';


        /*options array neccessary to enable ssl mode - do not change
        $options = array(
        	PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1zqyqk92mI4A4cAW43nhnCWxEveGSkY7k/view?usp=sharing',
        	PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,

        );*/

        /** TODO
        * Create new connection
        * Use $options array as last parameter to new PDO call after the password
        */

        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$schema", $username, $password);

        
        // set the PDO error mode to exception
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

}
?>
