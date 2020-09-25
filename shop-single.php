<?
  require_once('includes/header.php');

  //Grab value from query string and send val to DB
  if(isset($_GET['id']) && !empty($_GET['id']))
  {
    $queryID = htmlspecialchars($_GET['id']);
  }
  //Instantiate product class object
  $singleProd = new Product();
  $singleProd->querySingleItem($queryID);
  //Get images for item we're loading


  //Store item details so we don't have to keep calling class getters
  $description = $singleProd->getDescript();
  $price = $singleProd->getPrice();
  $manufacturer = $singleProd->getManufacture();
  $name = $singleProd->getName();
  $model = $singleProd->getModel();
  $images = $singleProd->getImages();
  //Get number of images
  $imageNum = count($images);
?>

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">
            <?
            //Going to come back and update this so it'll show what category/ subcat etc.
            $output = "";
            $output .= $manufacturer . ": " . $name;
            echo $output;
            ?>
          </strong></div>
        </div>
      </div>
    </div>  

    <div class="site-section">
      <div class="container">
        <div class="row">
          <!-- Image carousel will go here for all product images -->
          <div class="col-md-6">
            <div id="productCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
              <div class="carousel-inner">
                  <?
                  //Logic to populate carousel or no
                    
                      for($i = 0; $i <= $imageNum - 1; $i++)
                      {
                        $image = $images[$i];
                        if($i == 0)
                        {
                          //Set a unique ID for jquery
                          $carouselID = $queryID;
                          echo "<div class='carousel-item active' id='" . $carouselID . "'>
                          <img class='d-block w-100' src='" . $image . "' alt='" . $name . " image'>
                          </div>";
                        }
                        else
                        {
                          //Increment the ID for each successive image
                          $carouselID++;
                          echo "<div class='carousel-item' id='" . $carouselID . "'>
                          <img class='d-block w-100' src='" . $image . "' alt='" . $name . " image'>
                          </div>";
                        }
                      }
                      //If more than one image exists, populate carousel controls
                      if($imageNum > 1)
                      {
                        echo "</div>
                              <a class='carousel-control-prev' href='#productCarousel' role='button' data-slide='prev'>
                                <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                                <span class='sr-only'>Previous</span>
                              </a>
                              <a class='carousel-control-next' href='#productCarousel' role='button' data-slide='next'>
                                <span class='carousel-control-next-icon' aria-hidden='true'></span>
                                <span class='sr-only'>Next</span>
                              </a>
                            </div>
                          </div>";
                      }
                      else
                      {
                        echo "</div>
                        </div>
                      </div>";
                      }
                      
                  ?>
              
          <div class="col-md-6">
            <h2 class="text-black">
              <? 
              $output = "";
              $output .= $name . " - " . $model;
              echo $output;
              ?>
              </h2>
            <p class="mb-4">
              <?
                $output = "";
                $output .= $description;
                echo $output;
              ?>
            </p>
            <p><strong class="text-primary h4">$<? echo $price; ?></strong></p>
            <div class="mb-1 d-flex">
              <label for="option-sm" class="d-flex mr-3 mb-3">
                <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio" id="option-sm" name="shop-sizes"></span> <span class="d-inline-block text-black">Small</span>
              </label>
              <label for="option-md" class="d-flex mr-3 mb-3">
                <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio" id="option-md" name="shop-sizes"></span> <span class="d-inline-block text-black">Medium</span>
              </label>
              <label for="option-lg" class="d-flex mr-3 mb-3">
                <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio" id="option-lg" name="shop-sizes"></span> <span class="d-inline-block text-black">Large</span>
              </label>
              <label for="option-xl" class="d-flex mr-3 mb-3">
                <span class="d-inline-block mr-2" style="top:-2px; position: relative;"><input type="radio" id="option-xl" name="shop-sizes"></span> <span class="d-inline-block text-black"> Extra Large</span>
              </label>
            </div>
            <div class="mb-5">
              <div class="input-group mb-3" style="max-width: 120px;">
              <div class="input-group-prepend">
                <button class="btn btn-outline-primary js-btn-minus" type="button">&minus;</button>
              </div>
              <input type="text" class="form-control text-center" value="1" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
              <div class="input-group-append">
                <button class="btn btn-outline-primary js-btn-plus" type="button">&plus;</button>
              </div>
            </div>

            </div>
            <p><a href="cart.php" class="buy-now btn btn-sm btn-primary">Add To Cart</a></p>

          </div>
        </div>
      </div>
    </div>

    <div class="site-section block-3 site-blocks-2 bg-light">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7 site-section-heading text-center pt-4">
            <h2>Featured Products</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="nonloop-block-3 owl-carousel">
              <div class="item">
                <div class="block-4 text-center">
                  <figure class="block-4-image">
                    <img src="images/cloth_1.jpg" alt="Image placeholder" class="img-fluid">
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a href="#">Tank Top</a></h3>
                    <p class="mb-0">Finding perfect t-shirt</p>
                    <p class="text-primary font-weight-bold">$50</p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="block-4 text-center">
                  <figure class="block-4-image">
                    <img src="images/shoe_1.jpg" alt="Image placeholder" class="img-fluid">
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a href="#">Corater</a></h3>
                    <p class="mb-0">Finding perfect products</p>
                    <p class="text-primary font-weight-bold">$50</p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="block-4 text-center">
                  <figure class="block-4-image">
                    <img src="images/cloth_2.jpg" alt="Image placeholder" class="img-fluid">
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a href="#">Polo Shirt</a></h3>
                    <p class="mb-0">Finding perfect products</p>
                    <p class="text-primary font-weight-bold">$50</p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="block-4 text-center">
                  <figure class="block-4-image">
                    <img src="images/cloth_3.jpg" alt="Image placeholder" class="img-fluid">
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a href="#">T-Shirt Mockup</a></h3>
                    <p class="mb-0">Finding perfect products</p>
                    <p class="text-primary font-weight-bold">$50</p>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="block-4 text-center">
                  <figure class="block-4-image">
                    <img src="images/shoe_1.jpg" alt="Image placeholder" class="img-fluid">
                  </figure>
                  <div class="block-4-text p-4">
                    <h3><a href="#">Corater</a></h3>
                    <p class="mb-0">Finding perfect products</p>
                    <p class="text-primary font-weight-bold">$50</p>
                  </div>
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
    
  </body>
</html>