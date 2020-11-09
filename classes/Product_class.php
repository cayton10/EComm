<?

/* -------------------------------------------------------------------------- */
/*                  CLASS WILL POPULATE PRODUCTS ON PAGE LOAD  
                    MUTLIPLE PUBLIC FUNCTIONS FOR DIFFERENT CASE
                    SCENARIOS                                                 */
/* -------------------------------------------------------------------------- */

class Product
{
    //Database private member variable
    private $database;
    //Limit and page for pagination
    private $limit;
    private $page;
    private $start;

    //Private members for item details page
    private $id;
    private $description;
    private $price;
    private $manufacturer;
    private $name;
    private $model;
    private $qty;

/* ------------------------ CONSTRUCT INSTANCE OF DB ------------------------ */
    public function __construct()
    {
        $this->database = DB::getInstance();
    }

/* -------------------------------------------------------------------------- */
/*         QUERY SINGLE ITEM AND STORE RESULTS IN PRIVATE MEMBER VARS         */
/* -------------------------------------------------------------------------- */

    public function querySingleItem($productID)
    {
      //Query the item passed from query string
      $query = "SELECT * 
                FROM product
                WHERE pro_ID = $productID";
      //Store results for processing
      $results = $this->database->get_results($query);

      //If results returned, make appropriate member var assignments
      if($results)
      {
        $item = $results[0];
        $this->id = $item['pro_ID'];
        $this->description = $item['pro_Descript'];
        $this->price = $item['pro_Price'];
        $this->manufacturer = $item['pro_Manufacturer'];
        $this->name = $item['pro_Name'];
        $this->model = $item['pro_Model'];
        $this->qty = $item['pro_Qty'];
      }
      else//Need to ask Brian about errno() and error handling using this DB class
          //Or just test and find out myself :) 
        echo "Error";
    }

/* --------------------- GETTERS FOR SINGLE ITEM DETAILS -------------------- */
    public function getProdID()
    {
      return $this->id;
    }

    public function getDescript()
    {
      return $this->description;
    }

    public function getPrice()
    {
      return $this->price;
    }

    public function getManufacture()
    {
      return $this->manufacturer;
    }

    public function getName()
    {
      return $this->name;
    }

    public function getModel()
    {
      return $this->model;
    }

    public function getQty()
    {
      return $this->qty;
    }

    public function getImages()
    {
      //Thanks, Brian :)
      $images = glob("../../products/" . $this->id . "*.jpg");
      return $images;
    }


    //Function definition w/ default parameter
    public function getAllProducts($limit, $page)
    {
        $start = (($page - 1) * $limit);


        //Store the current page from query string
          //Define query to pull product information
          $query = "SELECT t1.pro_ID ID, 
                         pro_Name title, 
                         pro_Price price, 
                         pro_Manufacturer manu,
                         AVG(rev_Score) AS avgScore 
                  FROM product t1
                  LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                  GROUP BY t1.pro_ID
                  LIMIT $start, $limit";

        //Store results for processing
        $results = $this->database->get_results($query);

        //Return our results from DB as array
        return $results;
        //If results are returned
    }

    public function getMainProducts($limit, $category, $page)
    {

      //Query to return all products from main category
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
                  WHERE t2.cat_SubCat = $category
                  GROUP BY t1.pro_ID";

      //Store results for processing
      $results = $this->database->get_results($query);
      return $results;
    }

    public function getSubProducts($limit, $category, $page)
    {


        $query = "SELECT t1.pro_ID ID,
                         pro_Name title,
                         pro_Price price,
                         pro_Manufacturer manu,
                         AVG(rev_Score) AS avgScore
                  FROM product t1
                  LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                  WHERE cat_ID = $category
                  GROUP BY t1.pro_ID";

      //Store results for processing
      $results = $this->database->get_results($query);
      return $results;
    }

    //Public function to check the quantity of items remaining 
    //For specific product ID in product table
    //Made to use in addToCart.php
    public function getQuantity($prodID)
    {

        $query = "SELECT pro_Qty
                  FROM product
                  WHERE pro_ID = '" . $prodID ."'";

        $results = $this->database->get_results($query);
        return $results;
    }


/* -------------------------------------------------------------------------- */
/*                     FUNCTION TO GET PRODUCT OPTIONS                   */
/* -------------------------------------------------------------------------- */

  public function getProductOptionGroups()
  {

    //Query the prodopt table in the database to return all product options associated
    //with this productID
    $query = "SELECT DISTINCT opt_Group, opt_Name
              FROM prodopt
              WHERE pro_ID = '" . $this->id . "'";
    
    //Lick stamp and send it
    $results = $this->database->get_results($query);
    return $results;
  }

  //Uses option group number to bring back an array of all options under those constraints
  public function getProductOptions($value)
  {

    $query = "SELECT opt_Value, opt_Price
              FROM prodopt
              WHERE pro_ID = '" . $this->id . "' AND opt_Group = '" . $value . "'";

    $results = $this->database->get_results($query);
    return $results;
  }

/* -------------------------------------------------------------------------- */
/*                       PROMOTION QUERY AND INFO RETURN                      */
/* -------------------------------------------------------------------------- */

  public function getPromoItem()
  {
    $query = "SELECT pro_ID id, pro_Name title
              FROM product
              WHERE pro_Qty = (SELECT MAX(pro_Qty)
                      FROM product)
              LIMIT 1";

    $results = $this->database->get_results($query);
    return $results;
  }
}


?>
