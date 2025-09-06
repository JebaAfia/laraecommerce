// to get current year
function getYear() {
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    document.querySelector("#displayYear").innerHTML = currentYear;
}

getYear();

// owl carousel

$('.owl-carousel').owlCarousel({
    loop: true,
    margin: 10,
    nav: true,
    autoplay: true,
    autoplayHoverPause: true,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 3
        },
        1000: {
            items: 6
        }
    }
})


$(function(){

    $('#pay_now').on('click', function(e){
        e.preventDefault();

        let data = $('#confirm_order').serializeArray();
        let ajaxUrl = $(this).attr('href');
        $.ajax({
            url: ajaxUrl,
            data: data,
            success: function(response){
                window.location.href = response.url;
            }
        });
    })


});
