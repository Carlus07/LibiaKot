ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

UserReset = (function() {
    var s = {
        mail : $("#mail"),
        button : $(".buttonRegister"),
        errors : $("#errorPassword")
    };
    var init = function() {
        bindUIActions();
        if (s.errors.val() != "") notification();
        Translator.translation('textEmpty').done(function(data){
            if (s.mail.val() == "") s.mail[0].setCustomValidity(data);
        });
    };
    var bindUIActions = function() {
        s.mail.on("change", function(){validation(this);});
    };
    var validation = function(element) {
        var result = Validation.validationMail(element.value);
        translation(result, element);
    };
    var translation = function(label, element)
    {
        if (label != '')
        {
            Translator.translation(label).done(function(data){
                element.setCustomValidity(data);
            });
        }
        else element.setCustomValidity('');
    };
    var notification = function()
    {
        var str = (s.errors.val()).split('+');
        for (var i = 0; i < str.length; i++)
        {
            Translator.translation(str[i]).done(function(data){
                Notification.notification("warning", data);
            });
        }
    };
    return {
        init: init
    }
})();