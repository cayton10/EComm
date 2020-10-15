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

    static function getImageAjax($prodID)
    {
      //Set image name
      $relativePath = '../../../products/' . $prodID . '_1'; //Since the above static method is called in a directory above,
                                                              //The path to images won't work from where we're calling in ajax
                                                              //Directory.
      //Needed path
      $image = '../../products/' . $prodID . '_1';

        if(file_exists($relativePath . ".jpg"))
        {
          $printImage = $image . ".jpg";
        }
        else
          $printImage = '../../products/' . "noimage.jpg";

        //return image
        return $printImage;
    }
}

?>