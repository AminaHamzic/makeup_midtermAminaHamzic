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
        
        $host = 'db-mysql-nyc1-13993-do-user-3246313-0.b.db.ondigitalocean.com';
        $username = 'doadmin';
        $password = 'AVNS_z6PG_c6BSn-5dB0CG5S';
        $port = '25060';
        $schema = 'final-midterm2-2023';


        /*options array neccessary to enable ssl mode - do not change*/
        $options = array(
        	PDO::MYSQL_ATTR_SSL_CA => 'https://drive.google.com/file/d/1zqyqk92mI4A4cAW43nhnCWxEveGSkY7k/view?usp=sharing',
        	PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,

        );

        /** TODO
        * Create new connection
        * Use $options array as last parameter to new PDO call after the password
        */

        $this->conn = new PDO("mysql:host=$host;port=$port;dbname=$schema", $username, $password, $options);

        
        // set the PDO error mode to exception
          $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          echo "Connected successfully";
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }

}
?>
