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

        //Customer information from Address table
        private $add_ID;
        private $add_Street;
        private $add_Street2;
        private $add_City;
        private $add_State;
        private $add_Zip;

        //Customer information from Card table
        private $car_ID;
        private $car_Num;
        private $car_Name;
        private $car_Exp;
        private $car_Sec;
        private $car_Active;




        //Construct database when Customer object is made
        public function __construct()
        {
            $this->database = DB::getInstance();
        }

        /*customerGetAll
        * Grabs all details for given customer based on 
        * customer_id parameter*/
        public function customerGetAll($id)
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
                        LEFT JOIN card t3 ON t2.cus_ID = t3.cus_ID";
            
            $results = $this->database->get_results($id);

            //Store all the information in our object member variables
            if($results)
            {
                $data = $results[0];
                $this->cus_FirstName = $item['cus_FirstName'];
                $this->cus_LastName = $item['cus_LastName'];
                $this->cus_EMail = $item['cus_EMail'];
                $this->cus_Password = $item['cus_Password'];
                $this->add_ID = $item['add_ID'];
                $this->add_Street = $item['add_Street'];
                $this->add_Street2 = $item['add_Street2'];
                $this->add_City = $item['add_City'];
                $this->add_State = $item['add_State'];
                $this->add_Zip = $item['add_Zip'];
                $this->car_ID = $item['car_ID'];
                $this->car_Num = $item['car_Num'];
                $this->car_Name = $item['car_Name'];
                $this->car_Exp = $item['car_Exp'];
                $this->car_Sec = $item['car_Sec'];
                $this->car_Active = $item['car_Active'];
            }
        }


        //Method to grab customer name for displaying for UX
        public function getCustomerName($id)
        {
            
        }
    }
?>