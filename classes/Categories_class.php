<?
//include the DB_class definition
require_once('../config/config.php');
require_once('DB_class.php');

//Create category class to inherit database class
class Category extends DB_class
{
    //Database private member variable
    private $database;

    //Constructor?
    public function __construct()
    {
        $this->database = DB::getInstance();
    }
    //Public to retrieve categories
    
    /**
     * Retrieve results of a standard query
     */
    public function getCategories()
    {
        $query = "SELECT * FROM category
                ORDER BY cat_Name ASC";
        $results = $this->$database->get_results( $query );
        //foreach to process returned categories and ouput as buttons
        foreach( $results as $row )
        {
            echo "<div class='dropright'>
                <button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                </button>      
            </div>"
        }
    }
}
?>
<!--
<div class="dropright">
    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="catName">Category</span>
    </button>
    <div class="dropdown-menu">
        <ul class='list-unstyled mb-0'>
        <li><a href='#' class='p-2'>All Products</a></li>
        <li><a href='#' class='p-2'>SubCat A</a></li>
        <li><a href='#' class='p-2'>SubCat B</a></li>
        <li><a href='#' class='p-2'>SubCat C</a></li>
        </ul>
    </div>
</div>

-->