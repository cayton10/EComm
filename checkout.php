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
        <h5 class="modal-title" id="loginTitle"></h5>
        <button id='closeSignInCheckOut' type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='successMessage'>
        <form id='signInCheckout'>
          <div class="form-group row" id='registerNamesDiv'>
            <div class="col-md-6">
              <label for="registerFirst" class="text-black">First Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-black" id="registerFirst" name="registerLast">
            </div>
            <div class="col-md-6">
              <label for="registerLast" class="text-black">Last Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control text-black" id="registerLast" name="registerFirst">
            </div>
          </div>
          <div class="form-group">
            <label for='email'>Email address</label>
            <input type='email' name='email' class='form-control' id='email' aria-describedby="emailHelp" autocomplete="off" placeholder='peter@parker.edu' value='' required>
          </div>
          <div class="form-group">
            <label for='password'>Password</label>
            <input type='password' name='password' class='form-control' id='password' autocomplete="off" minlength="6" required>
          </div>
          <div class='form-group' id='confirmPWDiv'>
            <label for='confirmPassword'>Confirm Password</label>
            <input type='password' name='confirmPassword' class='form-control' id='confirmPassword' autocomplete='off'>
          </div>
          <div class='checkoutModalError'></div>

          <div class="modal-footer" id='checkoutModalFooter'>
            <button id='loginButtonCheckOut' type="submit" class="btn btn-primary"></button>
          </div>
        </form>
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
        <div class="row">
          <div class="col-md-12">
            <?
            //If the user session variable is not set, prompt them to sign in
              if(!isset($_SESSION['user']))
              {
                echo "<div id='tellem' class='text-center text-black'><h2>New Phone... Who dis?</h2></div>
                      <div id='signin' class='border p-4 rounded' role='alert'>
                        <div id='login'>
                          <button class='btn btn-primary' id='checkoutLogin' href='#' data-toggle='modal' data-target='#exampleModalCenter'><strong>Login</strong></button>
                        </div>
                        <div id='register'>
                          <button class='btn btn-primary' id='checkoutRegister' href='#' data-toggle='modal' data-target='#exampleModalCenter'><strong>Register</strong></button>
                        </div>
                      </div>";
                      
              }
            
            ?>
          </div>
        </div>

