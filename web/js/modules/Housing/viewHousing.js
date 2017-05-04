HousingViewHousing = (function() {
    var s = {
        carousel : $("#owl-example")
    };

    var init = function() {
        bindUIActions();
    };

    var bindUIActions = function() {
        s.carousel.owlCarousel({
            singleItem:true
        });
    };
    return {
        init: init
    }
})();