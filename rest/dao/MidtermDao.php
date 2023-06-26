<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct();
        $conn = $this->conn;
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor(){

    }

    /** TODO
    * Implement DAO method to validate email format and check if email exists
    */
    /** TODO
    * This endpoint is used to check if investor email is in valid format
    * and if it exists in investors table
    * If format is not valid, output should be 'Invalid email format' message
    * If format is valid, return either
    * 'Investor first_name last_name' uses this email address' (replace first_name and last_name with data from database)
    * or 'Investor with this email does not exists in database'
    * Output example is given in figure 2 (message should be updated according to the result)
    * This endpoint should return output in JSON format
    */
    public function investor_email($email){
        $query = "SELECT * FROM investors WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return $data;
        } else {
            return false;
        }
        
        

    }

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors($share_class_id){
        $query="SELECT 'sc.description', 'sc.equity_main_currency', 'sc.price', 'sc.authorized_assets', 'i.first_name', 'i.last_name', 'i.email', 'i.company', SUM('ct.diluted_shares') as total_diluted_shares
        FROM share_classes sc
        JOIN cap_table ct on 'ct.share_class_id' = 'sc.id'
        JOIN investors i on 'i.id' = 'ct.investor_id'
        GROUP BY 'sc.description'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $row) {
        $share_class_id = $row['share_class_id'];
        $category_id = $row['share_class_category_id'];
        $investor_id = $row['investor_id'];

        $result[$share_class_id]['class'] = $row['class_description'];
        $result[$share_class_id]['categories'][$category_id]['category'] = $row['category_description'];
        $result[$share_class_id]['categories'][$category_id]['investors'][] = [
            'investor' => $row['investor_name'],
            'diluted_shares' => $row['diluted_shares']
        ];
        }

        $result = array_values($result);

        return $result;


    }

}
?>
