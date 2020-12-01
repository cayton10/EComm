<?

    class Order
    {
        private $database;

        //Set default constructor value for page to 1
        public function __construct()
        {
            $this->database = DB::getInstance();
        }

        //Public function to add order to order table in DB
        public function addOrder($billingAdd, $shippingAdd, $cardID, $cusID, $shippingCost, $orderTrack)
        {
            //Add everything to the new order array
            $newOrder = array
            (
                'add_Bill' => $billingAdd,
                'add_Ship' => $shippingAdd,
                'car_ID' => $cardID,
                'cus_ID' => $cusID,
                'ord_ship' => $shippingCost,
                'ord_track' => '" . $orderTrack . "'
            );

            //Lick stamp and send
            $this->database->insert('`order`', $newOrder);
            //Get the order ID so we can use it for next tables
            $last = $this->database->lastid();

            //Return the order ID for processing other order tables
            return $last;
            
        }

        //Use orderID and cartID to populate order details table
        public function addOrderDetail($orderID, $cartInfo)
        {
            //Will be inserting multiple rows in this function so name our fields in array
            $fields = array
                (
                    'ord_ID',
                    'pro_ID',
                    'ord_Price',
                    'ord_Qty'
                );
            //Declare and initialize our records array which we will fill in our nested foreach loops
            $records = array();

            //Define and iterator
            $i = 0;
            //Process cartInfo to pull out our order details
            foreach($cartInfo as $product)
            {
                //Initialize the product option price
                $optionPrice = 0; 

                //Process the item options to add to orderPrice of product
                foreach($product['options'] as $option)
                {
                    
                    $optionPrice += $option['opt_Price'];
                }
                //Create array that we'll insert into our collection of records

                $orderPrice = $optionPrice + $product['price'];

                $recordArray = array
                (
                    $orderID,
                    $product['prod'],
                    $orderPrice,
                    $product['qty']
                );


                $records[$i] = $recordArray;

                //Increment iterator
                ++$i;
            }


            //Now insert this information using DB class insert Multi
            $this->database->insert_multi('orddetail', $fields, $records);

            //Return affected rows
            $affectedRows = $this->database->affected();
            return $affectedRows;
        }

/* -------------------------------------------------------------------------- */
/*                          Add order detail options                          */
/* -------------------------------------------------------------------------- */
        public function addOrderDetailOpts($orderID, $cartInfo)
        {
            //Load arrays for fields and records to use DB class insert_multi
            $fields = array
            (
                'ord_ID',
                'pro_ID',
                'opt_ID'
            );

            $records = array();
            
            $k = 0;
            
            foreach($cartInfo as $product)
            {
                $prodID = $product['prod'];

                foreach($product['options'] as $option)
                {
                    $optID = $option['opt_ID'];

                    $recordArray = array
                    (
                        $orderID,
                        $prodID,
                        $optID
                    );
                    
                    $records[$k] = $recordArray;

                    ++$k;
                }
            }

            //Insert the information using DB class insert_multi
            $this->database->insert_multi('orddetailopts', $fields, $records);

            //Return affected rows
            $affectedRows = $this->database->affected();
            return $affectedRows;
        }

    }


?>
