//This file will serve as my custom jQuery commands file
$(document).ready(function(){

    $('#ratingError').hide();
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
        $('#starrrs').starrr().on('starrr:change', function (event, value) {
            //Add the value of the rating as a data field for reading upon submission
            $('#stars').attr('data-rate', value);
        });

/* -------------- DISPLAY RATINGS FROM REVIEWS AS FILLED STARS -------------- */

        //For all other star ratings in comments, remove functionality
        var ratings = document.getElementsByClassName('pScore');
        for(var a = 0; a < ratings.length; a++)
        {
            $(ratings[a]).starrr({
                readOnly: true,
                rating: ratings[a].getAttribute("data-rating")
            });
            $('.pScore > a').removeAttr('href');
        }
    });




/* -------------------------------------------------------------------------- */
/*                           SUBMIT REVIEW AJAX CALL                          */
/* -------------------------------------------------------------------------- */

    //On submit event 
    //NOTE: $(#element).submit() is deprecated
    $('#reviewSubmit').on('click', function(event){
        event.preventDefault();//Prevent default action

        //Check if a rating has been selected
        if($('#stars').data('rate') == null)
        {
            $('#ratingError').slideDown(200);
            return
        }
        //Assign appropriate variables to send via ajax
        prodID = $('#reviewForm').attr('value');
        rating = $('#stars').data('rate');
        reviewDetail = $('#reviewDetail').val();
        console.log(rating);
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
                if(response == 1)
                {

/* --------------- SET APPROPRIATE VALUES IN MAIN PRODUCT CARD -------------- */
                    //Declare variables for calculation
                    var newAvg;
                    var total;
                    //Get value of rating count
                    var ratingCount = parseInt($('.ratingCount').text());
                    //Get current rating average
                    var previousRating = parseFloat($('#rating').text());

/* -------------------- If no ratings have been provided -------------------- */

                    if(isNaN(previousRating) || isNaN(ratingCount))
                    {
                        
                        //Since it wasn't a number, that means we have no ratings... This is the first one
                        ratingCount = 1;
                        previousRating = 1;
                        //remove the text that says "no product ratings"
                        $('#rating').text('');
                        $('.ratingCount').text(ratingCount);
                        //Set new avg variable
                        newAvg = rating;
                        //Set rating to string

                        //Set appropriate values
                        $('#rating').text(newAvg.toFixed(2));
                        $('#rating').append(' / 5');
                        $('.ratingCount').text(ratingCount);
                        $('.ratingCount').append(' Ratings');
                        $('#reviewHeader').text('Product Reviews');
                    }
                    else
                    {
                        total = (previousRating * ratingCount) + rating;

                        //Increment rating
                        ratingCount++;
                        newAvg = total / ratingCount;

                        newAvg = parseFloat(newAvg.toFixed(2));
                        //Turn our values back into strings for output
                        ratingCount = ratingCount.toString();

                        newAvg = newAvg.toString();
                        $('.ratingCount').text(ratingCount);
                        $('#rating').text(newAvg);
                    }

                    console.log(rating);
                    var output = "<div class='container reviewContainer'>" + 
                                    "<div class='row justify-content-left fNameDiv'>" + 
                                        "<h5 class='fname'>PlaceHolder" + "</h5>" + 
                                        "</div>" +
                                    "<div class='row justify-content-left scoreDiv'>" + 
                                    "<p class='pScore' data-rating='" + rating + "'>" + rating + " / 5 <i class='fa fa-star single'></i></p></div>" +
                                    "<div class='row deetsDiv'><p class='deets'>" + reviewDetail + "</p></div>" +
                                "</div>";
                    //So, without reloading the page, I can't get the star rating plugin to work
                    //with my php class functions to populate it.... :'( this is a major bummer.
                    //Any suggestions?
                    //Append the above into the reviews container under the header text
                    //$('#reviewHeader').append(output);
                    $(output).hide().appendTo('#reviewHeader').fadeIn(300);

                    //Clear the text field
                    $('#reviewDetail').val('');

                    //Cheap shot fix to not let a user continually review w/o reloading page.
                    $('#reviewSubmit').prop('disabled', true);

                    
                    console.log(response);
                }
                else
                {
                    console.log("failed");
                    console.log(response);
                }
            }
        })
    });


