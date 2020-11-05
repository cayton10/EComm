<?php 
    require_once('includes/header.php');
    //$database = new DB();
?>

    <div class="site-blocks-cover" style="background-image: url(images/hero_bike.jpg);" data-aos="fade">
      <div class="container">
        <div class="row align-items-start align-items-md-center justify-content-start">
          <div id='heroMessage' class="col-md-5 text-center text-md-left pt-5 pt-md-0">
            <h1 class="mb-2">Finding Your Perfect Ride</h1>
            <div class="intro-text text-center text-md-left">
              <p class="mb-4">Whether you're taking to the trails or hitting the road. We've got you covered.</p>
              <p>
                <a href="shop.php?page=1&MainCat=100&name=Bicycles" class="btn btn-sm btn-primary">Shop Now</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="site-section site-blocks-2">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-md-6 col-lg-4 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
            <a class="block-2-item" href="shop.php?page=1&MainCat=106&name=Tech">
              <figure class="image">
                <img src="images/tech_category.jpeg" alt="" class="img-fluid">
              </figure>
              <div class="text">
                <span class="text-uppercase">Collections</span>
                <h3>Tech</h3>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="100">
            <a class="block-2-item" href="shop.php?page=1&MainCat=103&name=Glassware">
              <figure class="image">
                <img src="images/glassware_category.jpg" alt="" class="img-fluid">
              </figure>
              <div class="text">
                <span class="text-uppercase">Collections</span>
                <h3>Glassware</h3>
              </div>
            </a>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0" data-aos="fade" data-aos-delay="200">
            <a class="block-2-item" href="shop.php?page=1&MainCat=100&name=Bicycles">
              <figure class="image">
                <img src="images/bike_category.jpg" alt="" class="img-fluid">
              </figure>
              <div class="text">
                <span class="text-uppercase">Collections</span>
                <h3>Bicycles</h3>
              </div>
            </a>
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
              <?
                //Instantiate featured product object
                $featured = new Featured();
                //Store results of method call into 'featuredProds
                $featuredProds = $featured->getFeaturedProds();
                //Process results and ouput product cards
                foreach ($featuredProds as $feat)
                {
                  //Process ID, grab appropriate image
                  $image = Image::getImage($feat['ID']);


                  echo "<div class='item prodContainer'>
                          <div class='block-4 text-center innerProdContainer'>
                            <figure class='block-4-image'>                        
                              <img src='" . $image . "' alt='Image placeholder' class='img-fluid featuredProds'>
                            </figure>
                            <div class='block-4-text p-4 prodInfo'>
                              <h3><a href='shop-single.php?id=" . $feat['ID'] . "&name=" . $feat['Title'] . "'>" . $feat['Manu'] . "</a></h3>
                              <p class='mb-0'>" . $feat['Title'] . "</p>
                              <p class='text-primary font-weight-bold'> $" . number_format($feat['Price'], 2) . "</p>
                            </div>
                            <div class='avgRating'>" . Review::staticAvgRating($feat['avgScore']) . "</div>
                          </div>
                        </div>";
                }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
    //Footer inclusion for site wide template  
      require_once('includes/footer.php'); 
    ?> 
  </body>
</html>