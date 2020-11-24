<?
require_once('includes/header.php');
//Create an object for products so we can process our cart info
$products = new Product();

?>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header" id='loginHeader'>
        <h5 class="modal-title" id="loginTitle">Login</h5>
        <button id='closeSignInCheckOut' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id='signInCheckout'>
          <div class="form-group">
            <label for='returningEmail'>Email address</label>
            <input type='email' name='email' class='form-control' id='returningEmail' aria-describedby="emailHelp" autocomplete="off" placeholder='peter@parker.edu' value=''>
          </div>
          <div class="form-group">
            <label for='returningPassword'>Password</label>
            <input type='password' name='password' class='form-control' id='returningPassword' autocomplete="off" placeholder="******">
          </div>
        </form>
      </div>
      <div class="modal-footer" id='checkoutModalFooter'>
        <button id='loginButtonCheckOut' type="button" class="btn btn-primary">Login</button>
      </div>
    </div>
  </div>
</div>

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <a href="cart.php">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Checkout</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-12">
            <?
            //If the user session variable is not set, prompt them to sign in
              if(!isset($_SESSION['user']))
              {
                echo "<div id='signing' class='border p-4 rounded' role='alert'>
                        Returning customer? <a id='checkoutLogin' href='#' data-toggle='modal' data-target='#exampleModalCenter'><strong>Click here</strong></a> to login
                      </div>";
              }
            
            ?>
          </div>
        </div>

