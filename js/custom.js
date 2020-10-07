//This file will serve as my custom jQuery commands file
$(document).ready(function(){

    //Get full year and append to span
    $('#year').append(new Date().getFullYear());

    //Detail item carousel
    //Activate carousel
    $('#productCarousel').carousel();

    //Hide search results div on load
    $('#searchResults').hide();

    //Function for dynamic searching in header.php on keyup
    $('#search').keyup(function()
    {
        //if no content in search bar, hide search results
        if($(this).val() == "")
            $('#searchResults').hide();
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
                            $.each(data, function(i, product) {
                                var prevManu
                                //Format output and provide links to products
                                if(product.Manu != prevManu)
                                {   //Formatting for manufacturer
                                    output += "<span class='manufact'>" + product.Manu + "</span>";
                                }
                                //Formatting for manufacturer's item
                                output += "<span class='prodName'><a href='shop-single.php?id=" + product.ID + "&name=" + product.Title + "'>" + product.Title + "</a></span>";
                                
                                //Set prevManu to Manu
                                prevManu = product.Manu;
                            });

                            $('#searchResults').html(output);
                            $('#searchResults').fadeIn('200');
                        }
                    }
                )
            }
        }
    });

});