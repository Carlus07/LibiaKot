ModuleManager.loadModule("web/js/tools/Notification.js");
HousingListProperties = (function() {
    var s = {
       deleteProperty : $('.deleteProperty'),
       dialog : $('#dialog-confirm'),
    };
    var init = function() {
        bindUIActions();
    };

    var bindUIActions = function() {
        s.deleteProperty.on('click', deleteProperty);
    };
    var deleteProperty = function()
    {
        var selection = $(this).attr("value");
        Translator.translation("confirmDeleteProperty").done(function(data){
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
                            {idProperty: selection},
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
    return {
        init: init
    }
})();