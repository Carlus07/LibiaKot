ModuleManager.loadModule("web/js/tools/Notification.js");
HousingMyHousing = (function() {
    var s = {
        divMap : $(".mapMyHousing"),
        latitude : $(".latitude"),
        longitude : $(".longitude"),
        dialog : $('#dialog-confirm'),
        removeProperty : $('.removeProperty'),
        removeHousing : $('.removeHousing')
    };

    var init = function() {
        bindUIActions();
        LoaderScript.loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCvRAuFQQ04OVRXeimrBPdMMHRnpMXYw8Q&language=fr");
        setTimeout(function(){ initializeMaps(); }, 3000);
    };

    var bindUIActions = function() {
        s.removeProperty.on('click', removeProperty);
        s.removeHousing.on('click', removeHousing);
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
    var removeHousing = function()
    {
        id = $(this).attr("value");
        frame = $(this).parent().parent().parent();
        Translator.translation("removeHousing").done(function(data){
            s.dialog.html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+data+'</p>').dialog({
                title : "Confirmation",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Ok": function() {
                        s.dialog.dialog( "close" );
                        $.post("?p=housing.deleteHousing",
                            {idHousing: id},
                            function(result)
                            {
                                var str = result.split('+');
                                Translator.translation(str[1]).done(function(data){
                                    Notification.notification(str[0], data);
                                    PageManager.loadPage();
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
    }
    var removeProperty = function()
    {
        id = $(this).attr("value");
        frame = $(this).parent().parent().parent();
        Translator.translation("removeProperty").done(function(data){
            s.dialog.html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+data+'</p>').dialog({
                title : "Confirmation",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Ok": function() {
                        s.dialog.dialog( "close" );
                        $.post("?p=housing.deleteProperty",
                            {idProperty: id},
                            function(result)
                            {
                                var str = result.split('+');
                                Translator.translation(str[1]).done(function(data){
                                    Notification.notification(str[0], data);
                                    PageManager.loadPage();
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
    return {
        init: init
    }
})();