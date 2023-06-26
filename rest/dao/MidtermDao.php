<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor(){

    }

    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
    public function investor_email($email){
        

    }

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */
    /** TODO
    * This endpoint is used to list all investors from give share_class
    * (meaning all investors occuring in cap table with given share_class_id)
    * It should return share class description, equiy main currency, price and authorized_assets,
    * investor first and last name, email, company and total diluted assets within cap table
    * Sample data within tables and expected output with given data is provided in figures 3, 4, 5 and 6
    * Output is given in figure 6
    * This endpoint should return output in JSON format
    */
    public function investors(){
        


    }

}
?>
