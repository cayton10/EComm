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
                        WHERE t1.cus_ID = '" . $id . "'";
            
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

        //Returns all addresses for a user if they are registered users
        public function getUserShipping()
        {
            $addresses = array(
                $this->address
            );

            return $addresses;
        }
    }
?>