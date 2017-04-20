HousingMyHousing = (function() {
    var s = {
        divMap : $(".mapMyHousing"),
        latitude : $(".latitude"),
        longitude : $(".longitude")
    };

    var init = function() {
        bindUIActions();
        LoaderScript.loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCvRAuFQQ04OVRXeimrBPdMMHRnpMXYw8Q&language=fr");
        setTimeout(function(){ initializeMaps(); }, 3000);
    };

    var bindUIActions = function() {
        
    };
    var initializeMaps = function()
    {
        if (window['google'] == undefined) setTimeout(initializeMap, 1000);
        s.divMap.each(function(){
            var coordinated = $(this).prev().val().split(',');
            var myLatLng = {lat: parseFloat(coordinated[0]), lng: parseFloat(coordinated[1])};
            var map = new google.maps.Map($(this)[0], {
                zoom: 15,
                center: myLatLng
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map
            }); 
        });
        
    };
    return {
        init: init
    }
})();