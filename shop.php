<?
require_once('includes/header.php');

//Instantiate objects of our required classes
$product = new Product();
$pagination = new Paginate();
//Set up count variable to store how many results we returned
$count;

/* ------------- CHECK IF QUERY STRING CONTAINS PRODUCT ID INFO ------------- */
if(isset($_GET['category']) && !empty($_GET['category']))
{
  $value = htmlspecialchars($_GET['category']);
  $products = $product->getSubProducts($value);
  $count = count($products);
}
else if(isset($_GET['MainCat']) && !empty($_GET['MainCat']))
{
  $value = htmlspecialchars($_GET['MainCat']);
  $products = $product->getMainProducts($value);
  $count = count($products);
}
else
{
  $products = $product->getAllProducts();
  $count = count($products);
}


/* ----------------- CHECK QUERY STRING FOR APPROPRIATE PAGE ---------------- */

if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] > 0)
{
  $pageNumber = htmlspecialchars($_GET['page']);
  $pagination->setCurrentPage($pageNumber);
}
else
{  
  $pagination->setCurrentPage(1);
  $pageNumber = 1;
}

$pageCount = ceil($count / 9);
$currentPage = $pagination->getCurrentPage();
echo $currentPage;


//Set total pages required for pagination
//$pagination->setTotalPages($limit);
//Return that number and store it in a variable
//$totalPages = $pagination->getTotalPages();



?>

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Shop</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">

        <div class="row mb-5">
          <div class="col-md-9 order-2">

            <div class="row">
              <div class="col-md-12 mb-5">
                <div class="float-md-left mb-4"><h2 class="text-black h5">Shop All 
                  <?
/* -------------- USE QUERY STRING TO UPDATE SHOPPING TEXT INFO ------------- */
                    if(isset($_GET['name']))
                    {
                      $text = htmlspecialchars($_GET['name']);
                      echo $text;
                    }
                    else
                      echo "Products";
                  ?>
                </h2></div>
              </div>
            </div>
            <div class="row mb-5" id='cardContainer'>
              <!-- Create instance of product class to populate product thumbnails and info -->
              <?
                
/* --------------- PROCESS RESULTANT PRODUCT OBJECT AND OUTPUT -------------- */
                foreach($products as $prod)
                {
                  //Process product ID and grab appropriate image
                  $image = Image::getImage($prod['ID']);

                  //Output our product cards
                  echo "<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up' data-manu=". $prod['manu'] . ">
                          <div class='block-4 text-center border innerProdContainer'>
                              <figure class='block-4-image'>
                                <a href='shop-single.php?id=" . $prod['ID'] . "&name=" . $prod['title'] . "'><img src='" . $image . "' alt='Image placeholder' class='img-fluid prods'></a>
                              </figure>
                            <div class='block-4-text p-4 prodInfo'>
                              <h3><a href='shop-single.php?id=" . $prod['ID'] . "&name=" . $prod['title'] . "'>" . $prod['manu'] . "</a></h3>
                              <p class='mb-0'>" . $prod['title'] . "</p>
                              <p class='text-primary font-weight-bold'>" . '$' . number_format($prod['price'], 2) . "</p>
                            </div>
                            <div class='avgRating'>" . Review::staticAvgRating($prod['avgScore']) . "</div>
                          </div>
                      </div>";
                }

              ?>
            </div>
<!-- PAGINATION CLASS METHOD CALLS GO HERE -->
            <?
              /*
              TODO: Eliminate this check and set pagination for all products/ main cats/ subcats
              TODO: This will invlove switching up the 'page' key on page load for product cards. Fix that in Product_class.php functions
              */
            
            
              //echo $pagination->printPagination();
            
            ?>
          </div>

          <div class="col-md-3 order-1 mb-5 mb-md-0">
            <div class="border p-4 rounded mb-4">
              <h3 class="mb-3 h6 text-uppercase text-black d-block">Categories</h3>
              <ul class="list-unstyled mb-0">

<!-- ------------- INSTANTIATE AND LOAD ALL CATEGORIES BY DEFAULT ------------->
                    <?
                      $cat = new Category();
                      echo $cat->getCat();
                      ?>
                  
              </ul>
            </div>

            <div class="border p-4 rounded mb-4">
              <div class="mb-4">
              <?