<!-- USER'S STORED SHIPPING INFORMATION IF EXISTS -->
        <?
        
        //If the user session variable IS set, show a list of previously used addresses
          if(isset($_SESSION['user']))
          {
            $output = '';
            $output .= "<div class='row mb-5' id='previousAdd'>
                          <div class='col-12 mb-5 mb-md-0'>
                            <h2 class='h3 mb-3 text-black'>Previous Shipping Addresses</h2>
                            <div class='p-3 p-lg-5 border'>
                              <div class='form-group row'>
                                <div class='col-md-10' id='previousShippingAdd'>";
                          
                          //Print user's known addresses here for selection
                          $addresses = $customerInfo->getUserShipping();

                         

                          $i = 0; //Use iterator to set first address as checked
                          //Process these addresses and format with radio button
                          foreach($addresses as $address)
                          {
                            foreach($address as $stored)//Address arrays were nested from customer class
                            {

                              if($i == 0)//Output default (first) used address
                              {
                                $output .= "<div class='form-check addressPrev p-4 border' id='addressID" . $stored['id'] ."' data-add='" . $stored['id'] . "'>
                                              <input class='form-check-input addressRadio' type='radio' name='address' data-add='" . $stored['id'] . "'>
                                              <label class='form-check-label addressLabel' for='addressID" . $stored['id'] . "'>"
                                              . $stored['street'] . " " . $stored['street2'] . " " . $stored['city'] . ", " . $stored['state'] . " " . $stored['zip'] . "</label>
                                            </div><br>";
                                            
                              }
                              else if ($i > 0)//Output any other associated addresses
                              {
                                $output .= "<div class='form-check addressPrev p-4 border' id='addressID" . $stored['id'] ."'>
                                              <input class='form-check-input addressRadio' type='radio' name='address' data-add='" . $stored['id'] . "'>
                                              <label class='form-check-label addressLabel' for='addressID" . $stored['id'] . "'>"
                                              . $stored['street'] . " " . $stored['street2'] . " " . $stored['city'] . ", " . $stored['state'] . " " . $stored['zip'] . "</label>
                                            </div><br>";
                              }

                              $i++;
                            }
                            
                          }

          
                $output.= "</div>
                        </div>
                      </div>
                    </div>
                  </div>";

                echo $output;
          }
            
        
        ?>

        <div class="row">
          <div class="col-md-6 mb-5 mb-md-0">
            <h2 class="h3 mb-3 text-black">Billing Details</h2>
            <div class="p-3 p-lg-5 border">
              <form id='shipBillForm'>
                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="c_fname" class="text-black">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-black" id="billingFName" name="c_fname" required>
                  </div>
                  <div class="col-md-6">
                    <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-black" id="billingLName" name="c_lname" required>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-12">
                    <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-black" id="billingAdd1" name="c_address" placeholder="Street address" required>
                  </div>
                </div>

                <div class="form-group">
                  <input type="text" class="form-control" id='billingAdd2' placeholder="Apartment, suite, unit etc. (optional)">
                </div>

                <div class='form-group row'>
                  <div class='col-md-12'>
                    <label for='c_city' class='text-black'>City <span class='text-danger'>*</span></label>
                    <input type='text' class='form-control c_city text-black' id='billingCity' name='billingCity' placeholder='City' required>
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-md-6">
                    <label for="c_state_country" class="text-black">State<span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-black" id="billingState" name="c_state_country" required>
                  </div>
                  <div class="col-md-6">
                    <label for="c_postal_zip" class="text-black">Postal / Zip <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-black" id="billingPost" name="c_postal_zip" required>
                  </div>
                </div>

                <div class="form-group row mb-5">
                  <div class="col-md-12">
                    <label for="c_email_address" class="text-black">Email Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-black" id="billingEmail" name="c_email_address">
                  </div>
                </div>

                <?
                //If user session variable is not set, preset an option to create account on this form
                    if(!isset($_SESSION['user']))
                    {
                      echo "<div class='form-group'>
                              <label for='c_create_account' class='text-black' data-toggle='collapse' href='#create_an_account' role='button' aria-expanded='false' aria-controls='create_an_account'><input type='checkbox' value='1' id='c_create_account'> Create an account?</label>
                              <div class='collapse' id='create_an_account'>
                                <div class='py-2'>
                                  <p class='mb-3'>Create an account by setting and confirming your password below.</p>
                                  <div class='form-group'>
                                    <label for='c_account_password' class='text-black'>Account Password</label>
                                    <input type='email' class='form-control' id='createPW' name='c_account_password' placeholder=''>
                                    <label for='c_account_password' class='text-black'>Confirm Password</label>
                                    <input type='email' class='form-control' id='confirmPW' name='c_account_password' placeholder=''>
                                  </div>
                                </div>
                              </div>
                            </div>";

                    }
                ?>
                
                <div class='form-check'>
                  <label for='shipToSame' class='text-black' role='button' aria-controls='shipToSame'><input name='shippingAdd' type='radio' id='shipToSame' required> Ship To Billing Address</label>
                </div>

                <div class="form-check">
                  <label for="shipDiff" class="text-black" data-toggle="collapse" href="#ship_different_address" role="button" aria-expanded="false" aria-controls="ship_different_address" id='shipDiffLabel'><input type="radio" id="shipDiff" name='shippingAdd'> Ship To A Different Address?</label>
                </div>

                <div class="collapse" id="ship_different_address">
                  <div class="py-2">


                    <div class="form-group row">
                      <div class="col-md-6">
                        <label for="c_diff_fname" class="text-black">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-black" id="shipFName" name="c_diff_fname">
                      </div>
                      <div class="col-md-6">
                        <label for="c_diff_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-black" id="shipLName" name="c_diff_lname">
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <label for="c_diff_address" class="text-black">Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-black" id="shipAddress1" name="c_diff_address" placeholder="Street address">
                      </div>
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control text-black" id='shipAddress2' placeholder="Apartment, suite, unit etc. (optional)">
                    </div>

                    <div class='form-group row'>
                      <div class='col-md-12'>
                        <label for='c_city' class='text-black'>City <span class='text-danger'>*</span></label>
                        <input type='text' class='form-control c_city text-black' id='shipCity' name='billingCity' placeholder='City'>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-6">
                        <label for="c_diff_state_country" class="text-black">State<span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-black" id="shipState" name="c_diff_state_country">
                      </div>
                      <div class="col-md-6">
                        <label for="c_diff_postal_zip" class="text-black">Postal / Zip <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-black" id="shipPostal" name="c_diff_postal_zip">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <button type='submit' class="btn btn-primary btn-lg py-3 btn-block" id='confirmShipping'>Confirm Shipping</button>
                </div>
              </form>
            </div>
          </div>
          <div class="col-md-6">
            
            <div class="row mb-5">
              <div class="col-md-12">
                <h2 class="h3 mb-3 text-black">Your Order</h2>
                <div class="p-3 p-lg-5 border">
                  <table class="table site-block-order-table mb-5">
                    <thead>
                      <th>Product</th>
                      <th>Total</th>
                    </thead>
                    <tbody>
                      <?

                        if(isset($_COOKIE['cartID']))
                        {
                          $cartContents = $cart->getCartDetail($_COOKIE['cartID']);

                          $output = '';
                          $i = 0;
                          $subtotal;
                          $orderWeight;

                          foreach($cartContents as $item)
                          {
                            //Set product ID to var and change from string to int
                            $prod = $item['prod'];
                            $prod = (integer)$prod;


                            //Set all of THIS item's specifics
                            $products->querySingleItem($prod);

                            //Set needed info for printing table rows
                            $prodName = $products->getName();
                            $output .= "<tr>
                                          <td>" . $prodName . "<strong class='mx-2'>x</strong>" . $item['qty'] . "</td>";

                            //Work out math for each item
                            $basePrice = $products->getPrice();
                            
                            foreach($item['options'] as $option)
                            {
                              $basePrice += $option['opt_Price'];
                            }

                            $totalPrice = $basePrice * $item['qty'];
                            $subtotal += $totalPrice;

                            //Work out math for item weights
                            $orderWeight += $item['wt'] * $item['qty'];

                            $output .= "<td>$" . number_format($totalPrice, 2) . "</td></tr>";
                          }

                          $output .= "<tr>
                                        <td class='text-black font-weight-bold checkSubTot'><strong>Cart Subtotal</strong></td>
                                        <td class='text-black checkSubTot'><strong>$" . number_format($subtotal, 2) . "</strong></td>
                                      </tr>
                                      <tr>
                                        <td class='text-black checkShipWt'><strong>Shipping Weight</strong></td>
                                        <td class='text-black checkShipWt'><strong>" . $orderWeight . " lbs</strong></td>
                                      </tr>";
                                      
                          echo $output;

                        }

                      ?>
                      
                      <tr>
                        <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                        <td class="text-black font-weight-bold"><strong>$350.00</strong></td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="border p-3 mb-3">
                    <h3 class="h6 mb-0"><a class="d-block" data-toggle="collapse" href="#collapsebank" role="button" aria-expanded="false" aria-controls="collapsebank">Direct Bank Transfer</a></h3>

                    <div class="collapse" id="collapsebank">
                      <div class="py-2">
                        <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
                      </div>
                    </div>
                  </div>

                  <div class="border p-3 mb-3">
                    <h3 class="h6 mb-0"><a class="d-block" data-toggle="collapse" href="#collapsecheque" role="button" aria-expanded="false" aria-controls="collapsecheque">Cheque Payment</a></h3>

                    <div class="collapse" id="collapsecheque">
                      <div class="py-2">
                        <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
                      </div>
                    </div>
                  </div>

                  <div class="border p-3 mb-5">
                    <h3 class="h6 mb-0"><a class="d-block" data-toggle="collapse" href="#collapsepaypal" role="button" aria-expanded="false" aria-controls="collapsepaypal">Paypal</a></h3>

                    <div class="collapse" id="collapsepaypal">
                      <div class="py-2">
                        <p class="mb-0">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order won’t be shipped until the funds have cleared in our account.</p>
                      </div>
                    </div>
                  </div>

                  <div class="form-group" id='placeOrderButtonDiv'>
                    <button class="btn btn-primary btn-lg py-3 btn-block" id='placeOrder' type='submit' onclick="window.location='thankyou.html'">Place Order</button>
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
        <!-- </form> -->
      </div>
    </div>

<?
require_once('includes/footer.php');
?>
    
  </body>
</html>