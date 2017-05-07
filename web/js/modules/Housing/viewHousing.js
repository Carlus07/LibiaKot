HousingViewHousing = (function() {
    var s = {
        carousel : $("#owl-example"),
        summary : $('.divSummaryHousing'),
        heightPicture : $('.carousel').height(),
        GPSPosition : $('.GPSPosition'),
        divMap : $("#map-canvas"),
        geoCoder : null,
        map : null,
        marker : null
    };

    var init = function() {
        bindUIActions();
        s.summary.height(s.heightPicture+'px');
        LoaderScript.loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCvRAuFQQ04OVRXeimrBPdMMHRnpMXYw8Q&language=fr");
        setTimeout(function(){ initializeMap(); }, 3000);
    };

    var bindUIActions = function() {
        s.carousel.owlCarousel({
            singleItem:true
        });
        $(window).resize(adjustment);
    };
    var adjustment = function()
    {
        s.heightPicture = $('.carousel').height();
        s.summary.height(s.heightPicture+'px');
    };
    var initializeMap = function()
    {
        if (s.GPSPosition.val() != "")
        {
            var coordinated = s.GPSPosition.val().split(',');
            var latlng = {lat: parseFloat(coordinated[0]), lng: parseFloat(coordinated[1])};
            var map = new google.maps.Map(s.divMap[0], {
                zoom: 15,
                center: latlng
            });
            var marker = new google.maps.Marker({
                position: latlng,
                map: map
            }); 
        }
        else
        {
            if (window['google'] == undefined) setTimeout(initializeMap, 1000);
            s.geoCoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(50.466667,4.867222);
            var mapOptions = {
                zoom      : 14,
                center    : latlng
            }
            s.map = new google.maps.Map(s.divMap[0], mapOptions);
        }
    };
    return {
        init: init
    }
})();