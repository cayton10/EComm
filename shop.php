<?
require_once('includes/header.php');
//Set limit for number of products to return as well as pagination results
$limit = 9;
//Instantiate our required object for pagination
//Doubles as error handling for bogus query strings(kind of)
$pagination = new Paginate();
//Set total pages required for pagination
$pagination->setTotalPages($limit);
//Return that number and store it in a variable
$totalPages = $pagination->getTotalPages();


/* -------------------- CHECK AGAINST BOGUS QUERY STRING -------------------- */
if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] <= $totalPages && $_GET['page'] > 0)
{
  $pageNumber = htmlspecialchars($_GET['page']);
  $pagination->setCurrentPage($pageNumber);
}
else if (!isset($_GET['page']))//If page isn't a key in the query string...
{
  //Set a boolean to populate pagination if we're shopping all products
  $dontPopulate = true;
}
else
{  
  $pagination->setCurrentPage(1);
  $pageNumber = 1;
}
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
            <div class="row mb-5">
              <!-- Create instance of product class to populate product thumbnails and info -->
              <?
                
                $prods = new Product();
                //store query string value
/* ------------- CHECK IF QUERY STRING CONTAINS PRODUCT ID INFO ------------- */
                if(isset($_GET['category']) && !empty($_GET['category']))
                {
                  $value = htmlspecialchars($_GET['category']);
                  echo $prods->getSubProducts($value);
                }
                else if(isset($_GET['MainCat']) && !empty($_GET['MainCat']))
                {
                  $value = htmlspecialchars($_GET['MainCat']);
                  echo $prods->getMainProducts($value);
                }
                else if(isset($_GET['page']) && !empty($_GET['page']))
                {
                  echo $prods->getAllProducts($pageNumber, $limit);
                }
                else
                  echo $prods->getAllProducts($limit);
              ?>
            </div>
<!-- PAGINATION CLASS METHOD CALLS GO HERE -->
            <?
            if(!$dontPopulate)
            {
              echo $pagination->printPagination();
            }
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
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Filter by Price</h3>
                <div id="slider-range" class="border-primary"></div>
                <input type="text" name="text" id="amount" class="form-control border-0 pl-0 bg-white" disabled="" />
              </div>

              <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Size</h3>
                <label for="s_sm" class="d-flex">
                  <input type="checkbox" id="s_sm" class="mr-2 mt-1"> <span class="text-black">Small (2,319)</span>
                </label>
                <label for="s_md" class="d-flex">
                  <input type="checkbox" id="s_md" class="mr-2 mt-1"> <span class="text-black">Medium (1,282)</span>
                </label>
                <label for="s_lg" class="d-flex">
                  <input type="checkbox" id="s_lg" class="mr-2 mt-1"> <span class="text-black">Large (1,392)</span>
                </label>
              </div>

              <div class="mb-4">
                <h3 class="mb-3 h6 text-uppercase text-black d-block">Color</h3>
                <a href="#" class="d-flex color-item align-items-center" >
                  <span class="bg-danger color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Red (2,429)</span>
                </a>
                <a href="#" class="d-flex color-item align-items-center" >
                  <span class="bg-success color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Green (2,298)</span>
                </a>
                <a href="#" class="d-flex color-item align-items-center" >
                  <span class="bg-info color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Blue (1,075)</span>
                </a>
                <a href="#" class="d-flex color-item align-items-center" >
                  <span class="bg-primary color d-inline-block rounded-circle mr-2"></span> <span class="text-black">Purple (1,075)</span>
                </a>
              </div>

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
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
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
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
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
                  <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
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