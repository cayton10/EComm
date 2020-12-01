<?

/* -------------------------------------------------------------------------- */
/*              CUSTOMER CLASS TO RETRIEVE / INJECT CUSTOMER DATA             */
/* Customer class will be comprised of member variables and methods for 
    CRUD of three database tables:
    1. address
    2. card
    3. customer
/* -------------------------------------------------------------------------- */

    class Customer
    {
        //Keep a database member variable private for accessing from class methods
        private $database;

        //Customer information from customer table
        private $cus_ID;
        private $cus_FirstName;
        private $cus_LastName;
        private $cus_EMail;
        private $cus_Password;


        private $address = [];
        //Customer information from Address table
        private $add_ID = [];
        private $add_Street = [];
        private $add_Street2 = [];
        private $add_City = [];
        private $add_State = [];
        private $add_Zip = [];

        private $card = [];
        //Customer information from Card table
        private $car_ID = [];
        private $car_Num = [];
        private $car_Name = [];
        private $car_Exp = [];
        private $car_Sec = [];
        private $car_Active = [];




        //Construct database when Customer object is made
        public function __construct()
        {
            $this->database = DB::getInstance();
        }

        //Grab first name for pretty header and cozy welcome message
        public function getFirstName()
        {
            return $this->cus_FirstName;
        }

        //Grab last name for form field population
        public function getLastName()
        {
            return $this->cus_LastName;
        }

        //Grab email address for form field population
        public function getEmail()
        {
            return $this->cus_EMail;
        }

        /*customerGetAll
        * Grabs all details for given customer based on 
        * customer_id parameter*/
        public function customerSetAll($id)
        {
            $query = '';

            $query = "SELECT 
                            t1.cus_FirstName,
                            t1.cus_LastName,
                            t1.cus_EMail,
                            t1.cus_Password,
                            t2.add_ID,
                            t2.add_Street,
                            t2.add_Street2,
                            t2.add_City,
                            t2.add_State,
                            t2.add_Zip,
                            t3.car_ID,
                            t3.car_Num,
                            t3.car_Name,
                            t3.car_Exp,
                            t3.car_Sec,
                            t3.car_Active
                        FROM customer t1
                        LEFT JOIN address t2 ON t1.cus_ID = t2.cus_ID
                        LEFT JOIN card t3 ON t2.cus_ID = t3.cus_ID
                        WHERE t1.cus_ID = '" . $id . "' AND t3.car_Active = 'Y'";
            
            $results = $this->database->get_results($query);

            //Store all the information in our object member variables
            if($results)
            {


                foreach($results as $data)
                {
                    $this->cus_FirstName = $data['cus_FirstName'];
                    $this->cus_LastName = $data['cus_LastName'];
                    $this->cus_EMail = $data['cus_EMail'];
                    $this->cus_Password = $data['cus_Password'];

                    //Create associate address array to store in object
                    $address = array(
                        "id" => $data['add_ID'],
                        "street" => $data['add_Street'],
                        "street2" => $data['add_Street2'],
                        "city" => $data['add_City'],
                        "state" => $data['add_State'],
                        "zip" => $data['add_Zip']
                    );

                    $this->address[] = $address;

                    $cards = array(
                        'id' => $data['car_ID'],
                        'num' => $data['car_Num'],
                        'name' => $data['car_Name'],
                        'exp' => $data['car_Exp'],
                    );

                    $this->card[] = $cards;

                    $this->car_ID[] = $data['car_ID'];
                    $this->car_Num[] = $data['car_Num'];
                    $this->car_Name[] = $data['car_Name'];
                    $this->car_Exp[] = $data['car_Exp'];
                    $this->car_Sec[] = $data['car_Sec'];
                    $this->car_Active[] = $data['car_Active'];
                    
                }
                
            }
        }


        //Check if user exists with credentials
        public function checkUserAndPass($email, $password)
        {
            $query = "";

            $query = "SELECT cus_ID id
                        FROM customer
                        WHERE cus_Email = '" . $email . "' AND cus_Password = '" . $password . "'";
            
            $rows = $this->database->num_rows($query);

            $id;
            //Control flow for what to return
            if($rows == 1)
            {
                $id = $this->database->get_results($query);
                return $id;
            }
            else
                return $rows;
        }

        //Add new customer to database
        public function addUser($first, $last, $email, $password)
        {

            //Declare variable to store customerID
            $customerID;
            //First set up a query to find latest customer ID
            
            $query = "SELECT COALESCE(MAX(cus_ID), 0) + 1 
                        FROM customer";
            $maxID = $this->database->get_results($query); //Originally just used MAX() to find most recent id
    
            //Dig through associative array to get last inserted ID
            foreach($maxID as $id)
            {
                foreach($id as $customerID)
                {
                    $customerID = $customerID;
                }
            }

            //Store our new user information into an array
            $newUser = array(
                'cus_ID' => $customerID,
                'cus_FirstName' => $first,
                'cus_LastName' => $last,
                'cus_EMail' => $email,
                'cus_Password' => $password
            );

            //Insert that information into customer table
            $this->database->insert('customer', $newUser);

            //Check if insert was successful
            $affectedRows['valid'] = $this->database->affected();
            $affectedRows['uID'] = $customerID; 

            return $affectedRows;

        }

        //Returns all addresses for a user if they are registered users
        public function getUserShipping()
        {
            $addresses = array(
                $this->address
            );

            return $addresses;
        }

        //Returns all recorded payments for a user if they are registered users
        public function getUserPayment()
        {
            $cards = array(
                $this->card
            );

            return $cards;
        }

        /*
        **Get customer previous address using address ID
        */
        public function getPrevAddress($id)
        {
            $query = '';

            //Took the time to write this out because don't want DB fields floating around
            $query = "SELECT add_Street street,
                                add_Street2 street2,
                                add_City city,
                                add_State state,
                                add_Zip zip,
                                cus_FirstName first,
                                cus_LastName last,
                                cus_EMail email
                        FROM address t1
                        LEFT JOIN customer t2 ON t1.cus_ID = t2.cus_ID
                        WHERE add_ID = $id";
            
            $address = $this->database->get_results($query);

            return $address;
        }

        /*
        **Get customer previous card using card ID
        */
        public function getPrevCard($id)
        {
            $query = '';

            $query = "SELECT car_Num num, car_Name carName, car_Exp ex
                        FROM card
                        WHERE car_ID = $id";
            
            $card = $this->database->get_results($query);

            return $card;
        }

        /*
        **Check customer DB for registration email attempt
        */
        public function checkEmail($email)
        {
            $query = '';

            $query = "SELECT cus_EMail
                        FROM customer
                        WHERE cus_EMail = '" . $email . "'";
            
            $numRows = $this->database->num_rows($query);

            return $numRows;
        }
    }
?>