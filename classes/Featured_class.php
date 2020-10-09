<?

class Featured extends DB
{

    //Construct the parent Product class
    public function __construct()
    {
        parent::__construct();
    }

    //Query the database for all products containing the featured boolean value
    public function getFeaturedProds()
    {
        $results = $this->get_results("SELECT t1.pro_ID ID, pro_Name Title, pro_Manufacturer Manu, pro_Price Price, AVG(rev_Score) AS avgScore
                                       FROM product t1
                                       LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                                       WHERE pro_Feat = 'Y'
                                       GROUP BY t1.pro_ID");
        return $results;
    }
}