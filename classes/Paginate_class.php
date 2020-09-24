<?
/* -------------------------------------------------------------------------- */
/*                   PAGINATION CLASS EXTENDS PRODUCT CLASS                   */
/* -------------------------------------------------------------------------- */
class Paginate
{
    //Create private member variable to store results
    private $currentPage;
    private $limit = 6;
    private $database;
    private $totalPages;

    //Set default constructor value for page to 1
    public function __construct($pageNumber = 1)
    {
        $this->database = DB::getInstance();
        //Use pageNumber parameter for assignment
        $this->currentPage = $pageNumber;
    }

    //Wrote these public functions for testing and debugging
    public function getLimit()
    {
        return $this->limit;
    }

    public function getPage()
    {
        return $this->page;
    }


/* ---------- CREATE PAGINATION HTML ELEMENT DYNAMICALLY AND OUTPUT --------- */

    public function printPagination()
    {
        //Query DB to get total count of all products
        $query = "SELECT count(pro_ID) AS id
                  FROM product";
        //Store result as array
        $result = $this->database->get_results($query);
        //Access array for total products in DB
        $total = $result[0]['id'];
        //Create appropriate page range for number of products in DB
        $pages = ceil($total / $this->limit);
        $this->totalPages = $pages;
        $output = "";
        $output .= "<div class='row' data-aos='fade-up'>
                        <div class='col-md-12 text-center'>
                            <div class='site-block-27'>
                                <ul>
                                    <li><a href='#'>&lt;</a></li>";
                for($i = 1; $i <= $pages; $i++)
                {
                    if($i == $this->currentPage)
                    {
                        $output .= "<li class='active'><a href='shop.php?page=" . $i . "'><span>" . $i . "</span></a></li>";
                    }
                    else
                    {
                        $output .= "<li><a href='shop.php?page=" . $i . "'><span>" . $i . "</span></a></li>";
                    }
                }
                        $output .= "<li><a href='#'>&gt;</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>";

      return $output;
    }
}

?>