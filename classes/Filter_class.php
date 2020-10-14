<?

/* -------------------------------------------------------------------------- */
/*                  FILTER CLASS FILTERS PRODUCTS IN SHOP.PHP                 */
/* -------------------------------------------------------------------------- */

class Filter extends DB
{

    public function construct()
    {

        parent::__constuct();
    }


    //Get all prices for all products browsing page
    public function getAllPrices()
    {
        $query = "SELECT MAX(pro_Price) AS maxPrice, MIN(pro_Price) AS minPrice
                    FROM product";
        
        $prices = $this->get_results($query);

        return $prices;
    }


    //Get max and min prices for main categories on browsing page
    public function getMainPrices($category)
    {
        $query = "SELECT MAX(pro_Price) AS maxPrice, MIN(pro_Price) AS minPrice
                    FROM product t1
                    LEFT JOIN category t2 ON t1.cat_ID = t2.cat_ID
                    WHERE t2.cat_SubCat = $category";

        $prices = $this->get_results($query);

        return $prices;
    }

    //Get max and min prices for sub categories on browsing page
    public function getSubPrices($category)
    {
        $query = "SELECT MAX(pro_Price) AS maxPrice, MIN(pro_Price) AS minPrice
                    FROM product
                    WHERE cat_ID = $category";

        $prices = $this->get_results($query);

        return $prices;
    }

/* -------------------------------------------------------------------------- */
/*                FUNCTIONS TO GET MANUFACTURERS FOR FILTERING                */
/* -------------------------------------------------------------------------- */

    public function getAllManu()
    {
        $query = "SELECT pro_Manufacturer manu
                    FROM product";

        $results = $this->get_results($query);

        return $results;
    }

    public function getMainManu($category)
    {
        $query = "SELECT pro_Manufacturer manu
                    FROM product t1
                    LEFT JOIN category t2 ON t1.cat_ID = t2.cat_ID
                    WHERE manu = $title AND t2.cat_SubCat = $category";

        $results = $this->get_results($query);

        return $results;
    }

    public function getSubManu($category)
    {
        $query = "SELECT pro_Manufacturer manu
                    FROM product
                    WHERE cat_ID = $category";
    }


}


?>