<!-- USER'S STORED SHIPPING INFORMATION IF EXISTS -->
        <?

          //Check if this user has any previous addresses
          $addresses = $customerInfo->getUserShipping();
    

        //If the user session variable IS set, show a list of previously used addresses
          if(isset($_SESSION['user']) && !empty($addresses))
          {
            $output = '';
            $output .= "<div class='row mb-5' id='previousAdd'>
                          <div class='col-12 mb-5 mb-md-0'>
                            <h2 class='h3 mb-3 text-black'>Previous Shipping Addresses</h2>
                            <div class='p-3 p-lg-5 border'>
                              <div class='form-group row'>
                                <div class='col-md-6' id='previousShippingAdd'>";

                         

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

        <!-- USER'S STORED PAYMENT INFORMATION IF EXISTS -->
        <?

          //Check if this user has any previous addresses
          $payments = $customerInfo->getUserPayment();
    

        //If the user session variable IS set, show a list of previously used addresses
          if(isset($_SESSION['user']) && !empty($payments))
          {
            $output = '';
            $output .= "<div class='row mb-5' id='previousCard'>
                          <div class='col-12 mb-5 mb-md-0'>
                            <h2 class='h3 mb-3 text-black'>Previous Payment Methods</h2>
                            <div class='p-3 p-lg-5 border'>
                              <div class='form-group row'>
                                <div class='col-md-10' id='previousPaymentInfo'>";

                         

                          $i = 0; //Use iterator to set first card as checked
                          //Process these cards and format with radio button
                          foreach($payments as $payment)
                          {
                            foreach($payment as $stored)//Card arrays were nested from customer class
                            {

                              //Set up our values to return
                              $cardName = $stored['name'];
                              $lastFour = substr($stored['num'], -4);
                              $expArray = explode('/', $stored['exp']);
                              $month = '';
                              $year = '';
                              $i = 0;

                              foreach($expArray as $exp)
                              {
                                if($i == 0)
                                {
                                  $month = $exp;
                                }
                                else
                                {
                                  $year = $exp;
                                }
                                ++$i;
                              }
                              
                              if($i == 0)//Output default (first) used card
                              {
                                $output .= "<div class='form-check cardPrev p-4 border'>
                                              <input class='form-check-input cardRadio' type='radio' name='address' data-card='" . $stored['id'] . "'>
                                              <label class='form-check-label cardLabel' for='cardID" . $stored['id'] . "'>Card Owner: " . $cardName . "<br>Card Ending: " . $lastFour . "<br>
                                              Expires: ". $month . "/" . $year . "</label>
                                            </div><br>";
                                            
                              }
                              else if ($i > 0)//Output any other associated addresses
                              {
                                $output .= "<div class='form-check cardPrev p-4 border'>
                                              <input class='form-check-input cardRadio' type='radio' name='address' data-card='" . $stored['id'] . "'>
                                              <label class='form-check-label cardLabel' for='cardID" . $stored['id'] . "'>Card Owner: " . $cardName . "<br>Card Ending: " . $lastFour . "<br>
                                              Expires: ". $month . "/" . $year . "</label>
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
              <form id='shipBillForm' method='post'>
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
                    <input maxlength='2' type="text" class="form-control text-black" id="billingState" name="c_state_country" required>
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
                        <input maxlength='2' type="text" class="form-control text-black" id="shipState" name="c_diff_state_country">
                      </div>
                      <div class="col-md-6">
                        <label for="c_diff_postal_zip" class="text-black">Postal / Zip <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-black" id="shipPostal" name="c_diff_postal_zip">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <button type='submit' class="btn btn-primary btn-lg py-3 btn-block" id='confirmShipping'>Confirm Address</button>
                </div>
              </form>
            </div>
          </div>

<!-- Payment info form -->
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-12">
                <h2 class="h3 mb-3 text-black">Payment Information</h2>
                <div class="p-3 p-lg-5 border">
                  <form id='paymentInfoForm'>

                    <div class="form-group row">
                      <div class="col-md-12">
                        <label for="cardNumber" class="text-black">Card Number <span class="text-danger">*</span></label>
                        <input id="cardNumber" class='form-control text-black' type="tel" inputmode="numeric" autocomplete="cc-number" minlength='15' maxlength="16" placeholder="xxxx xxxx xxxx xxxx" autocomplete="off" required>
                      </div>
                    </div>

                    <div class='form-group row'>
                      <div class='col-md-12'>
                        <label for='cardName' class='text-black'>Cardholder Name <span class='text-danger'>*</span></label>
                        <input type='text' class='form-control cardNumber text-black' id='cardName' name='cardName' placeholder='Name As Appears on Card' required autocomplete='off'>
                      </div>
                    </div>

                    <div class="form-group row">
                      <div class="col-md-6">
                        <label for="expirationMonth" class="text-black">Expiration Month<span class="text-danger">*</span></label>
                        <select class="form-control text-black" id="expirationMonth" required>
                          <option value='' selected disabled hidden class='default'></option>
                          <option value='01'>01</option>
                          <option value='02'>02</option>
                          <option value='03'>03</option>
                          <option value='04'>04</option>
                          <option value='05'>05</option>
                          <option value='06'>06</option>
                          <option value='07'>07</option>
                          <option value='08'>08</option>
                          <option value='09'>09</option>
                          <option value='10'>10</option>
                          <option value='11'>11</option>
                          <option value='12'>12</option>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label for="expirationYear" class="text-black">Expiration Year <span class="text-danger">*</span></label>
                          <select class="form-control text-black" id="expirationYear" required>
                            <option value='' selected disabled hidden class='default'></option>
                            <option value='2020'>2020</option>
                            <option value='2021'>2021</option>
                            <option value='2022'>2022</option>
                            <option value='2023'>2023</option>
                            <option value='2024'>2024</option>
                            <option value='2025'>2025</option>
                          </select>
                      </div>
                    </div>

                    <div class="form-group row mb-5">
                      <div class="col-md-6">
                        <label for="securityCode" class="text-black">Security Code <span class="text-danger">*</span></label>
                        <input type="password" class="form-control text-black" id="securityCode" name="securityCode" required autocomplete="off" maxlength="4">
                      </div>
                    </div>

                    <div id='confirmPaymentDiv'>
                      <div class="form-group">
                        <button type='submit' class="btn btn-primary btn-lg py-3 btn-block" id='confirmPayment'>Confirm Payment</button>
                      </div>
                    </div>
                    
                  </form>
                  

                  

                </div>
              </div>
            </div>
          </div>


          <div class="col-12 mt-5">
            <div class="row mb-5">
              <div class="col-md-12">
                <h2 class="h3 mb-3 text-black text-center">Your Order</h2>
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
                              $optID = $option['opt_ID'];
                            }

                            $totalPrice = $basePrice * $item['qty'];
                            $subtotal += $totalPrice;

                            //Work out math for item weights
                            $orderWeight += $item['wt'] * $item['qty'];

                            $output .= "<td>$<span class='optionID' data-opt='" . $optID . "'>" . number_format($totalPrice, 2) . "</span></td></tr>";
                          }

                          $output .= "<tr>
                                        <td class='text-black font-weight-bold checkSubTot'><strong>Cart Subtotal</strong></td>
                                        <td class='text-black checkSubTot'><strong>$<span id='subTotal'>" . number_format($subtotal, 2) . "</span></strong></td>
                                      </tr>
                                      <tr>
                                        <td class='text-black checkShipWt'><strong>Shipping Weight</strong></td>
                                        <td class='text-black checkShipWt'><strong><span id='shipWeight'>" . $orderWeight . "</span> lbs</strong></td>
                                      </tr>";
                                      
                          echo $output;

                        }

                      ?>
                      <tr class='orderSummaryShipping'>
                        <td class='text-black'><strong>UPS Ground Shipping</strong></td>
                        <td class='text-black'><strong>$<span id='shippingCost'></span></strong></td>
                      </tr>
                      <tr>
                        <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                        <td class="text-black font-weight-bold"><strong>$<span id='orderTotal'></span></strong></td>
                      </tr>
                    </tbody>
                  </table>

                  <div class='row' id='placeOrderButtonRow'>
                    <div class="form-group col-md-4 m-0 mr-0" id='placeOrderButtonDiv'>
                      <button class="btn btn-primary btn-lg py-3 btn-block" id='placeOrder' type='submit'>Place Order</button>
                    </div>
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