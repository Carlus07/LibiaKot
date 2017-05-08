HousingView = (function() {
    var s = {
        slider : $( "#slider-range" ),
        sliderBedroom : $( "#slider-range-min" ),
        amountRent : $( "#amountRent"),
        amoutBedroom : $('#amountBedroom')
    };

    var init = function() {
        bindUIActions();
        s.amountRent.val(s.slider.slider( "values", 0 ) + "€ - " + s.slider.slider( "values", 1 ) + "€");
        s.amoutBedroom.val(s.sliderBedroom.slider( "value" ) );
    };

    var bindUIActions = function() {
        s.slider.slider({
            range: true,
            min: 250,
            max: 1200,
            values: [250, 1200],
            slide: function( event, ui ) {
                s.amountRent.val(ui.values[ 0 ] + "€ - " + ui.values[ 1 ] + "€");
            }
        });
        s.sliderBedroom.slider({
            range: "min",
            min: 1,
            max: 10,
            value: 2,
            slide: function( event, ui ) {
                s.amoutBedroom.val( ui.value );
            }
        });
        s.slider.on("slidechange", function( event, ui ) {console.log(ui)} );
    };
    return {
        init: init
    }
})();