Notification = (function() {
    var notification = function(type, message) {
        var icon = "";
        switch(type)
        {
            case "success" : icon = 'fa fa-check-circle'; break;
            case "warning" : icon = 'fa fa-warning'; break;
            case "info" : icon = 'fa fa-info-circle'; break;
            default : icon = 'fa fa-warning';
        }
        
        $.notify({
            icon: icon,
            message: message
        },{
            type: type,
            placement: {
                from: "bottom",
                align: "center"
            },
            timer: 500,
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span class="logoNotication" data-notify="icon"></span> ' +
                '<span data-notify="message">{2}</span>' +
            '</div>' 
        });
    }
    return {
        notification: notification
    }
})();