<?

/* -------------------------------------------------------------------------- */
/*        CLASS EXTENDS DB AND SERVES TO GET AND SET REVIEWS IN 410 DB        */
/* -------------------------------------------------------------------------- */

class Review extends DB
{

    private $pID;

    //Public function to construct review object
    public function __construct($id)
    {
        $this->pID = htmlspecialchars(trim($id));
        //Auto constructs DB object
        parent::__construct();
    }


    //Get review scores
    public function getScores($id)
    {
        //Load query variable
        $query = "SELECT rev_Score
                    FROM review
                    WHERE pro_ID = $id"; //Set limit to one to keep from searching DB
        //Fire query and store results
        $results = $this->getResults($query);
        return $results;
    }

    //Calculate sum for averages
    public function getAvg()
    {
        $avg = "";

        $id = $this->pID;
        //Load query variable
        $query = "SELECT AVG(rev_Score) AS avgScore 
                    FROM review
                    WHERE pro_ID = $id";

        $results = $this->get_results($query);
        //Open the array to get avg
        foreach($results as $result)
        {
            
            $avg = $result['avgScore'];
        }
        return $avg;
    }

    //Get all review information
    public function getFullReviewInfo()
    {
        $id = $this->pID;
        //Load query variable
        $query = "SELECT cus_FirstName fname, rev_Score score, rev_Detail deets
                    FROM review t1
                    INNER JOIN customer t2 ON t1.cus_ID = t2.cus_ID
                    WHERE pro_ID = $id";

        //Fire query and store results
        $results = $this->get_results($query);
        return $results;
    }

    //Print rating information
    public function printAvgRating($count)
    {
        //Variable to store output to return
        $output = "";

        if($count > 0)
        {
            $avg = $this->getAvg();
            $output = "<p class='col-6 reviewSection'><strong class='text-primary h5'>
                        <span id='rating'>" . number_format($avg, 2) ."</span> / 5 </strong>
            <i class='fa fa-star single'></i><span class='ratingCount'>" . $count . "</span>  Ratings</p>";
        }
        else
        {
            $output .= "<p class='col-6 reviewSection'><strong class='text-primary h5'>Product Not Rated</p>";
        }

        return $output;
    }

    //Print product reviews method
    public function printReview($count)
    {
        $output = "";

        if($count > 0)
        {
            //Call class method
            $reviews = $this->getFullReviewInfo();
            //Process for return
            foreach($reviews as $review)
              {
                $output .= "<h2 class='reviewHeader'>Product Reviews</h2>
                                <div class='container reviewContainer'>
                                    <div class='row justify-content-left fNameDiv'><h5 class='fname'>" . $review['fname'] . "</h5></div>
                                    <div class='row justify-content-left scoreDiv'><p class='pScore' data-rating='" . $review['score'] . "'></p></div>
                                    <div class='row deetsDiv'><p class='deets'>" . $review['deets'] . "</p></div>
                                </div>";
              }
        }
        else 
            $output .= "<h2 class='reviewHeader'>No Product Reviews...Yet</h2>";
        

        return $output;
    }


}



?>

