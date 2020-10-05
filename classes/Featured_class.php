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
        $results = $this->get_results("SELECT pro_ID ID, pro_Name Title, pro_Manufacturer Manu, pro_Price Price 
                                       FROM product 
                                       WHERE pro_Feat = 'Y'");
        return $results;
    }
}