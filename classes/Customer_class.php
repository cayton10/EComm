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

        

        //Construct database when Customer object is made
        public function __construct()
        {
            $this->database = DB::getInstance();
        }

        //Method to grab customer name for displaying for UX
        public function getCustomerName($id)
        {
            
        }
    }
?>