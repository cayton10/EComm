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


}


?>
"SELECT
                    t1.pro_ID,
                    pro_Name,
                    pro_Price,
                    pro_Manufacturer,
                    AVG(rev_Score) AS avgScore
                  FROM
                      product t1
                  LEFT JOIN category t2
                  ON t1.cat_ID = t2.cat_ID
                  LEFT JOIN review t3
                  ON t1.pro_ID = t3.pro_ID
                  WHERE t2.cat_SubCat = $category
                  GROUP BY t1.pro_ID";