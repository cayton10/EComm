<?

    class Address
    {
        private $database;

        //Construct database when Customer object is made
        public function __construct()
        {
            $this->database = DB::getInstance();
        }


        //Use this function to add a new address to the 'address' db table
        public function addAddress($st1, $st2, $city, $state, $zip, $cusID)
        {
            //Declare variable to store addressID
            $addID;
            //First set up a query to find latest addressID
            
            $query = "SELECT COALESCE(MAX(add_ID), 0) + 1 
                        FROM address";
            $maxID = $this->database->get_results($query); //Originally just used MAX() to find most recent id
    
            //Dig through associative array to get last inserted ID
            foreach($maxID as $id)
            {
                foreach($id as $addID)
                {
                    $addID = $addID;
                }
            }

            //Store information into array
            $newAddress = array
            (
                'add_ID' => $addID,
                'add_Street' => $st1,
                'add_Street2' => $st2,
                'add_City' => $city,
                'add_State' => $state,
                'add_Zip' => $zip,
                'cus_ID' => $cusID
            );

            //Insert the info into the address table
            $this->database->insert('address', $newAddress);

            $affected = $this->database->affected();
            if($affected == 1)
            {
                return $addID;
            }
            else
                return false;
        }


        public function checkAddress($st1, $st2, $city, $state, $zip, $cusID)
        {
            //Declare variable to store addressID, if it already exists
            $addID;


            $query = '';

            if(!is_null($st2))
            {
                $query = "SELECT add_ID
                        FROM address
                        WHERE add_Street = '" . $st1 . "' AND add_Street2 = '" . $st2 . "' AND add_City = '" . $city . "' AND add_State = '" . $state . "' AND add_Zip = '" . $zip . "' AND cus_ID = $cusID";
            }
            else
            {                                   //If street add 2 is null, don't use it to compare fields in db

                $query = "SELECT add_ID
                        FROM address
                        WHERE add_Street = '" . $st1 . "' AND add_City = '" . $city . "' AND add_State = '" . $state . "' AND add_Zip = '" . $zip . "' AND cus_ID = $cusID";
            }

            

            $addID = $this->database->get_results($query);
                

            //If the address doesn't already exist, add it
            if(empty($addID))
            {
                $addID = $this->addAddress($st1, $st2, $city, $state, $zip, $cusID);

                return $addID;

            }
            else
                return $addID;//Return the address ID if it exists
        }
    }
?>