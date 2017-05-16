HousingViewHousing = (function() {
    var s = {
        carousel : $("#owl-example"),
        summary : $('.divSummaryHousing'),
        heightPicture : $('.item').height(),
        GPSPosition : $('.GPSPosition'),
        divMap : $("#map-canvas"),
        geoCoder : null,
        map : null,
        marker : null,
        deleteHousing : $('.deleteHousing'),
        confirmHousing : $('.confirmHousing'),
        dialog : $('#dialog-confirm'),
        mail : $('.mail'),
        menu : $('.selection'),
        message : $('.message')
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
        s.deleteHousing.on('click', deleteHousing);
        s.confirmHousing.on('click', confirmHousing);
        $(window).resize(adjustment);
        s.mail.on('click', displayForm);
    };
    var adjustment = function()
    {
        s.heightPicture = $('.item').height();
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
    var deleteHousing = function()
    {
        var selection = $(this).attr("value");
        Translator.translation("confirmDeleteHousing").done(function(data){
            s.dialog.html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+data+'</p>').dialog({
                title : "Confirmation",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Ok": function() {
                        s.dialog.dialog( "close" );
                        $.post("?w=housing.deleteHousing",
                            {idHousing: selection},
                            function(result)
                            {
                                var str = result.split('+');
                                Translator.translation(str[1]).done(function(data){
                                    Notification.notification(str[0], data);
                                    if (str[0] == "success") 
                                    {
                                        PageManager.loadPage();
                                        return true;
                                    }
                                    else return false;
                                });
                            }
                        );
                    },
                    Cancel: function() {
                        s.dialog.dialog( "close" );
                        return false;
                    }
                }
            });
        });
    };
    var confirmHousing = function() 
    {
        var selection = $(this).attr("value");
        $.post("?w=housing.deleteHousing",
            {idHousing: selection},
            function(result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                    if (str[0] == "success") PageManager.loadPage("home.index");
                });
            }
        );
    };
    var displayForm = function()
    {
        s.menu.empty();
        s.message.css('display','block');
    };
    return {
        init: init
    }
})();