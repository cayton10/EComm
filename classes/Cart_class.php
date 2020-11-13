<?

/* -------------------------------------------------------------------------- */
/*           CART CLASS FOR ACCESSING SPECIFIC CART AND ITEMS WITHIN          */
/* -------------------------------------------------------------------------- */

    class Cart extends DB
    {

        //Construct the parent Search class to get an instance of DB
        public function __construct()
        {
            parent::__construct();
        }

        //Count how many items are in the specified cart
        public function getCartInfo($value)
        {
            $cartID = htmlspecialchars(trim($value));

            $query = "SELECT SUM(cart_qty) total
                        FROM cart
                        WHERE cart_ID = '" . $cartID . "'";
            
            $results = $this->get_results($query);


            return $results;
        }

        //Get all product IDs and product quantities
        public function getCartDetail($value)
        {
            $cartID = htmlspecialchars(trim($value));

            $query = "SELECT pro_ID prod, cart_qty qty
                        FROM cart 
                        WHERE cart_ID = '" . $cartID . "'";

            //Fire query and store results
            $results = $this->get_results($query);

            $i = 0;
            //Get our price and option name information and add it to the assoc array.... as an array
            foreach($results as $result)
            {
                $query = "SELECT opt_Name, opt_Value, opt_Price, t1.opt_ID
                            FROM prodopt t1
                            LEFT JOIN cartopts t2 ON t1.opt_ID = t2.opt_ID
                            WHERE t2.cart_ID = '" . $cartID . "' AND t2.pro_ID = '" . $result['prod'] . "'";

                $results[$i]['options'] = $this->get_results($query);
                
                ++$i;
            }

            return $results;
        }

        //Clear cart for updating
        public function clearCart($cartID)//Tried to think of a better way to do this like comparing values, but.... :/
        {

            //We're going to clear all the data from the cart so we can reset it
            $where = array( 'cart_ID' => $cartID);
            $this->delete('cart', $where);

            //Clear all cart data from cartopts as well w/ subsequent reset
            $where = array('cart_ID' => $cartID); 
            $this->delete('cartopts', $where);
            
        }


/* -------------------------------------------------------------------------- */
/*                ADD ITEMS TO CART: QUICKVIEW AND ITEM DETAIL                */
/* -------------------------------------------------------------------------- */

        //Add items to cart
        public function addToCart($cartID, $prodID, $quantity, $options)
        {
            //Declare all of our check variables up here
            $checkVal = false;
            $result;
            $rows;
            $i = 0;
            $duplicate = 0;


            if(!empty($options))//If cart options are passed along
            {
                foreach($options as $option)
                {
                    ++$i;
                }

                //Check all the rows that may or may not exist for this option and store them
                foreach($options as $option)
                {
                    
                
                    $opt_ID = $option->id;
                    $rows[] = $this->num_rows("SELECT *
                                                FROM cartopts
                                                WHERE cart_ID = '" . $cartID ."' 
                                                    AND pro_ID = '" . $prodID . "' 
                                                    AND opt_ID = '" . $opt_ID . "'");
                    //Account for items that only have one option
                    if($i == 1)
                    {
                        //Check for duplicated items
                        $duplicate = $this->num_rows("SELECT t1.cart_ID
                                                        FROM cart t1
                                                        LEFT JOIN cartopts t2 ON t1.cart_ID = t2.cart_ID
                                                        WHERE t2.cart_ID = '" . $cartID . "' 
                                                            AND t2.pro_ID = '" . $prodID . "'");
                    }
                    else
                    {
                        //Check for duplicated items that have multiple options
                        $duplicate = $this->num_rows("SELECT t1.cart_ID
                                                        FROM cart t1
                                                        LEFT JOIN cartopts t2 ON t1.cart_ID = t2.cart_ID
                                                        WHERE t2.cart_ID = '" . $cartID . "' 
                                                            AND t2.pro_ID = '" . $prodID . "' 
                                                            AND t2.opt_ID = '" . $opt_ID . "'");
                    }
                    
                    //Account for items with only one option
                }
            }

            //Reset iterator
            $i = 0;


            if($duplicate > 0)//If the item already exists with options then get out of dodge
            {
                return -1;//Sentinel value to return
            }

            if(!empty($rows))
            {
                //Check for rows that don't have the option selected
                foreach($rows as $row)//If we get a zero number, that means a new option has been selected
                {

                    if($row == 0)
                    {
                        $checkVal = true;
                        
                        //Add this information to the cartopts table and set qty to 1
                        $data = array(
                            'cart_ID' => $cartID,
                            'pro_ID' => $prodID,
                            'opt_ID' => $options[$i]->id
                        );

                        //Fire insert
                        $this->insert('cartopts', $data);   

                        ++$i;//Increment so we can get appropriate option from object array
                    }
                }

                if($checkVal == true)
                {
                    //Add info to cart table
                    $data = array(
                        'cart_ID' => $cartID,
                        'pro_ID' => $prodID,
                        'cart_qty' => $quantity
                    );

                    //Fire insert
                    $this->insert('cart', $data);

                    $result = $this->get_row("select sum(cart_qty) qty from cart where cart_ID='" . $cartID . "'");

                    return $result;
                }
                
            }
            

            //Check if the cartID already exists using DB class
            $rows = $this->num_rows( "SELECT cart_ID 
                                        FROM cart
                                        WHERE cart_ID = '" . $cartID . "'");

            //If the cartID exists, add the items
            if($rows > 0)
            {

/* -------------------- Account for incrementing products ------------------- */

                $rows = $this->num_rows( "SELECT cart_ID
                                            FROM cart
                                            WHERE cart_ID = '". $cartID . "' AND pro_ID = '" . $prodID ."'");

/* ------------------ If item is already in cart, increment ----------------- */
                    if($rows > 0)
                    {
                        //Count the number of items with that pro_ID so we can update
                        $count = $this->get_row("SELECT SUM(cart_qty) qty 
                                                    FROM cart 
                                                    WHERE cart_ID ='" . $cartID . "' AND pro_ID = '" . $prodID . "'");


                        $newQty = $count[0]['qty'] + $quantity;//Store new update quantity
                        
                        //Use DB class function to update record for cart_ID and pro_ID composite key
                        $update = array('cart_qty' => $newQty);
                        $update_where = array('cart_ID' => $cartID, 'pro_ID' => $prodID);
                        $this->update( 'cart', $update, $update_where, 1);
                                            
                        //Count our cart items
                        $result = $this->get_row("select sum(cart_qty) qty from cart where cart_ID='" . $cartID . "'");
                        return $result;
                    }
                    else
                    {
                        //Set up our data array to pass using DB Class 'insert' function
                        $data = array('cart_ID' => $cartID, 'pro_ID' => $prodID, 'cart_qty' => $quantity);
                        
                        //Fire
                        $this->insert('cart', $data);
                        
                        //Count our cart items
                        $result = $this->get_row("select sum(cart_qty) qty from cart where cart_ID='" . $cartID . "'");
                        return $result;
                    }
            }
            else
            {
                $data = array(
                    'cart_ID' => $cartID,
                    'pro_ID' => $prodID,
                    'cart_qty' => $quantity
                );

                //Fire
                $this->insert('cart', $data);
                //Count our cart items
                $result = $this->get_row("select sum(cart_qty) qty from cart where cart_ID='" . $cartID . "'");

                return $result;
            }            
            
        }

/* -------------------------------------------------------------------------- */
/*                          METHOD FOR UPDATING CART                          */
// Problems presented with addToCart() method above when attempting to do so
// from updateCart button on cart page. Reason: On updating cart, items are
// themselves passed from ajax call to php script as objects unlike when 
// adding a single item. When adding a single item, single values of qty,
// id, etc are passed as single values, and the options are passed as an object
// When using the update cart button, the products are stored as objects with
// options as a nested object within the object array. 
/* -------------------------------------------------------------------------- */

        public function updateCart($cartID, $data)
        {

            //Iterator for inserting appropriate options
            $k = 0;

            foreach($data as $item)
            {
                $id = $item->id;
                $qty = $item->qty;
                $options = $item->option;

                //Iterate through options and insert into cartopts table
                if(!empty($options))
                {
                    foreach($options as $option)
                    {
                        $info = array(
                            'cart_ID' => $cartID,
                            'pro_ID' => $id,
                            'opt_ID' => $option
                        );

                        //Fire insert
                        $this->insert('cartopts', $info);

                    }
                }

                //Now insert the item info into the cart table
                $info = array(
                    'cart_ID' => $cartID,
                    'pro_ID' => $id,
                    'cart_qty' => $qty
                );

                $this->insert('cart', $info);
                

            }

            
        }

    }
?>