/* -------------------------------------------------------------------------- */
/*                       AJAX FUNCTION FOR PRICE FILTER

    this comment header marks the beginning of all filter function jquery
    for this file. It is a lot to process. I'm sure I can go back and
    refactor this code. I imagine I could probably store the similar
    ajax calls within a single function and pass paramaters depending
    on which filter is need (manufacturer or price). Being that I built
    these filters successively, I did not anticipate them to be so similar.
    May go back and refactor when I have some free time (which does not exist).
/* -------------------------------------------------------------------------- */

//Declare max and min variables to use in other functions
var minHandle;
var maxHandle;

//Initialize the slider
var siteSliderRange = function() {
    //Set min and max values for slider
    minHandle = 0;
    maxHandle = Math.ceil($('#slider-range').data('max'));
    var value = parseInt($('#slider-range').data('value'))
    var type = $('#slider-range').data('type');
    var step = ((maxHandle - minHandle) / 20);


    //Set slider specifics 
    $( "#slider-range" ).slider({
    range: true,
    min: minHandle,
    max: maxHandle,
    step: step,
    values: [ minHandle, maxHandle],
    slide: function( event, ui ) {

        //Get values as they change
        minHandle = ui.values[0];
        maxHandle = ui.values[1];

    $( "#amount" ).val( "$" + ui.values[ 0 ].toFixed(0) + " - $" + ui.values[ 1 ].toFixed(0) );

    //Stringify the information to send
    infotosend = JSON.stringify({min: minHandle, max: maxHandle, value: value, type: type, manu: manuName})
    //Put together AJAX request to send server side,
    
/* -------------- MAKE APPROPRIATE AJAX CALL WITH OTHER FILTER -------------- */
    if(manuName === 'all')
    {
        $.ajax({
            url: 'ajax/filterPrices.php',
            type: 'POST',
            data: {data: infotosend},
            dataType: 'json',
            
            success: function (products){
    
    
                //Remove our original product containers
                $('.prodContainer').remove();
    
                //Remove our original manufacturer checkboxes
                $('.manufact').remove();
                //Set previous manu var for manufacturer filter
                var previousManu;  
                //Print our new product cards
                $.each(products, function(key, value)
                {
                    var ID = value.ID;
                    var title = value.title;
                    var price = value.price;
                    var manu = value.manu;
                    var avgScore = value.avgScore;
                    var image = value.image;
    
                    
                    $('#cardContainer').append
                    
                    ("<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up' data-manu=" + manu + ">\
                        <div class='block-4 text-center border innerProdContainer'>\
                            <figure class='block-4-image'>\
                            <a href='shop-single.php?id=" + ID + "&name='" + title + "'><img src='" + image + "' alt='Image placeholder' class='img-fluid prods'></a>\
                            </figure>\
                        <div class='block-4-text p-4 prodInfo'>\
                            <h3><a href='shop-single.php?id=" + ID + "&name=" + title + "'>" + manu + "</a></h3>\
                            <p class='mb-0'>" + title + "</p>\
                            <p class='text-primary font-weight-bold'>" + '$' + price + "</p>\
                        </div>\
                        <div class='avgRating'>" + avgScore + "</div>\
                        </div>\
                    </div>")
    
    
    /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */
    
                    if(manu != previousManu)
                    {
                        $('.manufactDiv').append
    
                        ("<label for='s_sm' class='d-flex manufact'>\
                            <input type='checkbox' class='mr-2 mt-1 manuCheck' value='" + manu + "'> <span class='text-black manCheckBox'>" + manu + "</span>\
                        </label>");
                        previousManu = manu;
                    }
                    
                    
                });
            },
            error: function (xhr, error) {
                console.log(error);
            }
            
        });
    }
    else
    {
        $.ajax({
            url: 'ajax/filterPrices.php',
            type: 'POST',
            data: {data: infotosend},
            dataType: 'json',
            
            success: function (products){
    
    
                //Remove our original product containers
                $('.prodContainer').remove();
    
                //Remove our original manufacturer checkboxes
                $('.manufact').remove();

                
                //Print our new product cards
                $.each(products, function(key, value)
                {
                    var ID = value.ID;
                    var title = value.title;
                    var price = value.price;
                    var manu = value.manu;
                    var avgScore = value.avgScore;
                    var image = value.image;
    
                    
                    $('#cardContainer').append
                    
                    ("<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up' data-manu=" + manu + ">\
                        <div class='block-4 text-center border innerProdContainer'>\
                            <figure class='block-4-image'>\
                            <a href='shop-single.php?id=" + ID + "&name='" + title + "'><img src='" + image + "' alt='Image placeholder' class='img-fluid prods'></a>\
                            </figure>\
                        <div class='block-4-text p-4 prodInfo'>\
                            <h3><a href='shop-single.php?id=" + ID + "&name=" + title + "'>" + manu + "</a></h3>\
                            <p class='mb-0'>" + title + "</p>\
                            <p class='text-primary font-weight-bold'>" + '$' + price + "</p>\
                        </div>\
                        <div class='avgRating'>" + avgScore + "</div>\
                        </div>\
                    </div>")
                    
                });

                /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */
    
                    $('.manufactDiv').append

                    ("<label for='s_sm' class='d-flex manufact'>\
                        <input type='checkbox' class='mr-2 mt-1 manuCheck' value='" + manuName + "' checked> <span class='text-black manCheckBox'>" + manuName + "</span>\
                    </label>");

            },
            error: function (xhr, error) {
                console.log(error);
            }
            
        });
    }
    
  }
});
$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
  " - $" + $( "#slider-range" ).slider( "values", 1 ) );


    


};
siteSliderRange();



