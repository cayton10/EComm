<?
    //Create category class to inherit database class
    class Category
    {
        //Database private member variable
        private $database;

        //Constructor?
        public function __construct()
        {
            $this->database = DB::getInstance();
        }
        
        
        /**
         * Retrieve results of a standard query
         */
        public function getCat()
        {
            //Define output variable to return and initialize to empty string
            $output = "";
            $query = "SELECT * FROM category
                    WHERE cat_SubCat IS NULL
                    ORDER BY cat_Name";
 
            $results = $this->database->get_results( $query );
            //foreach to process returned categories and ouput as buttons
            if ($results)
            {
                foreach( $results as $row )
                {
                    
                    $mainCat = $row['cat_ID'];
                    //If subcat is null, create the desired cat button and return it.
                    if (is_null($row['cat_SubCat'])) 
                    {
                        
                        $output .= "<li class='mb-1'>
                        <div class='dropright'><button type='button' class='btn btn-secondary dropdown-toggle'data-toggle='dropdown' " . $row['cat_ID'] . " aria-haspopup='true' aria-expanded='false'>
                        <span class='catName'>". $row['cat_Name'] . "</span></button>";

                        //Add link to list all of particular category product
                        $output .= "<div class='dropdown-menu'>
                        <ul class='list-unstyled mb-0'>
                        <li><a href='?page=1&MainCat=" . $row['cat_ID'] . "&name=" . $row['cat_Name'] . "' class='p-2 catSelect'>All " . $row['cat_Name'] . "</a></li>";

                        //Call function to get subcategory and list as dropright menu
                        $output .= $this->getSubCat($mainCat);
                    }
                    

                    
                }
            } 
            
            return $output;
            
        }
        /**
         * FUNCTION IS CALLED BY GETCAT() FUNCTION ABOVE
         * MAIN CATEGORY ID IS PASSED AS PARAMETER AND COMPARED TO SUBCAT ID
         * TO RETURN FORMATTED HTML TO OUTPUT IN GETCAT()
         */
        private function getSubCat($value)
        {
            //Query again
            $output = "";
            $query = "SELECT * FROM category
                    WHERE cat_SubCat IN ($value)
                    ORDER BY cat_Name";
            //Store results
            $results = $this->database->get_results( $query );
            if($results)
            {
                foreach ($results as $row)
                {
                    $output .= "<li><a href='?page=1&category=" . $row['cat_ID'] . "&name=" . $row['cat_Name'] . "' class='p-2 catSelect'>" . $row['cat_Name'] . "</a></li>";
                }
                //Tie up the end of the dropright div and return output to calling function
                $output .= "</ul></div></div></li>";
            }

            return $output;
        }
    }

    

?>


