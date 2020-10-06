<?

/* -------------------------------------------------------------------------- */
/*              CLASS ACTS TO SEARCH DATABASE BASED ON USER INPUT             */
/* -------------------------------------------------------------------------- */

class Search extends DB
{
    //Construct the parent Search class to get an instance of DB
    public function __construct()
    {
        parent::__construct();
    }

    public function searchProdName($p_name)
    {
        //Set up query to search both product name and manufacturer fields
        $query = "SELECT pro_Name Title, pro_Manufacturer Manu
                  FROM product
                  WHERE 
                    CONCAT(pro_Name, pro_Manufacturer) LIKE '%$p_name%'
                    ORDER BY pro_Manufacturer";
        //Lick stamp... send
        $results = $this->get_results($query);

        //Return for processing
        return $results;
    }
}
?>