<footer class='site-footer border-top'>
    <div class='container'>
      <div class='row'>
        <div class='col-lg-6 mb-5 mb-lg-0'>
          <div class='row'>
            <div class='col-md-12'>
              <h3 class='footer-heading mb-4'>Navigation</h3>
            </div>
            <div class='col-md-6 col-lg-4'>
              <ul class='list-unstyled'>
                <li><a href='shop.php'>Browse Inventory</a></li>
                <li><a href='cart.php'>Cart</a></li>
              </ul>
            </div>
            <div class='col-md-6 col-lg-4'>
              <ul class='list-unstyled'>
                <li><a href='checkout.php'>Checkout</a></li>
                <li><a href='account.php'>Account</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class='col-md-6 col-lg-3 mb-4 mb-lg-0'>
          <h3 class='footer-heading mb-2'>Promotion</h3>
          <?
            //Create instance of product class
            $promo = new Product();

            $promoInfo = $promo->getPromoItem();

            foreach($promoInfo as $item)
            {
              $output = '';
              //Assign our needed vars
              $prodID = $item['id'];
              $title = $item['title'];
              //Use static image class method to grab image of promo item
              $image = Image::getImage($prodID);

              $output .= "<a href='shop-single.php?id=" . $prodID . "&name=" . $ittle . "' class='block-6'>
                            <img id='promoImage' src='" . $image . "' alt='Image placeholder' class='img-fluid rounded mb-2'>
                            <h3 class='font-weight-light  mb-0'>" . $title . "</h3>
                          </a>";

              echo $output;
            }

          ?>
        </div>
        <div class='col-md-6 col-lg-3'>
          <div class='block-5 mb-5'>
            <h3 class='footer-heading mb-4'>Contact Info</h3>
            <ul class='list-unstyled'>
              <li class='address'>203 Fake St. Mountain View, San Francisco, California, USA</li>
              <li class='email'>admin@myvarietystore.com</li>
            </ul>
          </div>

        </div>
      </div>
      <div class='row pt-5 mt-5 text-center'>
        <div class='col-md-12'>
          <p>
          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
          All rights reserved | This template is made with <i class='icon-heart' aria-hidden='true'></i> by <a href='https://colorlib.com' target='_blank' class='text-primary'>Colorlib</a>
          <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</br>
          Modified by BPC &copy; <script>document.write(new Date().getFullYear());</script>
          </p>
        </div>
        
      </div>
    </div>
  </footer>
</div>
<!-- ajax Google api script inclusion -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src='js/jquery-3.3.1.min.js'></script>
<script src='js/jquery-ui.js'></script>
<script src='js/popper.min.js'></script>
<script src='js/bootstrap.min.js'></script>
<script src='js/owl.carousel.min.js'></script>
<script src='js/jquery.magnific-popup.min.js'></script>
<script src='js/aos.js'></script>

<script src='js/main.js'></script>
<!-- STARRR PLUGIN REQUIRED JS -->
<script src='plugins/rating/starrr.js'></script>
<!-- FANCYBOX CDN -->
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

<script src='js/custom.js'></script>