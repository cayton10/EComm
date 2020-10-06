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
                            output += product.Manu + " " + product.Title + "<br/>"
                        });
                        $('#searchResults').html(output);
                        $('#searchResults').fadeIn('200');
                    }
                }
            )
        }
    });

});