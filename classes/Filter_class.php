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


/* -------------------------------------------------------------------------- */
/*                  QUERIES AND RETURNS FOR PRICE INFORMATION                 */
/* -------------------------------------------------------------------------- */

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
        $query = "SELECT DISTINCT pro_Manufacturer manu
                    FROM product
                    ORDER BY manu";

        $results = $this->get_results($query);

        return $results;
    }

    public function getMainManu($category)
    {
        $query = "SELECT DISTINCT pro_Manufacturer manu
                    FROM product t1
                    LEFT JOIN category t2 ON t1.cat_ID = t2.cat_ID
                    WHERE t2.cat_SubCat = $category
                    ORDER BY manu";

        $results = $this->get_results($query);

        return $results;
    }

    public function getSubManu($category)
    {
        $query = "SELECT DISTINCT pro_Manufacturer manu
                    FROM product
                    WHERE cat_ID = $category
                    ORDER BY manu";

        $results = $this->get_results($query);

        return $results;
    }





/* -------------------------------------------------------------------------- */
/*              QUERIES FOR AJAX CALLS FROM ajax/filterPrices.php             */
/* -------------------------------------------------------------------------- */

    public function getProductsInRange($min, $max, $value, $type, $manu)
    {
        $query = '';
        //Control flow for sentinel type
        if($type === 'all' && $manu !== 'all')
        {
            $query = "SELECT t1.pro_ID ID, 
                                pro_Name title, 
                                pro_Price price, 
                                pro_Manufacturer manu,
                                AVG(rev_Score) AS avgScore 
                        FROM product t1
                        LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                        WHERE pro_Price >= $min AND pro_Price <= $max AND pro_Manufacturer = '" . $manu . "'
                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";
                        
            $results = $this->get_results($query);

            return $results;
        }
        else if($type === 'category' && $manu !== 'all')
        {
            $query = "SELECT t1.pro_ID ID,
                                pro_Name title,
                                pro_Price price,
                                pro_Manufacturer manu,
                                AVG(rev_Score) AS avgScore
                        FROM product t1
                        LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                        WHERE cat_ID = $value AND pro_Price >= $min AND pro_Price <= $max AND pro_Manufacturer = '". $manu ."'
                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";

            $results = $this->get_results($query);

            return $results;
        }
        else if($type === 'MainCat' && $manu !== 'all')
        {

            $query = "SELECT
                            t1.pro_ID ID,
                            pro_Name title,
                            pro_Price price,
                            pro_Manufacturer manu,
                            AVG(rev_Score) AS avgScore
                        FROM
                            product t1
                        LEFT JOIN category t2
                        ON t1.cat_ID = t2.cat_ID
                        LEFT JOIN review t3
                        ON t1.pro_ID = t3.pro_ID
                        WHERE t2.cat_SubCat = $value AND pro_Price >= $min AND pro_Price <= $max AND pro_Manufacturer = '" . $manu ."'                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";

            $results = $this->get_results($query);

            return $results;
        }
        else if($manu !== 'all')
        {

            $query = "SELECT t1.pro_ID ID, 
                                pro_Name title, 
                                pro_Price price, 
                                pro_Manufacturer manu,
                                AVG(rev_Score) AS avgScore 
                        FROM product t1
                        LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                        WHERE pro_Price >= $min AND pro_Price <= $max AND pro_Manufacturer = '" . $manu . "'
                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";
                        
            $results = $this->get_results($query);

            return $results;
        }
        else if($type === 'all')
        {
            $query = "SELECT t1.pro_ID ID, 
                                pro_Name title, 
                                pro_Price price, 
                                pro_Manufacturer manu,
                                AVG(rev_Score) AS avgScore 
                        FROM product t1
                        LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                        WHERE pro_Price >= $min AND pro_Price <= $max
                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";
                        
            $results = $this->get_results($query);

            return $results;
        }
        else if($type === 'category')
        {
            $query = "SELECT t1.pro_ID ID,
                                pro_Name title,
                                pro_Price price,
                                pro_Manufacturer manu,
                                AVG(rev_Score) AS avgScore
                        FROM product t1
                        LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                        WHERE cat_ID = $value AND pro_Price >= $min AND pro_Price <= $max
                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";

            $results = $this->get_results($query);

            return $results;
        }
        else if($type === 'MainCat')
        {
            $query = "SELECT
                            t1.pro_ID ID,
                            pro_Name title,
                            pro_Price price,
                            pro_Manufacturer manu,
                            AVG(rev_Score) AS avgScore
                        FROM
                            product t1
                        LEFT JOIN category t2
                        ON t1.cat_ID = t2.cat_ID
                        LEFT JOIN review t3
                        ON t1.pro_ID = t3.pro_ID
                        WHERE t2.cat_SubCat = $value AND pro_Price >= $min AND pro_Price <= $max
                        GROUP BY t1.pro_ID
                        ORDER BY manu, price";

            $results = $this->get_results($query);

            return $results;
        }
        

    }


/* -------------------------------------------------------------------------- */
/*               QUERIES FOR AJAX CALLS FROM ajax/filterManu.php              */
/* -------------------------------------------------------------------------- */

    public function getProductsFromManu($manu, $max, $min)
    {
        //Set up query
        $query = "SELECT
                        t1.pro_ID ID,
                        pro_Name title,
                        pro_Price price,
                        pro_Manufacturer manu,
                        AVG(rev_Score) AS avgScore
                    FROM
                        product t1
                    LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                    WHERE pro_Price >= $min AND pro_Price <= $max AND pro_Manufacturer = '".$manu."'
                    GROUP BY t1.pro_ID
                    ORDER BY manu, price";

        $results = $this->get_results($query);

        return $results;

    }

    public function getAllProductsFromManu($max, $min)
    {
        //Set up query
        $query = "SELECT
                        t1.pro_ID ID,
                        pro_Name title,
                        pro_Price price,
                        pro_Manufacturer manu,
                        AVG(rev_Score) AS avgScore
                    FROM
                        product t1
                    LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                    WHERE pro_Price >= $min AND pro_Price <= $max
                    GROUP BY t1.pro_ID
                    ORDER BY manu, price";

        $results = $this->get_results($query);

        return $results;
    }

}


?>