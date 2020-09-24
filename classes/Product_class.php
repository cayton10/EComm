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


/* ------------------------ CONSTRUCT INSTANCE OF DB ------------------------ */
    public function __construct()
    {
        $this->database = DB::getInstance();
    }

    public function getResults()
    {
      print_r($results);
    }

    //Function definition w/ default parameter
    public function getAllProducts($pageNumber = 1)
    {
        //Declare output variable to return with formatted product display
        $output = "";
        $limit = 6;
        $start = ($pageNumber - 1) * $limit;
        //Store the current page from query string
          //Define query to pull product information
          $query = "SELECT pro_ID, 
                         pro_Name, 
                         pro_Descript, 
                         pro_Price, 
                         pro_Manufacturer 
                  FROM product
                  LIMIT $start, $limit";

        //Store results for processing
        $results = $this->database->get_results($query);

        //If results are returned
        if($results)
        { 

          //Iterate through results and format product display
            foreach($results as $row)
            {

/* -------------------- GRABS IMAGES OF COMMON EXT TYPES -------------------- */
              $image = "/products/" . $row[pro_ID] . "_1";
              $docRoot = $_SERVER['DOCUMENT_ROOT'];
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
                    <a href='shop-single.php'><img src='../../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                  </figure>
                  <div class='block-4-text p-4 prodInfo'>
                    <h3><a href='shop-single.php'>" . $row['pro_Manufacturer'] . "</a></h3>
                    <p class='mb-0'>" . $row['pro_Name'] . "</p>
                    <p class='text-primary font-weight-bold'>" . '$' . number_format($row['pro_Price'], 2) . "</p>
                  </div>
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
                    pro_ID,
                    pro_Name,
                    pro_Descript,
                    pro_Price,
                    pro_Manufacturer
                  FROM
                      product
                  LEFT JOIN category
                  ON product.cat_ID = category.cat_ID
                  WHERE category.cat_SubCat = $category";

      //Store results for processing
      $results = $this->database->get_results($query);
      //If results are returned
      if($results)
      {   //Iterate through results and format product display
          foreach($results as $row)
          {

/* -------------------- GRABS IMAGES OF COMMON EXT TYPES -------------------- */

            $image = "/products/" . $row[pro_ID] . "_1";
            $docRoot = $_SERVER['DOCUMENT_ROOT'];
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
                  <a href='shop-single.php'><img src='../../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                </figure>
                <div class='block-4-text p-4 prodInfo'>
                  <h3><a href='shop-single.html'>" . $row['pro_Manufacturer'] . "</a></h3>
                  <p class='mb-0'>" . $row['pro_Name'] . "</p>
                  <p class='text-primary font-weight-bold'>" . '$' . number_format($row['pro_Price'], 2) . "</p>
                </div>
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

        $query = "SELECT pro_ID,
                         pro_Name,
                         pro_Descript,
                         pro_Price,
                         pro_Manufacturer
                  FROM product
                  WHERE cat_ID = $category";

      //Store results for processing
      $results = $this->database->get_results($query);
      //If results are returned
      if($results)
      {   //Iterate through results and format product display
          foreach($results as $row)
          {

/* -------------------- GRABS IMAGES OF COMMON EXT TYPES -------------------- */

            $image = "/products/" . $row[pro_ID] . "_1";
            $docRoot = $_SERVER['DOCUMENT_ROOT'];
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
                  <a href='shop-single.php'><img src='../../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                </figure>
                <div class='block-4-text p-4 prodInfo'>
                  <h3><a href='shop-single.html'>" . $row['pro_Manufacturer'] . "</a></h3>
                  <p class='mb-0'>" . $row['pro_Name'] . "</p>
                  <p class='text-primary font-weight-bold'>" . '$' . number_format($row['pro_Price'], 2) . "</p>
                </div>
              </div>
            </div>";
          }
          return $output;
    }
  }
}
?>
