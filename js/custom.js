//This file will serve as my custom jQuery commands file
$(document).ready(function(){

    //Get full year and append to span
    $('#year').append(new Date().getFullYear());

    //Detail item carousel
    //Activate carousel
    $('#productCarousel').carousel();

    $('.owl-item').addClass('owlProdContainer');

});