/* ---------------- CREATE INSTANCE OF FILTER CLASS FOR PRICE --------------- */
                $prices = new Filter();
                $filterValue;
                $filterType;
                //Control logic to populate appropriate results
                if(isset($_GET['category']) && !empty($_GET['category']))
                {
                  $filterValue = htmlspecialchars(trim($_GET['category']));
                  $priceRange = $prices->getSubPrices($filterValue);
                  $filterType = 'sub';
                }
                else if(isset($_GET['MainCat']) && !empty($_GET['MainCat']))
                {
                  $filterValue = htmlspecialchars(trim($_GET['MainCat']));
                  $priceRange = $prices->getMainPrices($filterValue);
                  $filterType = 'main';
                }
                else
                {
                  $priceRange = $prices->getAllPrices();
                  //Set a sentinel value for default
                  $filterType = 'all';
                }
                //Output the price slider with appropriate prices
                if(!empty($priceRange))
                {
                  foreach($priceRange as $price)
                  {
                    echo "<h3 class='mb-3 h6 text-uppercase text-black d-block'>Filter by Price</h3>
                    <div id='slider-range' class='border-primary' 
                          data-min='" . $price['minPrice'] . "' 
                          data-max='" . $price['maxPrice'] . "' 
                          data-value='" . $filterValue . "'
                          data-type='" . $filterType . "'></div>
                    <input type='text' name='text' id='amount' class='form-control border-0 pl-0 bg-white' disabled='' />";
                  }
                }
                else
                {
                  echo "Can't connect to databse";
                }

              ?>
                
              </div>

              <div class="mb-4 manufactDiv">
              <?

/* ------------ CREATE INSTANCE OF FILTER CLASS FOR MANUFACTURER ------------ */

                $manufac = new Filter();
                $filterValue;
                $filterType;
                //Control logic to populate appropriate results
                if(isset($_GET['category']) && !empty($_GET['category']))
                {
                  $value = htmlspecialchars(trim($_GET['category']));
                  $manuFilter = $manufac->getSubManu($value);
                  $filterType = 'sub';
                }
                else if(isset($_GET['MainCat']) && !empty($_GET['MainCat']))
                {
                  $value = htmlspecialchars(trim($_GET['MainCat']));
                  $manuFilter = $manufac->getMainManu($value);
                  $filterType = 'main';
                }
                else
                {
                  $manuFilter = $manufac->getAllManu();
                  $filterType = 'all';
                }
                
                if(!empty($manuFilter))
                {
                  //Set up the filter header
                  echo "<h3 id='manufactFilter' class='mb-3 h6 text-uppercase text-black d-block' 
                          data-type='" . $filterType . "'
                          data-value='" . $filterValue . "'>Manufacturer</h3>";

                  //Access our results array and process
                  foreach($manuFilter as $manu)
                  {
                    //Output appropriate manufacturer filter checkboxes
                    echo "<label for='s_sm' class='d-flex manufact'>
                            <input name='manufacturerBox' type='checkbox' class='mr-2 mt-1 manuCheck' value='" . $manu['manu'] . "'> <span class='text-black manCheckBox'>" . $manu['manu'] . "</span>
                          </label>";
                  }
                }
                else
                {
                  "Database connection error";
                }
              
              ?> 

                
              </div>

                <!-- SPACE TO ADD FUTURE FILTERING TOOLS -->

            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="site-section site-blocks-2">
                <div class="row justify-content-center text-center mb-5">
                  <div class="col-md-7 site-section-heading pt-4">
                    <h2>Categories</h2>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0 categoryCard" data-aos="fade" data-aos-delay="">
                    <a class="block-2-item" href="#">
                      <figure class="image">
                        <img src="images/women.jpg" alt="" class="img-fluid">
                      </figure>
                      <div class="text">
                        <span class="text-uppercase">Collections</span>
                        <h3>Women</h3>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0 categoryCard" data-aos="fade" data-aos-delay="100">
                    <a class="block-2-item" href="#">
                      <figure class="image">
                        <img src="images/children.jpg" alt="" class="img-fluid">
                      </figure>
                      <div class="text">
                        <span class="text-uppercase">Collections</span>
                        <h3>Children</h3>
                      </div>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0 categoryCard" data-aos="fade" data-aos-delay="200">
                    <a class="block-2-item" href="#">
                      <figure class="image">
                        <img src="images/men.jpg" alt="" class="img-fluid">
                      </figure>
                      <div class="text">
                        <span class="text-uppercase">Collections</span>
                        <h3>Men</h3>
                      </div>
                    </a>
                  </div>
                </div>
              
            </div>
          </div>
        </div>
        
      </div>
    </div>

<?
require_once('includes/footer.php');
?>
  <div id='categoryTrigger'></div>
  </body>
</html>