//This file will serve as my custom jQuery commands file
$(document).ready(function(){

    //Get full year and append to span
    $('#year').append(new Date().getFullYear());

    //Detail item carousel
    //Activate carousel
    $('#productCarousel').carousel();

    //Add this class to dynamically generated owl-item for formatting
    $('.owl-item').addClass('owlProdContainer');

    //Hide search results div on load
    $('#searchResults').hide();


/* -------------------------------------------------------------------------- */
/*                      INITIALIZE JQUERY STARRR LIBRARY                      */
/* -------------------------------------------------------------------------- */

    $(function() {
        //For read ratings, set readOnly to true
        $('.readRating').starrr({
            readOnly: true,
        });
        $('.readRating > a').removeAttr('href');
    });

/* -------------- DISPLAY RATINGS FROM REVIEWS AS FILLED STARS -------------- */

    var ratings = document.getElementsByClassName('pScore');
    for(var a = 0; a < ratings.length; a++)
    {
        $(ratings[a]).starrr({
            readOnly: true,
            rating: ratings[a].getAttribute("data-rating")
        });
        $('.pScore > a').removeAttr('href');
    }


/* -------------------------------------------------------------------------- */
/*            Function for dynamic searching in header.php on keyup           */
/* -------------------------------------------------------------------------- */

    $('#search').keyup(function()
    {
        //if no content in search bar, hide search results
        if($(this).val() == "")
            $('#searchResults').fadeOut('150');
        else
        {
            //Provide a check for string length... limit ajax calls
            if($(this).val().length < 2)
            return;
            //Once check is met, fire ajax call
            else
            {
                $.ajax(
                    {
                        url: "ajax/search.php",
                        data: {"search": $('#search').val()},
                        method: "GET",
                        dataType: "json",
                        success: function(data)
                        {   
                            var output = "";
                            var prevManu = "";
                            $.each(data, function(i, product) {
                                
                                //Format output and provide links to products
                                if(product.Manu != prevManu)
                                {   //Formatting for manufacturer
                                    output += "<span class='manufact'>" + product.Manu + "</span>";
                                }
                                //Formatting for manufacturer's item
                                output += "<span class='prodName'><a href='shop-single.php?id=" + product.ID + "&name=" + product.Title + "'>" + product.Title + "</a></span>";
                                
                                //Set prevManu to Manu
                                prevManu = product.Manu;//Picked up some formatting tricks from a tut vid
                                                        //I saved from an old professor I had :)
                            });
                            //Tell user if string doesn't match anything in DB
                            if(output.length === 0)
                            {
                                output = "Please Refine Your Search";
                            }

                            $('#searchResults').html(output);
                            $('#searchResults').fadeIn('200');
                        }
                    }
                )
            }
        }
    });

});