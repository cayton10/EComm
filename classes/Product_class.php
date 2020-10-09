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
    //Limit for pagination
    private $limit;
    //Private members for item details page
    private $id;
    private $description;
    private $price;
    private $manufacturer;
    private $name;
    private $model;

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
        $this->price = number_format($item['pro_Price'],2);
        $this->manufacturer = $item['pro_Manufacturer'];
        $this->name = $item['pro_Name'];
        $this->model = $item['pro_Model'];
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

    public function getImages()
    {
      //Thanks, Brian :)
      $images = glob("../../products/" . $this->id . "*.jpg");
      return $images;
    }



    //Function definition w/ default parameter
    public function getAllProducts($pageNumber = 1, $limit)
    {
        //Declare output variable to return with formatted product display
        $output = "";
        $this->limit = $limit;
        $definedLimit = $this->limit;
        $start = ($pageNumber - 1) * $limit;
        //Store the current page from query string
          //Define query to pull product information
          $query = "SELECT t1.pro_ID, 
                         pro_Name, 
                         pro_Price, 
                         pro_Manufacturer,
                         AVG(rev_Score) AS avgScore 
                  FROM product t1
                  LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                  GROUP BY t1.pro_ID
                  LIMIT $start, $definedLimit";

        //Store results for processing
        $results = $this->database->get_results($query);

        //If results are returned
        if($results)
        { 

          //Iterate through results and format product display
            foreach($results as $row)
            {

/* -------------------- GRABS IMAGES OF COMMON EXT TYPES -------------------- */
              $image = "/products/" . $row['pro_ID'] . "_1";
              $docRoot = $_SERVER['DOCUMENT_ROOT'] . "/CIT410";
              $image = $docRoot . $image;
              if(file_exists($image . ".png"))
              {
                $printImage = $row['pro_ID'] . "_1.png";
              }
              else if(file_exists($image . ".jpg"))
              {
                $printImage = $row['pro_ID'] . "_1.jpg";
              }
              else if(file_exists($image . ".gif"))
              {
                $printImage = $row['pro_ID'] . "_1.gif";
              }
              else
                $printImage = "noimage.jpg";
                
              $output .= "<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up'>
                <div class='block-4 text-center border innerProdContainer'>
                  <figure class='block-4-image'>
                    <a href='shop-single.php?id=" . $row['pro_ID'] . "&name=" . $row['pro_Name'] . "'><img src='../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                  </figure>
                  <div class='block-4-text p-4 prodInfo'>
                    <h3><a href='shop-single.php?id=" . $row['pro_ID'] . "&name=" . $row['pro_Name'] . "'>" . $row['pro_Manufacturer'] . "</a></h3>
                    <p class='mb-0'>" . $row['pro_Name'] . "</p>
                    <p class='text-primary font-weight-bold'>" . '$' . number_format($row['pro_Price'], 2) . "</p>
                  </div>
                  <div class='avgRating'>" . Review::staticAvgRating($row['pro_ID']) . "</div>
                </div>
              </div>";
            }
            return $output;
        }

    }

    public function getMainProducts($category)
    {
      //Declare output variable to return with formatted product display
      $output = "";
      //Query to return all products from main category
        $query = "SELECT
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

      //Store results for processing
      $results = $this->database->get_results($query);
      //If results are returned
      if($results)
      {   //Iterate through results and format product display
          foreach($results as $row)
          {

/* -------------------- GRABS IMAGES OF COMMON EXT TYPES -------------------- */

            $image = "/products/" . $row['pro_ID'] . "_1";
            $docRoot = $_SERVER['DOCUMENT_ROOT'] . "/CIT410";
            $image = $docRoot . $image;
            if(file_exists($image . ".png"))
            {
              $printImage = $row['pro_ID'] . "_1.png";
            }
            else if(file_exists($image . ".jpg"))
            {
              $printImage = $row['pro_ID'] . "_1.jpg";
            }
            else if(file_exists($image . ".gif"))
            {
              $printImage = $row['pro_ID'] . "_1.gif";
            }
            else
              $printImage = "noimage.jpg";
              
            $output .= "<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up'>
              <div class='block-4 text-center border innerProdContainer'>
                <figure class='block-4-image'>
                <a href='shop-single.php?id=" . $row['pro_ID'] . "&name=" . $row['pro_Name'] . "'><img src='../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                </figure>
                <div class='block-4-text p-4 prodInfo'>
                  <h3><a href='shop-single.php?id=" . $row['pro_ID'] . "&name=" . $row['pro_Name'] . "'>" . $row['pro_Manufacturer'] . "</a></h3>
                  <p class='mb-0'>" . $row['pro_Name'] . "</p>
                  <p class='text-primary font-weight-bold'>" . '$' . number_format($row['pro_Price'], 2) . "</p>
                </div>
                <div class='avgRating'>" . Review::staticAvgRating($row['avgScore']) . "</div>
              </div>
            </div>";
          }
          return $output;
      }
    }

    public function getSubProducts($category)
    {
      //Declare output variable to return with formatted product display
      $output = "";

        $query = "SELECT t1.pro_ID,
                         pro_Name,
                         pro_Price,
                         pro_Manufacturer,
                         AVG(rev_Score) AS avgScore
                  FROM product t1
                  LEFT JOIN review t2 ON t1.pro_ID = t2.pro_ID
                  WHERE cat_ID = $category
                  GROUP BY t1.pro_ID";

      //Store results for processing
      $results = $this->database->get_results($query);
      //If results are returned
      if($results)
      {   //Iterate through results and format product display
          foreach($results as $row)
          {

/* -------------------- GRABS IMAGES OF COMMON EXT TYPES -------------------- */

            $image = "/products/" . $row['pro_ID'] . "_1";
            $docRoot = $_SERVER['DOCUMENT_ROOT'] . "/CIT410";
            $image = $docRoot . $image;
            if(file_exists($image . ".png"))
            {
              $printImage = $row['pro_ID'] . "_1.png";
            }
            else if(file_exists($image . ".jpg"))
            {
              $printImage = $row['pro_ID'] . "_1.jpg";
            }
            else if(file_exists($image . ".gif"))
            {
              $printImage = $row['pro_ID'] . "_1.gif";
            }
            else
              $printImage = "noimage.jpg";
              
            $output .= "<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up'>
              <div class='block-4 text-center border innerProdContainer'>
                <figure class='block-4-image'>
                <a href='shop-single.php?id=" . $row['pro_ID'] . "&name=" . $row['pro_Name'] . "'><img src='../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                </figure>
                <div class='block-4-text p-4 prodInfo'>
                  <h3><a href='shop-single.php?id=" . $row['pro_ID'] . "&name=" . $row['pro_Name'] . "'>" . $row['pro_Manufacturer'] . "</a></h3>
                  <p class='mb-0'>" . $row['pro_Name'] . "</p>
                  <p class='text-primary font-weight-bold'>" . '$' . number_format($row['pro_Price'], 2) . "</p>
                </div>
                <div class='avgRating'>" . Review::staticAvgRating($row['avgScore']) . "</div>
              </div>
            </div>";
          }
          return $output;
      }
    }



}
?>
