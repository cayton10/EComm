<?
/* -------------------------------------------------------------------------- */
/*                   PAGINATION CLASS                                         */
/* -------------------------------------------------------------------------- */
class Paginate
{
    //Create private member variable to store results
    private $currentPage;
    private $limit;
    private $database;
    private $totalPages;

    //Set default constructor value for page to 1
    public function __construct()
    {
        $this->database = DB::getInstance();
    }

    //Wrote these public functions for testing and debugging
    public function getLimit()
    {
        return $this->limit;
    }

    //Get total pages
    public function getTotalPages()
    {
        return $this->totalPages;
    }
    //Set total pages
    public function setTotalPages($limit, $value, $type)
    {
        $this->limit = $limit;
        //Query DB to get total count of all products

        if($value == '')
        {
            $query = "SELECT count(pro_ID) AS id
                        FROM product";
        }
        else if($type == 'sub')
        {
            $query = "SELECT count(pro_ID) AS id
                        FROM product
                        WHERE cat_ID = $value";
        }
        else
            $query = "SELECT count(pro_ID) AS id
                        FROM product t1
                        LEFT JOIN category t2 ON t1.cat_ID = t2.cat_ID
                        WHERE t2.cat_SubCat = $value";
        
        //Store result as array
        $result = $this->database->get_results($query);
        //Access array for total products in DB
        $total = $result[0]['id'];
        //Create appropriate page range for number of products in DB
        $this->totalPages = ceil($total / $this->limit);
    }

    //Sets current page based on query string passed
    public function setCurrentPage($page)
    {
        $this->currentPage = $page;
    }
    //Get current page
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public static function setStart($page, $limit)
    {
        $start = (($page - 1) * $limit) + 1;
    }



/* ---------- CREATE PAGINATION HTML ELEMENT DYNAMICALLY AND OUTPUT --------- */

    public function printPagination()
    {
        //Declare variables and logic for << >> buttons on pagination
        $back = $this->currentPage - 1;
        $next = $this->currentPage + 1;

        $output = "";
        $output .= "<div id='pagination' class='row' data-aos='fade-up'>
                        <div class='col-md-12 text-center'>
                            <div class='site-block-27'>
                                <ul>
                                    <li><a href='shop.php?page=" . $back . "'>&lt;</a></li>";
                for($i = 1; $i <= $this->totalPages; $i++)
                {   //Set active page link
                    if($i == $this->currentPage)
                    {
                        $output .= "<li class='active'><a href='shop.php?page=" . $i . "'><span>" . $i . "</span></a></li>";
                    }
                    else
                    {
                        $output .= "<li><a href='shop.php?page=" . $i . "'><span>" . $i . "</span></a></li>";
                    }
                }
                        $output .= "<li><a href='shop.php?page=" . $next . "'>&gt;</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>";

      return $output;
    }
}

?>