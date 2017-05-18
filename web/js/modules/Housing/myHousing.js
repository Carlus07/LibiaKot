ModuleManager.loadModule("web/js/tools/Notification.js");
HousingMyHousing = (function() {
    var s = {
        divMap : $(".mapMyHousing"),
        latitude : $(".latitude"),
        longitude : $(".longitude"),
        dialog : $('#dialog-confirm'),
        removeProperty : $('.removeProperty'),
        removeHousing : $('.removeHousing'),
        checkBox : $('.checkBox')
    };

    var init = function() {

        bindUIActions();
        LoaderScript.loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCvRAuFQQ04OVRXeimrBPdMMHRnpMXYw8Q&language=fr");
        setTimeout(function(){ initializeMaps(); }, 3000);
    };

    var bindUIActions = function() {
        s.removeProperty.on('click', removeProperty);
        s.removeHousing.on('click', removeHousing);
        s.checkBox.checkboxradio({
            icon: false
        });
        s.checkBox.on('click', changeState);
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
                        $.post("?w=housing.deleteHousing",
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
                        $.post("?w=housing.deleteProperty",
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
    var changeState = function()
    {
        var element = $(this);
        if (element.attr("data-state") == "false")
        {
            element.attr("data-state", "true");
            Translator.translation("visible").done(function(data){
                element.prev().text(data);
            });
        }
        else
        {
            element.attr("data-state", "false");
            Translator.translation("invisible").done(function(data){
                element.prev().text(data);
            });
        }
        var stateHousing = (element.attr("data-state") == "false") ? 0 : 1;
        $.post("?w=housing.changeVisibility",
            {state : stateHousing, idHousing : element.attr("data-idHousing")}
        );
    };
    return {
        init: init
    }
})();