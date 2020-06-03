(function() {

    //Toggle class"toggle" on menu button click
    $('.content .nav-bar span').on('click', function() {

        $('.menu').toggleClass('toggle');
        $('.content').toggleClass('toggle');
        $('footer').toggleClass('toggle');

    });

    //Active Class On links
    $('.links .link').on('click', function() {
        $(this).addClass('active');
        $('.links .link').not($(this)).removeClass('active');
    });

    if($('#select select > option:selected').text() == 'Category') {
    	var ahmed = 'Hi';
    	$('.modal-body').text(ahmed);
    }

    //Form Validation
    $('form').validator();



})();
