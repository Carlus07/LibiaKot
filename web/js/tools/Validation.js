Validation = (function() {
    var s = {
        textWithoutNumber : /^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ'!?:._()\s-]{2,255}$/,
        mail : /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        phone : /^0[1-9]([-. ]?[0-9]{2}){4}$/,
        password : /^[a-zA-Z0-9_-]{6,16}$/
    };
    var validationText = function(text)
    {
        if (text == "") return "textEmpty";
        else if (text.length < 2) return "textTooShort";
        else if (text.length > 255) return "textTooLong";
        else if (!s.textWithoutNumber.test(text)) return "textNotString";
        else return "";
    };
    var validationNumber = function(number)
    {
        if (number == "") return "textEmpty";
        number = parseInt(number);
        if (isNaN(number)) return "numberNotInteger"
        else if (number < 0) return "numberNotNull"
        else if (number > 99999) return "numberTooLong";
        else return "";
    };
    var checkMail = function(mail)
    {
        return $.post("?r=validation.checkMailAvailable",
            {email: mail}
        );
    };
    var validationMail = function(mail)
    {
        if (mail == "") return "textEmpty";
        else if (mail.length > 255) return "textTooLong";
        else if (!s.mail.test(mail)) return "textNotMail";
        else return "";
    }
    var validationPhone = function(phone)
    {
        if (phone == "") return "textEmpty";
        else if (!s.phone.test(phone)) return "textNotPhone";
        else return "";
    };
    var validationPassword = function(password)
    {
        if (password == "") return "textEmpty";
        else if (!s.password.test(password)) return "passwordNotValid";
        else return "";
    };
    return {
        validationText: validationText,
        validationPhone: validationPhone,
        validationPassword : validationPassword,
        validationMail : validationMail,
        validationNumber : validationNumber,
        checkMail : checkMail
    };
})();