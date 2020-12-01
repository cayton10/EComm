<?

    class CreditCard
    {
        private $database;

        //Construct database when Customer object is made
        public function __construct()
        {
            $this->database = DB::getInstance();
        }


        //Use this function to add a new address to the 'address' db table
        public function addCard($cardNum, $cardName, $cardExp, $cardSec, $cardActive, $cardAddr, $cusID)
        {
            //Declare variable to store addressID
            $cardID;
            //First set up a query to find latest addressID
            
            $query = "SELECT COALESCE(MAX(car_ID), 0) + 1 
                        FROM card";
            $maxID = $this->database->get_results($query); //Originally just used MAX() to find most recent id
    
            
            //Dig through associative array to get last inserted ID
            foreach($maxID as $id)
            {
                foreach($id as $cardID)
                {
                    $cardID = $cardID;
                }
            }

            //Store information into array
            $newCard = array
            (
                'car_ID' => $cardID,
                'car_Num' => $cardNum,
                'car_Name' => $cardName,
                'car_Exp' => $cardExp,
                'car_Sec' => $cardSec,
                'car_Active' => $cardActive,
                'add_ID' => $cardAddr,
                'cus_ID' => $cusID
            );

            //Insert the info into the address table
            $this->database->insert('card', $newCard);

            $affected = $this->database->affected();
            if($affected == 1)
            {
                return $cardID;
            }
            else
                return false;
        }


        public function checkCard($cardNum, $cardName, $cardExp, $cardSec, $cardActive, $cardAddr, $cusID)
        {
            //Declare variable to store addressID, if it already exists
            $cardID;

            echo $cardID;
            
            $query = '';

            $query = "SELECT car_ID
                        FROM card
                        WHERE car_Num = '" . $cardNum . "' AND car_Name = '" . $cardName . "' AND car_Exp = '" . $cardExp . "' AND car_Sec = '" . $cardSec . "' AND car_Active = '" . $cardActive . "' AND add_ID = '" . $cardAddr . "' AND cus_ID = $cusID";


            $cardID = $this->database->get_results($query);            

            //If the card doesn't already exist, add it
            if(empty($cardID))
            {
                echo "HERE";
                $cardID = $this->addCard($cardNum, $cardName, $cardExp, $cardSec, $cardActive, $cardAddr, $cusID);
                
                return $cardID;

            }
            else
                return $cardID;//Return the address ID if it exists
        }
    }
?>