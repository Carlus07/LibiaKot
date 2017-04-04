HomeIndex = (function() {
    var s = {
        carousel : $("#owl-example")
    };

    var init = function() {
        bindUIActions();
    };

    var bindUIActions = function() {
        s.carousel.owlCarousel({
            slideSpeed : 500,
            paginationSpeed : 1000,
            singleItem:true,
            autoPlay : true
        });
    };
    return {
        init: init
    }
})();