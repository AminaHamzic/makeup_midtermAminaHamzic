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
    public function investor($first_name, $last_name, $email, $company, $share_class_id, $share_class_category_id, $diluted_shares){
        //if email is unique
        $query="SELECT * FROM investors WHERE email=:email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->rowCount()>0){
            // Email already exists, return error message
            return (['message' => 'Email address already exists']);
        }
        // Check if the sum of diluted shares exceeds the authorized assets
        $query="SELECT SUM(diluted_shares) as total_shares FROM cap_table WHERE share_class_id= :share_class_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $share_class_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $query = "SELECT authorized_assets FROM share_classes WHERE id = :share_class_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $share_class_id);
        $stmt->execute();
        $authorized_assets = $stmt->fetchColumn();

        if($result['total_shares'] + $diluted_shares > $authorized_assets) {
            // The sum of diluted shares exceeds the authorized assets, return error message
            return (['message' => 'Sum of diluted shares exceeds authorized assets for this share class']);
        }

        // If all checks pass, insert the new investor
        $query = "INSERT INTO investors (first_name, last_name, email, company) VALUES (:first_name, :last_name, :email, :company)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':company', $company);
        $stmt->execute();

        $investor_id = $this->conn->lastInsertId();

        // Insert the new cap table record
        $query = "INSERT INTO cap_table (share_class_id, share_class_category_id, investor_id, diluted_shares) VALUES (:share_class_id, :share_class_category_id, :investor_id, :diluted_shares)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':share_class_id', $share_class_id);
        $stmt->bindParam(':share_class_category_id', $share_class_category_id);
        $stmt->bindParam(':investor_id', $investor_id);
        $stmt->bindParam(':diluted_shares', $diluted_shares);
        $stmt->execute();

        // Return success message
        return (['message' => 'Investor has been created successfully']);
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

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return (['message' => 'Invalid email format']);
        }
        $query= "SELECT * FROM investors WHERE email=:email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $response = [
                'message' => 'Investor ' . $result['first_name'] . ' ' . $result['last_name'] . ' uses this email address'
            ];
        } else {
            $response = [
                'message' => 'Investor with this email does not exists in database'];
        }

        return ($response);
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

    public function investors($id)
    {
        $query = "select sc.description, sc.equity_main_currency, sc.price, sc.authorized_assets, i.first_name, i.last_name, i.email, i.company, 
        sum(ct.diluted_shares) as total_diluted_shares
        from investors i
        join cap_table ct on i.id = ct.investor_id
        join share_classes sc on ct.share_class_id = sc.id
        where sc.id = :id
        group by i.id;";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>
