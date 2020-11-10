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
        var prodID = $('#reviewForm').attr('value');
        var rating = $('#stars').data('rate');
        var reviewDetail = $('#reviewDetail').val();
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
    var infotosend = JSON.stringify({min: minHandle, max: maxHandle, value: value, type: type, manu: manuName})
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
/*                    QUANTITY CHANGE ARROWS ON SHOP.SINGLE                   */
/* -------------------------------------------------------------------------- */
$('#addQty').on('click', function(){
    //Read current input qty value
    var currentVal = $('#inputQty').val();
    //Update the value of the input field for sending to DB
    $('#inputQty').val(currentVal++);
});




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

    //Grab the values in any present <select> fields
    var selectValues = $('.optionElements').map(function() {
        return $(this).val();
    });

    var checkValue;
    $.each(selectValues, function(key, value){//Iterate over all of the 'values' from options
        if(value == 'default')                  //If there are any defaults, set boolean
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

    //Make our ajax call, lick stamp and send it
    $.ajax(
        {
            url: 'ajax/addToCart.php',
            data:
            {
                quantity: quantity,
                ID: prodID
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
            $('#updateCart').arrt('disabled', true);
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
//When mouse enters
$('.innerProdContainer').mouseenter(function () { 
    $(this).children('.quickViewAccess').slideDown(300);
});
//When mouse leaves
$('.innerProdContainer').mouseleave(function () {
    $(this).children('.quickViewAccess').slideUp(300);
});


//Click function to show modal containing product info
$('.quickViewAccess').on('click', function()
{
    //grab the product ID from the quickview button
    var quickID = { "ID": $(this).data('id') };

    $.ajax({
        url: 'ajax/quickView.php',
        method: 'POST',
        data: quickID,
        dataType: 'JSON',
        success: function(data){

            //Process everything that comes back
            $.each(data, function(key, value){
                $('#quickViewTitle').html(value.title);
                $('#quickViewModal').modal({backdrop: 'static', keyboard: true});

            });
        },
        error: function(xhr, error){
            console.log(error);
        }
    });
});
    



$('.optionElements').on('change', function(){

    alert($('.optionElements').val())
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