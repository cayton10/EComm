<?
/**
 * THIS CLASS WIL POPULATE ALL PRODUCT INFORMATION AND IMAGES
 * TO THE SHOP.PHP PAGE
 */
class Product
{
    //Database private member variable
    private $database;

    //Construct instance of DB
    public function __construct()
    {
        $this->database = DB::getInstance();
    }

    public function getProducts()
    {
        //Declare output variable to return with formatted product display
        $output = "";

        //Define query to pull product information
        $query = "SELECT pro_ID, pro_Name, pro_Descript, pro_Price, pro_Manufacturer FROM product";

        //Store results for processing
        $results = $this->database->get_results($query);
        //If results are returned
        if($results)
        {   //Iterate through results and format product display
            foreach($results as $row)
            {

              
              //Define image variable to process images of different extension types
              $image = "/products/" . $row[pro_ID] . "_1";
              $docRoot = $_SERVER['DOCUMENT_ROOT'];
              $image = $docRoot . $image;
              echo $image;
              $imageDir = dirname(__DIR__, 3) . $image;
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
                $printImage = "NoImage";
              $output .= "<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up'>
                <div class='block-4 text-center border innerProdContainer'>
                  <figure class='block-4-image'>
                    <a href='shop-single.php'><img src='../../../products/" . $printImage . "' alt='Image placeholder' class='img-fluid prods'></a>
                  </figure>
                  <div class='block-4-text p-4'>
                    <h3><a href='shop-single.html'>" . $row['pro_Manufacturer'] . "</a></h3>
                    <p class='mb-0'>" . $row['pro_Name'] . "</p>
                    <p class='text-primary font-weight-bold'>" . '$' . $row['pro_Price'] . "</p>
                  </div>
                </div>
              </div>";
            }
            return $output;
        }

    }
}
?>