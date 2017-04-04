ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

UserRegister = (function() {
    var s = {
        lastName : $("#lastName"),
        firstName : $("#firstName"),
        mail : $("#mail"),
        password : $("#password"),
        confirmPassword : $("#confirmPassword"),
        street : $("#street"),
        number : $("#number"),
        city : $("#city"),
        zipCode : $("#zipCode"),
        phone : $("#phone"),
        secondPhone : $("#secondPhone"),
        button : $(".buttonRegister"),
        errors : $("#errorRegister")
    };

    var init = function() {
        bindUIActions();
        if (s.errors.val() != "") notification();
        Translator.translation('textEmpty').done(function(data){
            if (s.lastName.val() == "") s.lastName[0].setCustomValidity(data);
            if (s.firstName.val() == "") s.firstName[0].setCustomValidity(data);
            if (s.mail.val() == "") s.mail[0].setCustomValidity(data);
            if (s.password.val() == "") s.password[0].setCustomValidity(data);
            if (s.confirmPassword.val() == "") s.confirmPassword[0].setCustomValidity(data);
            if (s.street.val() == "") s.street[0].setCustomValidity(data);
            if (s.number.val() == "") s.number[0].setCustomValidity(data);
            if (s.city.val() == "") s.city[0].setCustomValidity(data);
            if (s.zipCode.val() == "") s.zipCode[0].setCustomValidity(data);
            if (s.phone.val() == "") s.phone[0].setCustomValidity(data);
        });
    };

    var bindUIActions = function() {
        s.lastName.on("change", function(){validation(this,"text");});
        s.firstName.on("change", function(){validation(this,"text");});
        s.mail.on("change", function(){validation(this,"mail");});
        s.password.on("change", function(){validation(this,"password");});
        s.confirmPassword.on("change", function(){validation(this,"passwordBis");});
        s.street.on("change", function(){validation(this,"text");});
        s.number.on("change", function(){validation(this,"number");});
        s.city.on("change", function(){validation(this,"text");});
        s.zipCode.on("change", function(){validation(this,"number");});
        s.phone.on("change", function(){validation(this,"phone");});
        s.secondPhone.on("change", function(){validation(this,"phone");});
    };
    var validation = function(element, type) {
        switch(type)
        {
            case "text" :
            {
                result = Validation.validationText(element.value);
                break;
            }
            case "number" :
            {
                result = Validation.validationNumber(element.value);
                break;
            }
            case "mail" :
            {
                result = Validation.validationMail(element.value);
                if (result == "") Validation.checkMail(element.value).done(function(data){
                    translation(data, element);
                });
                break;
            }
            case "password" :
            {
                result = Validation.validationPassword(element.value);
                break;
            }
            case "passwordBis" :
            {
                if (element.value != s.password.val()) result = "passwordCheck";
                else result = Validation.validationPassword(element.value);
                break;
            }
            case "phone" :
            {
                result = Validation.validationPhone(element.value);
                break;
            }
        }
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
        else 
        {
            element.setCustomValidity('');
        } 
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

