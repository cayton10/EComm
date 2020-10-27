<?

/* -------------------------------------------------------------------------- */
/*           CART CLASS FOR ACCESSING SPECIFIC CART AND ITEMS WITHIN          */
/* -------------------------------------------------------------------------- */

    class Cart extends DB
    {
        //Private member variables for cart objects
        private $cartID;
        private $pro_ID;
        private $cartQty;


        //Construct the parent Search class to get an instance of DB
        public function __construct()
        {
            parent::__construct();
        }

        //Set cartID
        public function setCartID($value)
        {
            $this->cartID = $value;
        }

        public function getCartID()
        {
            return $this->cartID;
            
        }

        public function getCartInfo($value)
        {
            $cartID = htmlspecialchars(trim($value));

            $query = "SELECT COUNT(cart_qty) total
                        FROM cart
                        WHERE cart_ID = '" . $cartID . "'";
            
            $results = $this->get_results($query);

            return $results;
        }


    }
?>