<?

class Featured extends DB
{

    //Construct the parent Product class
    public function __construct()
    {
        parent::__construct();
        //Throw in this if(debug) for good measure
        if(DEBUG)
            echo "Inside of Featured constructor";
    }

    //Query the database for all products containing the featured boolean value
    public function getFeaturedProds()
    {
        $results = $this->get_results("SELECT pro_ID, pro_Name, pro_Manufacturer, pro_Price 
                                       FROM product 
                                       WHERE pro_Feat = 'Y'");
        return $results;
    }
}