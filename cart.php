<?
  require_once('includes/header.php');

  //Store product information for cart into variables here
  //Create cart instance to get cart info
  $cart = new Cart();
  $cartDetails = $cart->getCartDetail(session_id());
  
?>

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row mb-5">
          <form class="col-md-12" method="post">
            <div class="site-blocks-table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Product</th>
                    <th class="product-price">Price</th>
                    <th class="product-option">Option</th>
                    <th class="product-quantity">Quantity</th>
                    <th class="product-total">Total</th>
                    <th class="product-remove">Remove</th>
                  </tr>
                </thead>
                <tbody>
                <?
                  //Create product object to get product info
                  $productInfo = new Product();
                  foreach($cartDetails as $item)
                  {
                    //Set up our output variable
                    $output = '';

                    //Store our variables from cart details to use in getting things like
                    //Images, prices, names, etc.
                    $prodID = $item['prod'];
                    $quantity = $item['qty'];
                    $options = $item['options'];
                    //Change type
                    $quantity = (float) $quantity;
                    //Get our image
                    $image = Image::getImage($prodID);
                    //Pass product ID to get DB values for this item
                    $productInfo->querySingleItem($prodID);
                    //Get Product name
                    $name = $productInfo->getName();
                    //Get product Price
                    $price = $productInfo->getPrice();
                    //Change type
                    
                    $total = ($quantity * $price);

                    $totalOptionPrice = 0;
                    foreach($options as $option)
                    {
                      $totalOptionPrice += $option['opt_Price'];
                    }

                    $total += $totalOptionPrice;

                    $output = "<tr id='productRow". $prodID ."'>
                                <td class='product-thumbnail'>
                                  <img src='" . $image . "' alt='Image' class='img-fluid'>
                                </td>
                                <td class='product-name'>
                                  <h2 class='h5 text-black'>" . $name . "</h2>
                                </td>
                                <td id='singleItemPrice" . $prodID . "' data-price='" . $price . "'>$" . number_format($price, 2) . "</td>
                                <td id='itemOptions" . $prodID . "' data-price'" . $totalOptionPrice . "' class='itemOptions'>";

                                foreach($options as $option)
                                {
                                  $output .= "<span class='optionName'>" . $option['opt_Name'] . ": " . $option['opt_Value'] . "</span><br>
                                              <span class='optionCharge'>Charge: $</span>
                                              <span class='option" . $prodID . "' data-option='" . $option['opt_ID'] . "'>" . $option['opt_Price'] . "</span><br>";
                                }
                                  
                    $output .= "</td>
                                <td>
                                  <div class='input-group mb-3' style='max-width: 120px;'>
                                    <div class='input-group-prepend'>
                                      <button class='btn btn-outline-primary js-btn-minus reduceQty' data-id='" . $prodID . "' type='button'>&minus;</button>
                                    </div>
                                    <input data-id='" . $prodID . "' id='quantity" . $prodID . "' type='text' class='form-control text-center quantity' value='" . $quantity . "' placeholder='' aria-label='Example text with button addon' aria-describedby='button-addon1'>
                                    <div class='input-group-append'>
                                      <button class='btn btn-outline-primary js-btn-plus addQty' data-id='" . $prodID . "' type='button'>&plus;</button>
                                    </div>
                                  </div>
            
                                </td>
                                <td>$<span class='totalLine' id='totalLine" . $prodID . "' data-total='" . $total . "'>" . number_format($total, 2) . "</span></td>
                                <td><a class='btn btn-primary btn-sm removeItem' data-id='" . $prodID . "'>X</a></td>
                              </tr>";
                      
                      echo $output;

                  }
                ?>

                </tbody>
              </table>
            </div>
          </form>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-6 mb-3 mb-md-0">
                <button id='updateCart' class="btn btn-primary btn-sm btn-block cartBtns">Update Cart</button>
              </div>
              <div class="col-md-6">
                <button class="btn btn-outline-primary btn-sm btn-block cartBtns">
                  <a href='shop.php'>Continue Shopping</a></button>
              </div>
            </div>
            <div class="row" id='updateState'>
              <div class="col-md-12">
                <label class='text-black h4'><span id='updateLabel'></span></label>
                <p id='updateMessage'></p>
              </div>
            </div>
          </div>
          <div class="col-md-6 pl-5">
            <div class="row justify-content-end">
              <div class="col-md-7">
                <div class="row">
                  <div class="col-md-12 text-right border-bottom mb-5">
                    <h3 class="text-black h4 text-uppercase">Cart Summary</h3>
                  </div>
                </div>
                <div class="row mb-5">
                  <div class="col-md-6">
                    <span class="text-black">Total</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black">$<span id='total'></span></strong>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <button class="btn btn-primary btn-lg py-3 btn-block cartBtns" onclick="window.location='checkout.php'">Proceed To Checkout</button>
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