/* -------------------------------------------------------------------------- */
/*                   AJAX FUNCTIONS FOR MANUFACTURER FILTER                   */
/* -------------------------------------------------------------------------- */

//Set default manu name to 1.) pass to manufacturer filter, and two pass back to price filter
var manuName = 'all';

//Bind event to document since .manuCheck is dynamically created element

$(document).on('click', '.manuCheck', function() {

    var value = $('#manufactFilter').data('value');
    var type = $('#manufactFilter').data('type');

    //Logic to execute appropriate code based on checkbox state
    if($('input.manuCheck').is(':checked'))
    {
        //Set up the data to send via ajax
        manuName = $(this).attr('value');
        maxPrice = maxHandle;
        minPrice = minHandle;
    
        //Make our ajax call
        $.ajax({
            url: 'ajax/filterManu.php',
            data: {
                name: manuName,
                maxPrice: maxPrice,
                minPrice: minPrice,
                type: type,
                value: value
            },
            dataType: 'JSON',
            method: 'POST',
    
            success: function(products)
            {
                //Remove our product cards and manufacturer checkboxes
                $('.prodContainer').remove();
                $('.manufact').remove();

                var previousManu

                $.each(products, function(key, value)
                {
                    var ID = value.ID;
                    var title = value.title;
                    var price = value.price;
                    var manu = value.manu;
                    var avgScore = value.avgScore;
                    var image = value.image;


                    
                    $('#cardContainer').append
                    
                    ("<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up' data-manu=" + manu + ">\
                        <div class='block-4 text-center border innerProdContainer'>\
                            <figure class='block-4-image'>\
                            <a href='shop-single.php?id=" + ID + "&name='" + title + "'><img src='" + image + "' alt='Image placeholder' class='img-fluid prods'></a>\
                            </figure>\
                        <div class='block-4-text p-4 prodInfo'>\
                            <h3><a href='shop-single.php?id=" + ID + "&name=" + title + "'>" + manu + "</a></h3>\
                            <p class='mb-0'>" + title + "</p>\
                            <p class='text-primary font-weight-bold'>" + '$' + price + "</p>\
                        </div>\
                        <div class='avgRating'>" + avgScore + "</div>\
                        </div>\
                    </div>")


    /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */

                    if(manu != previousManu)
                    {
                        $('.manufactDiv').append

                        ("<label for='s_sm' class='d-flex manufact'>\
                            <input type='checkbox' class='mr-2 mt-1 manuCheck' value='" + manu + "' checked> <span class='text-black manCheckBox'>" + manu + "</span>\
                        </label>");
                        previousManu = manu;
                    }             
                });
            }
        });
    }

/* ----------- IF WE WANT TO GET OUR MANUFACTURER CHECKBOXES BACK ----------- */

    else
    {
        //Set up the data to send via ajax
        manuName = 'all';
        maxPrice = maxHandle;
        minPrice = minHandle;
    
        //Make our ajax call
        $.ajax({
            url: 'ajax/filterManu.php',
            data: {
                name: manuName,
                maxPrice: maxPrice,
                minPrice: minPrice,
                type: type,
                value: value,
            },
            dataType: 'JSON',
            method: 'POST',
    
            success: function(products)
            {
                //Remove our product cards and manufacturer checkboxes
                $('.prodContainer').remove();
                $('.manufact').remove();

                var previousManu
                var i = 0;

                $.each(products, function(key, value)
                {
                    var ID = value.ID;
                    var title = value.title;
                    var price = value.price;
                    var manu = value.manu;
                    var avgScore = value.avgScore;
                    var image = value.image;


                    
                    $('#cardContainer').append
                    
                    ("<div class='col-sm-6 col-lg-4 mb-4 prodContainer' data-aos='fade-up' data-manu=" + manu + ">\
                        <div class='block-4 text-center border innerProdContainer'>\
                            <figure class='block-4-image'>\
                            <a href='shop-single.php?id=" + ID + "&name='" + title + "'><img src='" + image + "' alt='Image placeholder' class='img-fluid prods'></a>\
                            </figure>\
                        <div class='block-4-text p-4 prodInfo'>\
                            <h3><a href='shop-single.php?id=" + ID + "&name=" + title + "'>" + manu + "</a></h3>\
                            <p class='mb-0'>" + title + "</p>\
                            <p class='text-primary font-weight-bold'>" + '$' + price + "</p>\
                        </div>\
                        <div class='avgRating'>" + avgScore + "</div>\
                        </div>\
                    </div>")


    /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */

                    if(manu != previousManu)
                    {
                        $('.manufactDiv').append

                        ("<label for='s_sm' class='d-flex manufact'>\
                            <input type='checkbox' class='mr-2 mt-1 manuCheck' value='" + manu + "'> <span class='text-black manCheckBox'>" + manu + "</span>\
                        </label>");
                        previousManu = manu;
                    }             
                });
            }
        });
    }

});





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




/* -------------------------------------------------------------------------- */
/*             LIMIT RESULTS OF PRODUCT CARDS SHOWN FOR PAGINATION            */
/* -------------------------------------------------------------------------- */

//Store the number of containers returned
totalItems = $('.prodContainer').length



/* ----------------------------- STOLE THIS FROM ---------------------------- */
/* -------- https://css-tricks.com/snippets/jquery/smooth-scrolling/ -------- */

        // Select all links with hashes
        $('a[href*="#"]')
        // Remove links that don't actually link to anything
        .not('[href="#"]')
        .not('[href="#0"]')
        .not('[href="#productCarousel"]')
        .click(function(event) {
        // On-page links
        if (
            location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
            && 
            location.hostname == this.hostname
        ) {
            // Figure out element to scroll to
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            // Does a scroll target exist?
            if (target.length) {
            // Only prevent default if animation is actually gonna happen
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 1000, function() {
                // Callback after animation
                // Must change focus!
                var $target = $(target);
                $target.focus();
                if ($target.is(":focus")) { // Checking if the target was focused
                return false;
                } else {
                $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
                $target.focus(); // Set focus again
                };
            });
            }
        }
        });

});