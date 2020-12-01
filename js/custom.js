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


    //Hide the confirm password field unless registering a new user
    $('#confirmPWDiv').hide();
    $('.checkoutModalError').hide();

    //Hide the shipping div on page load
    $('.orderSummaryShipping').hide();

    //Hide the place order button on page load
    $('#placeOrderButtonDiv').hide();

    //Hide the confirm payment button on page load
    $('#confirmPaymentDiv').hide();

    //Calculate the shipping cost on page load
    var total = $('#subTotal').html();
    
    $('#orderTotal').html(total);


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
        var prodID = $('#reviewForm').attr('value');
        var rating = $('#stars').data('rate');
        var reviewDetail = $('#reviewDetail').val();

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
/*                            SORTING FUNCTIONALITY                           */
/* -------------------------------------------------------------------------- */
$('.form-check-input').on('click', function(){

    //Log checkbox value if checked
    var manufactCheck = ($("input[type='checkbox']").val());
    //Remove checked attribute from previous radio if exists
    $("input[type='radio'][name='sort']").removeAttr('checked');

    //Find the manufacturer and fill the checkbox again
    //For some reason it removes it every time the sorting radios are clicked
    var string = "input[type=checkbox][value=" + manufactCheck + "]";
    $(string).attr('checked', true);
    
    $(this).attr('checked', true);

    

    //Store the information we need to run new query
    sortType = $(this).data('sort');
    sort = $(this).val();
    
    
    //Grab the value and type we need
    var type = $('#slider-range').data('type');
    var value = parseInt($('#slider-range').data('value'));
    //Store all of the sorting information we need
    var infotosend = JSON.stringify(
        {
            min: minHandle, 
            max: maxHandle, 
            value: value, 
            type: type, 
            manu: manuName, 
            sortType: sortType,
            sort: sort
        });

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
                
                //Store manufacturers under these conditions in an array
                var manufacturerArray = [];
                $.each(products, function(key, value)
                {
                    manufacturerArray.push(value.manu);
                });

                //Sort the array for processing
                manufacturerArray.sort();

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
                        <button class='quickViewAccess btn btn-primary' data-id='" + ID + "'>Quick View</button>\
                        </div>\
                    </div>");
                });

                /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */
                $.each(manufacturerArray, function(key, value)
                {
                    
                    var manu = value;

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

         //Get values for sorting type if exists
        var sort = "";
        var sortType = "";
        var selected = $("input[type='radio'][name='sort']:checked");

        if(selected.length > 0)
        {
            sort = selected.val();
            sortType = selected.data('sort');
        }

    $( "#amount" ).val( "$" + ui.values[ 0 ].toFixed(0) + " - $" + ui.values[ 1 ].toFixed(0) );

    //Stringify the information to send
    var infotosend = JSON.stringify(
        {
            min: minHandle, 
            max: maxHandle, 
            value: value, 
            type: type, 
            manu: manuName, 
            sortType: sortType, 
            sort: sort
        });
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
                
                //Store manufacturers under these conditions in an array
                var manufacturerArray = [];
                $.each(products, function(key, value)
                {
                    manufacturerArray.push(value.manu);
                });

                //Sort the array for processing
                manufacturerArray.sort();

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
                        <button class='quickViewAccess btn btn-primary' data-id='" + ID + "'>Quick View</button>\
                        </div>\
                    </div>");
                });

                /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */
                $.each(manufacturerArray, function(key, value)
                {
                    
                    var manu = value;

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
                        <button class='quickViewAccess btn btn-primary' data-id='" + ID + "'>Quick View</button>\
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

    //Get values for sorting type if exists
    var sort = "";
    var sortType = "";
    var selected = $("input[type='radio'][name='sort']:checked");

    if(selected.length > 0)
    {
        sort = selected.val();
        sortType = selected.data('sort');
    }

    //Logic to execute appropriate code based on checkbox state
    if($('input.manuCheck').is(':checked'))
    {
        //Disable sorting by manufacturer
        $('.sortedManu').prop('disabled', true);
        //Set up the data to send via ajax
        manuName = $(this).attr('value');
        var maxPrice = maxHandle;
        var minPrice = minHandle;
    
        //Make our ajax call
        $.ajax({
            url: 'ajax/filterManu.php',
            data: {
                name: manuName,
                maxPrice: maxPrice,
                minPrice: minPrice,
                type: type,
                value: value,
                sortType: sortType,
                sort: sort
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
                        <button class='quickViewAccess btn btn-primary' data-id='" + ID + "'>Quick View</button>\
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

        //Disable sorting by manufacturer
        $('.sortedManu').removeAttr('disabled');
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
                sortType: sortType,
                sort: sort
            },
            dataType: 'JSON',
            method: 'POST',
    
            success: function(products)
            {
                //Remove our product cards and manufacturer checkboxes
                $('.prodContainer').remove();
                $('.manufact').remove();

                var previousManu; 
                
                //Store manufacturers under these conditions in an array
                var manufacturerArray = [];
                $.each(products, function(key, value)
                {
                    manufacturerArray.push(value.manu);
                });

                //Sort the array for processing
                manufacturerArray.sort();

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
                        <button class='quickViewAccess btn btn-primary' data-id='" + ID + "'>Quick View</button>\
                        </div>\
                    </div>")            
                });

                /* --------------- APPEND APPROPRIATE MANUFACTURER CHECKBOXES --------------- */
                $.each(manufacturerArray, function(key, value)
                {
                    
                    var manu = value;

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
/*                    QUANTITY CHANGE ARROWS ON SHOP.SINGLE                   */
/* -------------------------------------------------------------------------- */
$('#addQty').on('click', function(){
    //Read current input qty value
    var currentVal = $('#inputQty').val();
    //Update the value of the input field for sending to DB
    $('#inputQty').val(currentVal++);
});



/* -------------------------------------------------------------------------- */
/*               PROCESS OPTION PRICING TO REFLECT DYNAMIC PRICE              */
/* -------------------------------------------------------------------------- */


let originalPrice;

var selectionIterator = 0;

$(document).on('change', '.optionElements', function(){


    //Hide error handling if it's there
    if($('#modalMessage').is(':visible'))
    {
        $('#modalMessage').slideUp(200);
    }


    //Store the original price
    if(selectionIterator == 0)
    {
        originalPrice = $('#prodPrice').html();
        originalPrice = originalPrice.replace(',', '');         //Strip commas
        originalPrice = parseFloat(originalPrice);     //Had to do this for dynamically created spans
    }
    

    //Since we've made a selection, count that iterator
    selectionIterator++;

    var currentPrice = parseFloat($('#prodPrice').html());


    //Declare an array to store price information for options
    var addedCost = [];
    //Grab the values in any present <select> fields
    $('.optionElements').each(function(index, value) {

        value = $(this).children('option:selected').data('charge');
        addedCost.push({cost: value});

    });

    var totalAdded = 0;

    
    $.each(addedCost, function(index, value){

        //Parse this for every iteration because it may reset
        currentPrice = parseFloat(currentPrice);

        totalAdded += parseFloat(value.cost);
        

        if(value.cost != 0)
        {
            var newCost = originalPrice + totalAdded;
            newCost = parseFloat(newCost);
            //Format the price

            newCost = addCommas(newCost.toFixed(2));

            //Output the new price for user
            $('#prodPrice').html(newCost);
        }
        else
        {
            currentPrice = originalPrice + totalAdded;
            currentPrice = addCommas(currentPrice.toFixed(2));
            $('#prodPrice').html(currentPrice);
        }
        
    });
    
});

/* -------------------------------------------------------------------------- */
/*                       ADD ITEMS TO CART ON QUICKVIEW                       */
/* -------------------------------------------------------------------------- */
$(document).on('click', '#addToCartQuick', function(e){

    e.preventDefault();
    //Grab qty
    var quantity = parseInt($('#inputQty').val());
    //Grab the productID 
    var prodID = parseInt($('#inputQty').data('id'));

    if(quantity < 1)
    {
        return;
    }


    //Declare an array to store price information for options
    var selectValues = [];
    //Grab the values in any present <select> fields
    $('.optionElements').each(function(index, value) {

        value = $(this).children('option:selected').data('id');
        selectValues.push({id: value});

    });



    var checkValue;

    $.each(selectValues, function(key, value){//Iterate over all of the 'values' from options
    
        if(value.id == 'default')                  //If there are any defaults, set boolean
        {
            checkValue = false;//If we hit any default then return from loop w/ false checkValue
            return;
        }
    });

/* --------------------- PUT IN SOME ERROR HANDLING HERE -------------------- */

    if(checkValue == false)
    {
        $('#modalMessage').html(
            "<p class='text-primary'>Please select options for this product</p>"
        );
        $('#modalMessage').attr('display: flex');

        $('#modalMessage').slideDown(300);
        return;
    }

    //Stringify our array to JSON format
    var jsonString = JSON.stringify(selectValues);

    //Make our ajax call, lick stamp and send it
    $.ajax(
        {
            url: 'ajax/addToCart.php',
            data:
            {
                ID: prodID,
                quantity: quantity,
                option: jsonString
            },
            dataType: 'JSON',
            method: 'POST',
            success: function(data)
            {
                if(data.message == "success")
                {
                    //Control flow for modal body output
                    var output = '';
                    if(quantity > 1)
                    {
                        output = quantity + ' items added to cart!';
                    }
                    else if(quantity == 1)
                    {
                        output = quantity + ' item added to cart!';
                    }

                    //Close the modal
                    $('#quickViewModal').modal('hide');

                    //Update item count in mini cart
                    $('#miniCartCount').text(data.cartQty);

                    //Present modal for user
                }
                else if(data.message == "lowQty")
                {
                    $('#modalMessage').html('');
                    $('#modalMessage').html(
                        "<p class='text-primary'>We may be running low on that item. Either reduce the quantity ordered or check back soon!</p>"
                    );
                    $('#modalMessage').attr('display: flex');
            
                    $('#modalMessage').slideDown(300);
                    $('#cartModal').modal({backdrop: 'static', keyboard: false});
                    
                }
                else if(data.message == "no bueno")
                {
                    $('#modalMessage').html('');
                    $('#modalMessage').html(
                        "<p class='text-primary'>You already have one of this item with options selected. If you would like to select a different option, please remove the item from your cart.</p>"
                    );
                    $('#modalMessage').attr('display: flex');

                    $('#modalMessage').slideDown(300);
                }
            },
            error(xhr, error)
            {
                alert(error);
            }
    });

    //Reset selection iterator
    selectionIterator = 0;
});

//Reset selection iterator on closing modal button
$(document).on('click', '#closeQuickView', function(){

    //reset iterator
    selectionIterator = 0;
})


/* -------------------------------------------------------------------------- */
/*                  DISABLE QTY CONTROLS IF OPTIONS AVAILABLE                 */
/* -------------------------------------------------------------------------- */
if($('.optionElements').length)
{
    $('#inputQty').prop('disabled', true);
    $('#removeQty').prop('disabled', true);
    $('#addQty').prop('disabled', true);
}




/* -------------------------------------------------------------------------- */
/*                         ADD ITEMS TO SHOPPING CART                         */
/* -------------------------------------------------------------------------- */

$('#addToCart').on('click', function(e){

    //Prevent default so we don't reload / exit page, etc
    e.preventDefault();
    //Grab our quantity
    var quantity = parseInt($('#inputQty').val());
    //Grab out productID
    var prodID = parseInt($('#reviewForm').attr('value'));
    
    
    //If they ain't buyin' we ain't workin'
    if(quantity < 1)
    {
        return;
    }


    //Declare an array to store price information for options
    var selectValues = [];
    //Grab the values in any present <select> fields
    $('.optionElements').each(function(index, value) {

        value = $(this).children('option:selected').data('id');
        selectValues.push({id: value});

    });



    var checkValue;
    $.each(selectValues, function(key, value){//Iterate over all of the 'values' from options
    
        if(value.id == 'default')                  //If there are any defaults, set boolean
        {
            checkValue = false;//If we hit one default then return from loop w/ false checkValue
            return;
        }
    });

    //Show appropriate message to user
    if(checkValue == false)
    {
        $('.optionElements').addClass('optionElementsError');
        //Hide the go to cart button
        $('#goToCart').hide();
        $('#cartModalTitle').html('Unsuccessful :(');
        $('#cartModalBody').html('Please select appropriate product options.');
        $('#cartModal').modal({backdrop: 'static', keyboard: false});

        //return from the add to cart function
        return;
    };

    //Else we made it through, so show the goToCart button if it's hidden
    if($('#goToCart').is(':hidden'))
    {
        $('#goToCart').show();
        $('.optionElements').removeClass('optionElementsError');
    }


    //Stringify our array to JSON format
    var jsonString = JSON.stringify(selectValues);

    //Make our ajax call, lick stamp and send it
    $.ajax(
        {
            url: 'ajax/addToCart.php',
            data:
            {
                quantity: quantity,
                ID: prodID,
                option: jsonString
            },
            dataType: 'JSON',
            method: 'POST',
            success: function(data)
            {
                if(data.message == "success")
                {
                    //Control flow for modal body output
                    var output = '';
                    if(quantity > 1)
                    {
                        output = quantity + ' items added to cart!';
                    }
                    else if(quantity == 1)
                    {
                        output = quantity + ' item added to cart!';
                    }

                    //Update the modal messaging
                    $('#cartModalTitle').html('Success!');
                    $('#cartModalBody').html(output);
                    $('#cartModal').modal({backdrop: 'static', keyboard: false});
                    //Update item count in mini cart
                    $('#miniCartCount').text(data.cartQty);

                    //Present modal for user
                }
                else if(data.message == "lowQty")
                {
                    $('#cartModalTitle').html('Unsuccessful :(');
                    $('#cartModalBody').html("We may be running low on that item. Either reduce the quantity ordered or check back soon!");
                    $('#cartModal').modal({backdrop: 'static', keyboard: false});
                    
                }
            },
            error(xhr, error)
            {
                alert(error);
            }
    });
});

/* -------------------------------------------------------------------------- */
/*                                  CART MATH                                 */
/* -------------------------------------------------------------------------- */
//On page load, grab all totalLine spans and sum them for cart total ouput
var sum = 0;

$('.totalLine').each(function(){
    sum += ($(this).data('total'));
});

//Format output
var formattedSum = addCommas(sum.toFixed(2));

//Set total on cart
$('#total').html(formattedSum);

/* -------------------------------------------------------------------------- */
/*                             CART QUANTITY LOGIC                            */
/* -------------------------------------------------------------------------- */
//Add quantity
$('.addQty').on('click', function(){

    //Store the incremented qty into a variable to use for updating prices
    var newQty = $('#quantity' + $(this).data('id')).val();

    //Increment the qty
    newQty++;
    
    //Grab item price and store as well
    var itemPrice = $('#singleItemPrice' + $(this).data('id')).data('price');
    
    //Calculate our new total for item line
    var newLineTotal = (newQty * itemPrice);

    //Store miniCart value
    var miniCartCount = parseInt($('#miniCartCount').html());
    //Increment the cartCount
    miniCartCount++;
    //Update miniCart;
    $('#miniCartCount').html(miniCartCount);
    

    var formatTotal = addCommas(newLineTotal.toFixed(2));
    //Update the lineTotal
    $('#totalLine' + $(this).data('id')).empty();
    $('#totalLine' + $(this).data('id')).html(formatTotal);

    //Update cart total sum
    var prevTotal = $('#total').html();
    prevTotal = prevTotal.replace(',','');
    prevTotal = parseFloat(prevTotal);
    itemPrice = parseFloat(itemPrice);
    //Reset the total
    $('#total').html('');
    var newTotal = prevTotal + itemPrice;
    var formatSum = addCommas(newTotal.toFixed(2));
    //Output the new cart total
    $('#total').text(formatSum);

    //Enable update cart button
    $('#updateCart').attr('disabled', false);
});

//Reduce quantity
$('.reduceQty').on('click', function(){

    //Store the incremented qty into a variable to use for updating prices
    var newQty = $('#quantity' + $(this).data('id')).val();

    //Decrement the qty
    newQty--;
    
    //Grab item price and store as well
    var itemPrice = $('#singleItemPrice' + $(this).data('id')).data('price');
    
    //Calculate our new total for item line
    var newLineTotal = (newQty * itemPrice);

    //Store miniCart value
    var miniCartCount = parseInt($('#miniCartCount').html());
    //Decrement the cartCount
    miniCartCount--;
    //Update miniCart;
    $('#miniCartCount').html(miniCartCount);

    var formatTotal = addCommas(newLineTotal.toFixed(2));
    //Update the lineTotal
    $('#totalLine' + $(this).data('id')).empty();
    $('#totalLine' + $(this).data('id')).html(formatTotal);

    //Update cart total sum
    var prevTotal = $('#total').html();
    prevTotal = prevTotal.replace(',','');
    prevTotal = parseFloat(prevTotal);
    itemPrice = parseFloat(itemPrice);
    //Reset the total
    $('#total').html('');
    var newTotal = prevTotal - itemPrice;
    var formatSum = addCommas(newTotal.toFixed(2));
    //Output the new cart total
    $('#total').text(formatSum);

    //Enable update cart button
    $('#updateCart').attr('disabled', false);
});

/* --------------------------- REMOVE ITEM BUTTON --------------------------- */

$('.removeItem').on('click', function(){
    var productID = $(this).data('id');
    
    //Store all the numbers we need to update the cart summary after removing item
    var totalLine = $('#totalLine' + productID).html();
    var prevTotal = $('#total').html();
    //Remove commas
    totalLine = totalLine.replace(',', '');
    prevTotal = prevTotal.replace(',', '');
    //Convert to float values
    totalLine = parseFloat(totalLine);
    prevTotal = parseFloat(prevTotal);
    //Update total
    $('#total').html('');
    var newTotal = prevTotal - totalLine;
    var formatSum = addCommas(newTotal.toFixed(2));

    //Store miniCart value
    var miniCartCount = parseInt($('#miniCartCount').html());
    let lineQty = $('#quantity' + $(this).data('id')).val();
    miniCartCount -= lineQty;
    //Update miniCart;
    $('#miniCartCount').html(miniCartCount);

    //Output the new cart total
    $('#total').text(formatSum);
    //Use the stored ID to remove the table row containing that product
    $('#productRow' + productID).fadeOut(300, function(){
        $(this).remove();
    });
    //Enable update cart button
    $('#updateCart').attr('disabled', false);
    
});


$('#updateCart').on('click', function(){

    //Store quantities and product IDs to pass in ajax call
    var productArray = [];

    $('.quantity').each(function(){
            productArray.push({
                id: $(this).data('id'),
                qty: $(this).val()
            });
    });

    var i = 0;

    
    
    //Declare all the indeces we need for object array to store options (if any)
    $.each(productArray, function(key, value)
    {
        
        $('.option' + value.id).each(function(){
            productArray[i]['option'] = [];
        });
        
        ++i;
    });

    //Reset iterator
    i = 0;
    
    //Now load the option array(s) we just initialized in previous loop
    $.each(productArray, function(key, value)
    {
        //Nested array iterator
        k = 0;

        //Load options in object array
        $('.option' + value.id).each(function(){
            productArray[i]['option'][k] = $(this).data('option');
            k++;
        });

        ++i;
    });


    //Stringify our array to JSON format
    var jsonString = JSON.stringify(productArray)
    //Send our array to php script for cart processing
    $.ajax(
    {
        url: 'ajax/updateCart.php',
        method: 'POST',
        data: {data: jsonString},
        dataType: 'JSON',

        success: function(data)
        {
            //Set our appropriate modal or div to display on success
            //Update the div w/ appropriate text
            $('#updateLabel').html('Success');
            $('#updateMessage').html('Your cart has been successfully updated.');
            $('#updateState').slideDown('slow').delay(2000).slideUp('slow');
            $('#updateCart').attr('disabled', true);
        },
        error: function(xhr, error)
        {
            $('#updateLable').html(error);
            $('#updateMessage').html('Cart could not be updated at this time.');
            $('#updateState').slideDown('slow').delay(2000).slideUp('slow');
            $('#updateCart').attr('disabled', true);
        }
    });
});

//Disable update cart button on page load
$('#updateCart').attr('disabled', true);



/* -------------------------------------------------------------------------- */
/*                         FANCY BOX INIT AND OPTIONS                         */
/* -------------------------------------------------------------------------- */
$('[data-fancybox="gallery"]').fancybox({
    //Fancybox options
    loop: false,
    gutter: 10,
    arrows: true,
    inforbar: true,
    idleTime: 3,
    transitionEffect: 'slide',
    spinnerTpl:'<div class="fancybox-loading"></div>',
    autoScale: true,
    centerOnScroll: true
});


/* -------------------------------------------------------------------------- */
/*                            QUICK VIEW FUNCTIONS                            */
/* -------------------------------------------------------------------------- */

var that;//JS forgets what $(this) is when we get into the setTimeout function so.... we'll help it remember
var timer;//Store the timer so we can clear it


//Document mouseenter takes care of both static and dynamically created div content
$(document).on('mouseenter', '.innerProdContainer', function(){

    that = this;
    timer = setTimeout(function(){
        $(that).children('.quickViewAccess').slideDown(300);
    }, 1000);
    
});

//When mouse leaves
$(document).on('mouseleave', '.innerProdContainer', function(){
    clearTimeout(timer);
    $(this).children('.quickViewAccess').slideUp(300);
});



//Click function to show modal containing product info
$(document).on('click', '.quickViewAccess', function()
{

    //grab the product ID from the quickview button
    var quickID = { "ID": $(this).data('id') };


    


    $.ajax({
        url: 'ajax/quickView.php',
        method: 'POST',
        data: quickID,
        dataType: 'JSON',
        success: function(data){

            //Process everything that comes back and output into modal
            $.each(data, function(key, value){

                //Set manufacturer title
                $('#quickViewTitle').html(value.manu);

                //Set link for 'more details' button
                $('#moreDetails').attr('href', "shop-single.php?id=" + value.ID + "&name=" + value.title);
                //Format our price for output
                price = addCommas(value.price);

                var iterator = 0;
                //Start output of quickViewBody
                $('#quickViewBody').html(
                    "<div class='row'>\
                        <div class='col-md-6'>\
                            <img class='d-block w-100' src='" + value.image + "' alt='" + value.title + "'>\
                        </div>\
                    \
                        <div class='col-md-6 productDetails' mt-5 mt-md-0>\
                            <h4 class='text-black'>" + value.title + "</h4>\
                            <p class='mb-4'>" + value.descr + "</p>\
                            <div class='row-fluid priceRating'>\
                                <p class='col-6 priceSection'>\
                                    <strong class='text-primary h5'>$<span id='prodPrice'>" + price + "</span></strong>\
                                </p>"
                                    + value.avgScore + 
                            "</div>\
                            <div class='mb-q d-flex selectGroupContainer' id='selectGroupContainer'>\
                            </div>\
                        </div>");
         
                        
                        //Append each option group returned from our ajax call to the selectGroupContainer
                        $.each(value.opt_Group, function(key, value){

                            $('#selectGroupContainer').append(
                                "<div class='form-group selectGroup'>\
                                    <label for='" + value.opt_Name + " Select class='optionLabel'>" + value.opt_Name + "</label>\
                                    <select class='form-control optionElements' id='" + value.opt_Group + "'>\
                                        <option selected value='default' data-id='default' data-charge='0'>" + value.opt_Name + "</size>\
                                    </select>\
                                </div>");
                        });

                        //Append the available options to the appropriate select field
                        $.each(value.opt_Value, function(key, value){
                            $.each(value, function(k, v)
                            {

                                $('#' + v.opt_Group).append(
                                    "<option value='" + v.opt_Value + "' data-id='" + v.opt_ID + "' data-charge='" + v.opt_Price + "'>" + v.opt_Value + "</option>"); 
                            });
                        });

                        //Append the increment / decrement select element
                        $('.productDetails').append(
                        "<div class='modal-message' id='modalMessage'></div>\
                            <div class='mb-5'>\
                                <div class='input-group mb-3' style='max-width: 120px;'>\
                                    <div class='input-group-prepend'>\
                                        <button id='removeQty' class='btn btn-outline-primary js-btn-minus' type='button'>&minus;</button>\
                                    </div>\
                                    <input id='inputQty' data-id='" + value.ID + "'type='text' class='form-control text-center' value='1' placeholder='' aria-label='Example text with button addon' aria-describedby='button-addon1'>\
                                    <div class='input-group-append'>\
                                        <button id='addQty' class='btn btn-outline-primary js-btn-plus' type='button'>&plus;</button>\
                                    </div>\
                                </div>\
                            </div>"
                        );
                
                $('#quickViewModal').modal({backdrop: 'static', keyboard: true});

                //Check if options for this item are available
                if($('.optionElements').length)
                {
                    //If they do, disable quantity options
                    $('#inputQty').prop('disabled', true);
                    $('#removeQty').prop('disabled', true);
                    $('#addQty').prop('disabled', true);
                }

            });
        },
        error: function(xhr, error){
            console.log(error);
        }
    });
});
    

/* -------------------------------------------------------------------------- */
/*                      LOGIN MODAL FORM ON CHECKOUT PAGE                     */
/* -------------------------------------------------------------------------- */

//Clear the form fields because they're auto populating database info for some reason
$('#checkoutLogin').on('click', function(){

    //Hide the FName / LName divs
    $('#registerNamesDiv').hide();
    //Remove required attributes to avoid throwing errors
    $('#registerFirst').removeAttr('required');
    $('#registerLast').removeAttr('required');
    $('#confirmPassword').removeAttr('required');
    //Set appropriate modal title
    $('#loginTitle').html('User Login');
    //Clear autopopulated input
    $('#email').val('');
    $('#password').val('');

    //Add class so we can send to appropriate php script
    $('#loginButtonCheckOut').addClass('loginCheckout');
    $('#loginButtonCheckOut').html('Login');
});

//Same as above
$('#checkoutRegister').on('click', function(){

    $('#registerNamesDiv').show();
    //Add required attributes to these fields for form validation
    $('#registerFirst').attr('required');
    $('#registerLast').attr('required');
    $('#confirmPassword').attr('required');

    $('#loginTitle').html('User Registration');

    $('#email').val('');
    $('#password').val('');
    //Show our hidden confirm password field
    $('#confirmPWDiv').show();

    //Add class so we can send to appropriate php script
    $('#loginButtonCheckOut').addClass('registerCheckout');
    $('#loginButtonCheckOut').html('Register');
});

//Clear form fields if closing modal
$('#closeSignInCheckOut').on('click', function(){

    //Clear added classes from login/register selection
    $('#loginButtonCheckOut').removeClass('loginCheckout');
    $('#loginButtonCheckOut').removeClass('registerCheckout');
    $('#loginButtonCheckOut').html('');
    $('.checkoutModalError').hide();
    
    //Take care of confirm PW
    $('#confirmPWDiv').hide();
    //Clear all input fields
    $('#email').val('');
    $('#password').val('');
});

/* ----------- PROCESS LOGIN CREDENTIALS AND HANDLE APPROPRIATELY ----------- */
$(document).on('click', "button.loginCheckout", function(e){
    
    if($('.checkoutModalError').is(':visible'))
    {
        $('.checkoutModalError').slideUp(150);
    }

    //Checking form validation for prevent default
    if($(this).closest('form')[0].checkValidity())
    {
        e.preventDefault();

        //Store information for back end processing
        var userEmail = $('#email').val();
        var userPW = $('#password').val();

        //Ajax call to compare credentials via PHP
        $.ajax({
            url: 'ajax/login.php',
            method: 'POST',
            dataType: 'JSON',
            data:
            {
                email: userEmail,
                pass: userPW
            },

            //On success
            success: function(data)
            {
                //If successful, reload the page so php can do its work
                if(data.success == true)
                {
                    
                    window.location.reload();
                }
                else if(data.success == false)
                {
                    $('.checkoutModalError').html("<h5 class='text-black text-center'>" + data.message + "</h5>");
                    $('.checkoutModalError').slideDown(300);
                }
            },

            error: function(xhr, error)
            {
                console.log(error);
            }
        });
    }

});

/* ----------- PROCESS REGISTER CREDENTIALS AND HANDLE APPROPRIATELY ----------- */
$(document).on('click', "button.registerCheckout", function(e){

    if($('.checkoutModalError').is(':visible'))
    {
        $('.checkoutModalError').slideUp(150);
    }

    if($(this).closest('form')[0].checkValidity())
    {
        e.preventDefault();

        //Store information for back end processing
        userEmail = $('#email').val();
        userPW = $('#password').val();
        confirmPW = $('#confirmPassword').val();
        firstName = $('#registerFirst').val();
        lastName = $('#registerLast').val();

        //Check password validation
        if(userPW !== confirmPW)
        {
            $('.checkoutModalError').html('Passwords do not match');
            $('.checkoutModalError').slideDown(300);

            return;
        }



        //Ajax call to compare credentials via PHP
        $.ajax({
            url: 'ajax/register.php',
            method: 'POST',
            dataType: 'JSON',
            data:
            {
                email: userEmail,
                pass: userPW,
                first: firstName,
                last: lastName
            },

            //On success
            success: function(data)
            {
                //If successful, reload the page so php can do its work
                if(data.success == true)
                {
                    window.location.reload();
                }
                else if(data.success == false)//If there's an error, show user what it is
                {
                    $('.checkoutModalError').html(data.message);
                    $('.checkoutModalError').slideDown(300);
                }
                
            },

            error: function(xhr, error)
            {
                console.log(error);
            }
        });
    }
    

});

/* -------------------------------------------------------------------------- */
/*                USING PREVIOUSLY STORED CARD TO FILL FORM                */
/* -------------------------------------------------------------------------- */
$('.cardRadio').on('click', function(){

    //Grab the card id from the input tag and process w/ ajax
    var cardID = $(this).data('card');
    
    $.ajax({
        url: 'ajax/getCard.php',
        method: 'POST',
        dataType: 'JSON',
        data: {card: cardID},

        success: function(data)
        {
            //Iterate through returned card info and populate form
            $.each(data, function(key, value)
            {
                $('#cardNumber').val(value.num);
                $('#cardName').val(value.carName);

                //Split expiration for output
                var expiration = value.ex.split('/');
                $('#expirationMonth').val(expiration[0]);
                $('#expirationYear').val(expiration[1]);

            });
            
        }
    });
});

/* -------------------------------------------------------------------------- */
/*                USING PREVIOUSLY STORED ADDRESS TO FILL FORM                */
/* -------------------------------------------------------------------------- */
$('.addressRadio').on('click', function(){

    //Grab the address id from the input tag and process w/ ajax
    var addID = $(this).data('add');
    
    $.ajax({
        url: 'ajax/getAddress.php',
        method: 'POST',
        dataType: 'JSON',
        data: {addID: addID},

        success: function(data)
        {
            //Iterate through returned address info and populate form
            $.each(data, function(key, value)
            {
                $('#billingFName').val(value.first);
                $('#shipFName').val(value.first);
                $('#billingLName').val(value.last);
                $('#shipLName').val(value.last);
                $('#billingEmail').val(value.email);
                $('#billingAdd1').val(value.street);
                $('#shipAddress1').val(value.street);
                $('#billingAdd2').val(value.street2);
                $('#shipAddress2').val(value.street2);
                $('#billingCity').val(value.city);
                $('#shipCity').val(value.city);
                $('#billingState').val(value.state);
                $('#shipState').val(value.state);
                $('#billingPost').val(value.zip);
                $('#shipPostal').val(value.zip);

            });
            
        }
    });
});

/* --------------------- FOR SHIPPING TO BILLING ADDRESS -------------------- */

$('#shipToSame').on('click', function(){
    //Store all our required values in variables
    var fName = $('#billingFName').val();
    var lName = $('#billingLName').val();
    var addr1 = $('#billingAdd1').val();
    var addr2 = $('#billingAdd2').val();
    var city = $('#billingCity').val();
    var state = $('#billingState').val();
    var zip = $('#billingPost').val();

    //Now set all the appropriate fields so we can submit the form
    $('#shipFName').val(fName);
    $('#shipFName').prop('required', false);

    $('#shipLName').val(lName);
    $('#shipLName').prop('required', false);

    $('#shipAddress1').val(addr1);
    $('#shipAddress1').prop('required', false);

    $('#shipAddress2').val(addr2);

    $('#shipCity').val(city);
    $('#shipCity').prop('required', false);

    $('#shipState').val(state);
    $('#shipState').prop('required', false);

    $('#shipPostal').val(zip);
    $('#shipPostal').prop('required', false);
});

/* -------------------- FOR SHIPPING TO DIFFERENT ADDRESS ------------------- */

$('#shipDiff').on('click', function(){

    $('#shipFName').val('');//On click, remove all previously populated information
    $('#shipFName').prop('required', true);

    $('#shipLName').val("");
    $('#shipLName').prop('required', true);

    $('#shipAddress1').val("");
    $('#shipAddress1').prop('required', true);

    $('#shipAddress2').val("");

    $('#shipCity').val("");
    $('#shipCity').prop('required', true);

    $('#shipState').val("");
    $('#shipState').prop('required', true);

    $('#shipPostal').val("");
    $('#shipPostal').prop('required', true);
});

/* -------------------------------------------------------------------------- */
/*                          CONFIRM SHIPPING FUNCTION                         
    Essentially just a cheap shot way to calculate shipping costs without 
    having to redirect to another page or reload current checkout.php page
/* -------------------------------------------------------------------------- */

$('#confirmShipping').on('click', function(e){

    
    if($(this).closest('form')[0].checkValidity())
    {
        
        e.preventDefault();

        var shipToZip = $('#shipPostal').val();
        var orderWeight = parseFloat($('#shipWeight').html());

        $.ajax({
            url: 'ajax/calculateShipping.php',
            method: 'POST',
            dateType: 'JSON',
            data: 
            {
                zip: shipToZip,
                wt: orderWeight
            },

            success: function(data)
            {
                //Enable the ability to place the order
                $('#confirmPaymentDiv').slideDown(100);
                
                //Show our shipping option and cost
                $('#shippingCost').html(data);
                $('.orderSummaryShipping').slideDown();

                //Update the order total cost
                total = $('#orderTotal').html();
                total = total.replace(',', '');
                total = parseFloat(total);

                shipping = parseFloat(data)

                total += shipping;
                //Reformat the number for output
                formattedTotal = addCommas(total.toFixed(2));

                //Update the total order cost
                $('#orderTotal').html(formattedTotal);

            },

            error: function(xhr, error)
            {
                console.log(error);
            }
        });
    }
    //Prevent default, gather information, lick stamp and send it via ajax
    

    
});

/* -------------------------------------------------------------------------- */
/*                      ROLLUP SHIPPING ADDRESS ON CLICK                      */
/* -------------------------------------------------------------------------- */
$('#shipToSame').on('click', function(){
    
    if($('#ship_different_address').hasClass('collapse show'))//This class is present when 'ship to different address is selected'
    {
        //Process appropriately to collapse shipping form
        $('#shipDiffLabel').addClass('collapsed')
        $('#shipDiffLabel').attr('aria-expanded', false);
        $('#ship_different_address').removeClass('show');
    }
    //Take all the info from the billing fields and populate into shipping


});


/* -------------------------------------------------------------------------- */
/*                          CONFIRM CARD INFORMATION                          */
/* -------------------------------------------------------------------------- */
$('#confirmPayment').on('click', function(e)
{

    //Remove error handling if it's present
    $('#expirationYear').removeClass('error');
    $('#expirationMonth').removeClass('error');

    if($(this).closest('form')[0].checkValidity())
    {
        
        e.preventDefault();

        //Load up all of our billing / shipping address information
        var billingStreet = $('#billingAdd1').val();
        var billingStreet2 = $('#billingAdd2').val();

        if(billingStreet2 === '')
        {
            billingStreet2 = null;

        }
        var billingCity = $('#billingCity').val();
        var billingState = $('#billingState').val();
        var billingZip = $('#billingPost').val();

        //Shipping
        var shippingStreet = $('#shipAddress1').val();
        var shippingStreet2 = $('#shipAddress2').val();
        var shippingCity = $('#shipCity').val();
        var shippingState = $('#shipState').val();
        var shippingZip = $('#shipPostal').val();

        //Payment information
        var cardNumber = $('#cardNumber').val();
        var cardName = $('#cardName').val();
        var expMonth = $('#expirationMonth').val()
        var expYear = $('#expirationYear').val();
        var secCode = $('#securityCode').val();

        //Get the current date
        var date = new Date();
        var month = date.getMonth() + 1;//0 based 
        var year = date.getFullYear();

        
        //Check expiration values and concatenate
        if(expYear < year)
        {
            $('#expirationYear').addClass('error');
            return;
        }
        else if(expYear == year && expMonth < month)
        {
            $('#expirationMonth').addClass('error');
            return;
        }
        //Concat the expiration
        var expiration = expMonth + '/' + expYear;
        
        
        $.ajax({
            url: 'ajax/processCustomer.php',
            method: 'POST',
            dateType: 'JSON',
            data: 
            {
                billStreet1: billingStreet,
                billStreet2: billingStreet2,
                billCity: billingCity,
                billState: billingState,
                billZip: billingZip,
                shipStreet1: shippingStreet,
                shipStreet2: shippingStreet2,
                shipCity: shippingCity,
                shipState: shippingState,
                shipZip: shippingZip,
                cardNum: cardNumber,
                cardName: cardName,
                cardExp: expiration,
                secCode: secCode
            },

            success: function(data)
            {
                //Enable the ability to place the order
                $('#placeOrderButtonDiv').slideDown(100);

                
                

            },

            error: function(xhr, error)
            {
                console.log(error);
            }
        });
    }
    //Prevent default, gather information, lick stamp and send it via ajax
});


/* -------------------------------------------------------------------------- */
/*                            PLACE ORDER FUNCTION                            */
/* -------------------------------------------------------------------------- */
$('#placeOrder').on('click', function(e)
{
    //Prevent default submission
    e.preventDefault();

    //Grab cost of shipping to insert into db
    shippingCost = parseFloat($('#shippingCost').html());

    $.ajax({
        url: "ajax/addOrder.php",
        dataType: "JSON",
        method: "POST",
        data:
        {
            shippingCost: shippingCost
        },
        success: function(data)
        {
            console.log(data);
        },
        error: function(xhr, error)
        {
            console.log(error);
        }

    });
    
});


/* -------------------------------------------------------------------------- */
/*             HANDLE DYNAMICALLY ADDED INCREMENT/DECREMENT BUTTON            */
/* -------------------------------------------------------------------------- */
$(document).on('click', '.js-btn-minus', function(e){
    e.preventDefault();

    if ( $(this).closest('.input-group').find('.form-control').val() != 0  ) {
        $(this).closest('.input-group').find('.form-control').val(parseInt($(this).closest('.input-group').find('.form-control').val()) - 1);
    } else {
        $(this).closest('.input-group').find('.form-control').val(parseInt(0));
    }
});

$(document).on('click', '.js-btn-plus', function(e){
    e.preventDefault();
	$(this).closest('.input-group').find('.form-control').val(parseInt($(this).closest('.input-group').find('.form-control').val()) + 1);
});



//Ripped this addcommas function from https://stackoverflow.com/a/7327229/12671600
function addCommas(nStr)
{
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

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