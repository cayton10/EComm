<?
//DB Connection config file
  require_once('config/config.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>MyVarietyStore.com</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">


    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/custom.css">
    
  </head>
  <body>
  
  <div class="site-wrap">
    <header class="site-navbar" role="banner">
      <div class="site-navbar-top">
        <div class="container">
          <div class="row align-items-center">
        <!-- SEARCH BAR -->
            <div class="col-6 col-md-4 order-2 order-md-1 site-search-icon text-left">
              <form action="" class="site-block-top-search">
                <span class="icon icon-search2"></span>
                <input id='search' type="text" class="form-control border-0" placeholder="Search">
                <div id='searchResults'></div>
              </form>
            </div>
        <!-- SITE LOGO -->
            <div class="col-12 mb-3 mb-md-0 col-md-4 order-1 order-md-2 text-center">
              <div class="site-logo">
                <a href="index.php" class="js-logo-clone">my variety store</a>
              </div>
            </div>

        <!-- NAVIGATION AND ICONS -->

            <div class="col-6 col-md-4 order-3 order-md-3 text-right">
              <div class="site-top-icons">
                <ul>
                  <li><a href="account"><span class="icon icon-person"></span></a></li>
                  <li>
                      <!-- MINI CART UTILITY -->
                    <a href="cart.php" class="site-cart">
                      <span class="icon icon-shopping_cart"></span>
                      <span id="miniCartCount" class="count">2</span>
                    </a>
                  </li> 
                  <li class="d-inline-block d-md-none ml-md-0"><a href="#" class="site-menu-toggle js-menu-toggle"><span class="icon-menu"></span></a></li>
                </ul>
              </div> 
            </div>

          </div>
        </div>
      </div> 
      <nav class="site-navigation text-right text-md-center" role="navigation">
        <div class="container">
          <ul class="site-menu js-clone-nav d-none d-md-block">
            <li class="has-children active">
              <a href="shop.php?page=1">Browse Inventory</a>
            </li>
            <li>
                <a href="featured.php">Featured Products</a>
            </li>
            <li>
                <a href="cart.php">Cart</a>
            </li>
            <li>
                <a href="checkout.php">Checkout</a>
            </li>
            <li>
                <a href="account.php">Account</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>