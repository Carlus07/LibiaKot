ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

UserChangePassword = (function() {
    var s = {
    	oldPassword : $("#oldPassword"),
        password : $("#password"),
        confirmPassword : $("#confirmPassword"),
        button : $(".buttonRegister"),
        errors : $("#errorPassword")
    };
    var init = function() {
        bindUIActions();
        if (s.errors.val() != "") notification();
        Translator.translation('textEmpty').done(function(data){
            if (s.oldPassword.val() == "") s.oldPassword[0].setCustomValidity(data);
            if (s.password.val() == "") s.password[0].setCustomValidity(data);
            if (s.confirmPassword.val() == "") s.confirmPassword[0].setCustomValidity(data);
        });
    };
    var bindUIActions = function() {
    	s.oldPassword.on("change", function(){validation(this,"password");});
        s.password.on("change", function(){validation(this,"password");});
        s.confirmPassword.on("change", function(){validation(this,"passwordBis");});
    };
    var validation = function(element, type) {
    	var result = (type == "password") ? Validation.validationPassword(element.value) : (element.value != s.password.val()) ? "passwordCheck" : result = Validation.validationPassword(element.value);
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