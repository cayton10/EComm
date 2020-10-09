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

        //For the reviewer, on change event, store the value of the star in 
        //var ratings
        $('.scoreDiv').starrr().on('starrr:change', function (event, value) {
            //Add the value of the rating as a data field for reading upon submission
            $('#stars').attr('data-rate', value);
        });
    });

/* ------------------------- SUBMIT REVIEW AJAX CALL ------------------------ */
    //On submit event 
    //NOTE: $(#element).submit() is deprecated
    $('#reviewSubmit').on('click', function(event){
        event.preventDefault();//Prevent default action

        //Check if a rating has been selected
        if($('#stars').data('rate') == null)
        {
            alert("FILL THIS OUT");
            return
        }
        //Assign appropriate variables to send via ajax
        prodID = $('#reviewForm').attr('value');
        rating = $('#stars').data('rate');
        reviewDetail = $('#reviewDetail').val();

        $.ajax({
            url: 'ajax/saveRatings.php',
            type: 'POST',
            data: 
            {
                prodID: prodID,
                rating: rating,
                reviewDetail: reviewDetail
            },
            dataType: 'JSON',
            success: function(response)
            {
                if(response.success)
                {
                    alert(response)
                    console.log(response);
                }
                else
                    console.log(response);
            }
        })

        alert(reviewDetail);

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