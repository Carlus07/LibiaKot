ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

UserProfile = (function() {
    var s = {
        avatar : $('.avatar'),
        updateAvatar : $('.updateAvatar'),
        fileUploadAvatar : $('#fileUploadAvatar'),
        maxW : 600,
        maxH : 600,
        qualiteCompression : 0.8,
        buttonEdition : $('.buttonEdition'),
        table : $('.tableProfile'),
        caseTable : $('.tableProfile td'),
        idUser : $('#idUser')
    };

    var init = function() {
        bindUIActions();
    };

    var bindUIActions = function() {
        s.avatar.on('mouseover', decreaseOpacity);
        s.avatar.on('mouseout', increaseOpacity);
        s.updateAvatar.on('mouseover', decreaseOpacity);
        s.updateAvatar.on('click', click);
        s.fileUploadAvatar.on('change', function(event){ compression(event); });
        s.buttonEdition.on('click', editTable);
        s.caseTable.on("change", validation);
    };
    var decreaseOpacity = function()
    {
        s.avatar.addClass('opacity');
        s.updateAvatar.addClass('visible');
    };
    var increaseOpacity = function()
    {
        s.avatar.removeClass('opacity');
        s.updateAvatar.removeClass('visible');
    };
    var click = function()
    {
        fileUploadAvatar.click();
    };
    var compression = function(event, type) {
        // verification de la compatibilité du navigateur avec  File
        if(window.File && window.FileList && window.FileReader)
        {
            // la liste complete de fichiers
            var files = event.target.files;
            // on parcourt tous les fichier
            for(var i = 0; i< files.length; i++)
            {
                var activeFile = files[i];
                var fileType=activeFile.type;
                var error = false;
                // Verification du type de fichier, image seulement
                if(!fileType.match('image'))
                {
                    error = true;
                    continue;
                }
                // instance du FileReader
                var FileReaderImage = new FileReader();
                FileReaderImage.name=activeFile.name;
                // lecture du fichier terminé
                FileReaderImage.onload=function(e){
                   var pictureFile = e.target;
                   
                    // instance de l'objet image
                    var img = new Image();
                    img.name = uniquid();
                    img.onload = function(e) {
                        var orrigineW=img.width;
                        var orrigineH=img.height;
                        // appel de fuction qui defini les tailles finales
                        var taille=finalSize(orrigineW, orrigineH, s.maxW, s.maxH);
                        
                        // creation du canvas 2d pour les miniatures
                        var canvasUpload = document.createElement('canvas');
                        canvasUpload.width = s.maxW;
                        canvasUpload.height = s.maxH;
                        var contextUpload = canvasUpload.getContext('2d');
 
                        // integration de notre image dans le canvas
                        if (taille.height<taille.width) contextUpload.drawImage(img, 0,0, orrigineW, orrigineH,(s.maxW-taille.width)/2,(s.maxH-taille.height)/2,taille.width,taille.height);
                        else contextUpload.drawImage(img, 0, 0, orrigineW, orrigineH,(s.maxW-taille.width)/2,(s.maxH-taille.height)/2,taille.width,taille.height);

                        // recuperation du nouveau fichier image en jpg compression qualité 80%
                        canvasDataUpload = canvasUpload.toDataURL('image/png', s.qualiteCompression)
                        
                        uploadFile(canvasDataUpload, 'png', img.name);

                        delete canvasUpload;
                        delete canvasDataUpload;
                        delete contextUpload; 
                        delete taille;                    
                    };
                    img.src = pictureFile.result;
                };
                 //lecture de l'image
                FileReaderImage.readAsDataURL(activeFile);
            }
            if(error)
            {
                Translator.translation("wrongFormat").done(function(data){
                    Notification.notification("warning", data);
                });
            }
        }
        else
        {
            Translator.translation("notSupported").done(function(data){
                element.setCustomValidity(data);
            });
        }
    };
    var uniquid = function()
    {
        return (new Date().getTime() + Math.floor((Math.random()*10000)+1)).toString(16);
    };
    var uploadFile = function(f, ext, n)
    {
        $.post("index.php?w=user.addAvatar",
            {file: f, extension : ext, name : n, id : s.idUser.val()},
            function (result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                    if (str[0] == "success") s.avatar.attr('src', 'web/pictures/Avatar/'+n+'.png');
                });
            }
        );
    };
    var finalSize = function(orrigineW, OrrigineH, maxW, maxH)
    {
        var finalSizeW = orrigineW, 
            finalSizeH = OrrigineH;
        
        if (orrigineW > OrrigineH) 
        {
            if (orrigineW > maxW) {
                finalSizeH *= maxW / orrigineW;
                finalSizeW = maxW;
            }
        } 
        else 
        {
            if (OrrigineH > maxH) {
                finalSizeW *= maxH / OrrigineH;
                finalSizeH = maxH;
            }
        }
        return { width: finalSizeW, height: finalSizeH };
    };
    var editTable = function()
    {
        var button = $(this);
        if (button.attr("data-option") == "edit")
        {
            s.table.editableTableWidget({editor: $('<textarea>')});
            button.attr("data-option", "endOfEdit");
            Translator.translation("endOfEdition").done(function(data){
                button.text(data);
            });
            Translator.translation("infoEditable").done(function(data){
                Notification.notification("info", data);
            });
        }
        else 
        {
            s.table.off();
            s.table.css('cursor','context-menu')
            button.attr("data-option", "edit");
            Translator.translation("editAccount").done(function(data){
                button.text(data);
            });
        }        
    };
    var validation = function() 
    {
        s.selection = $(this);
        value = $(this).attr("value");
        switch(value)
        {
            case "text" :
            {
                result = Validation.validationText(s.selection.text());
                break;
            }
            case "number" :
            {
                result = Validation.validationNumber(s.selection.text());
                break;
            }
            case "phone" :
            {
                result = Validation.validationPhone(s.selection.text());
                break;
            }
        }
        if (result == "") 
        {
            var column = s.selection.attr("data-column");
            updateProfil(column, s.selection.text());
        }
        else 
        {
            Translator.translation(result).done(function(data){
                Notification.notification("warning", data);
            });
            return false;
        }
    };
    var updateProfil = function(column, value)
    {
        $.post("?w=user.updateProfil",
            {text: value, field : column, id : s.idUser.val()},
            function(result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                    return (str[0] == "success") ? true : false;
                });
            }
        );
    };
    return {
        init: init
    }
})();