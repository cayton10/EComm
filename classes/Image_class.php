<?

/* -------------------------------------------------------------------------- */
/*                 CLASS USED FOR RETURNING APPROPRIATE IMAGE                 */
/* -------------------------------------------------------------------------- */

class Image
{

    //Searches directory for image based on passed parameter, returns appropriate
    //image name
    static function getImage($prodID)
    {
        //Set image name
        $image = PATH_TO_IMAGES . $prodID . '_1';
             if(file_exists($image . ".jpg"))
            {
              $printImage = $image . ".jpg";
            }
            else
              $printImage = PATH_TO_IMAGES . "noimage.jpg";
        
        //Return the image
        return $printImage;
    }
}

?>