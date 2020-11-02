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

            return $results;
        }

        //Clear cart for updating
        public function clearCart($cartID)//Tried to think of a better way to do this like comparing values, but.... :/
        {

            //We're going to clear all the data from the cart so we can reset it
            $where = array( 'cart_ID' => $cartID);
            $this->delete('cart', $where);
            
        }

        //Add items to cart
        public function addToCart($cartID, $prodID, $quantity)
        {

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

    }
?>