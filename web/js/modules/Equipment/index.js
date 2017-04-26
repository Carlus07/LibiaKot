ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

EquipmentIndex = (function() {
    var s = {
        table : $('.tableEquipment'),
        addEquipment : $('.list-group-item-action'),
        settingEquipment : $('.settingEquipment'),
        equipment : $('.equipment'),
        maxW : 150,
        maxH : 150,
        qualiteCompression : 0.8,
        vignetteSize : 120,
        errors : $("#errorEquipment"),
        updateLogo : $('.updateLogo'),
        fileUpdateLogo : $('#fileUpdateLogo'),
        deleteEquipment : $('.deleteEquipment'),
        equipmentSort : $('#equipmentSort'),
        equipmentFree : $( "#equipmentFree" )
    };

    var init = function() {
        bindUIActions();
    };

    var bindUIActions = function() {
        if (s.errors.val() != "") notification();

        $( "#equipmentSort, #equipmentFree" ).sortable({
            connectWith: ".list-group",
            cancel: ".active, .divAddEquipment"
        }).disableSelection();
        s.equipmentSort.on( "sortupdate", function( event, ui ) {updateCategoryEquipment(ui.item);} );

        s.addEquipment.on('click', formEquipment);
        s.equipment.on('mouseover', function() {$(this).find('i').addClass('visible');} );
        s.equipment.on('mouseout', function() {$(this).find('i').removeClass('visible');} );
        s.updateLogo.on('click', updateLogo);
        s.deleteEquipment.on('click', deleteEquipment);
        s.fileUpdateLogo.on('change', function(event){ compression(event,'update'); });
    };
    var formUIActions = function() {
        s.pictureUpload.on('click', clickInput);
        Translator.translation('textEmpty').done(function(data){
            if (s.label.val() == "") s.label[0].setCustomValidity(data);
        });
        s.label.on("change", validation);
        s.submit.on("click", verification);
        s.fileUpload.on('change', function(event){ compression(event, 'add'); });
    };
    var uploadUIActions = function() {
        s.divUpload.on('mouseover', showButtonCancel);
        s.divUpload.on('mouseout', disappearButtonCancel);
        s.cancelUpload.on('click', cancelUpload);
    };
    var refreshUIAction = function() {
        s.pictureUpload.on('click', clickInput);
    };
    var formEquipment = function() {
        s.addEquipment.off();
        var selection = $(this);
        selection.empty();
        var label = "";
        var buttonAddLabel = "";
        Translator.translation("equipmentLabel").done(function(data){
            label = data;
            Translator.translation("buttonAddLabel").done(function(data){
                buttonAddLabel = data;
                var form =  '<form class="form-signin" action="?w=equipment.add" method="POST">'+
                            '<input type="text" name="labelEquipment" id="labelEquipment" class="form-control" placeholder="*'+label+'" required autofocus>'+
                            '<div class="divUpload">'+
                                '<img class="uploadLogo" src="web/pictures/upload.png"/>'+
                            '</div>'+
                            '<input type="hidden" name="namePicture" id="namePicture"/>'+
                            '<input type="file" name="fileUploadLogo" id="fileUploadLogo" style="display:none;" required>'+
                            '<button class="btn btn-lg btn-success btn-signin buttonRegister" type="submit">'+buttonAddLabel+'</button>'+
                        '</form>';
                selection.append(form);
                s.fileUpload = $('#fileUploadLogo');
                s.pictureUpload = $('.uploadLogo');
                s.label = $("#labelEquipment");
                s.submit = $(".buttonRegister");
                s.divUpload = $('.divUpload');
                s.namePicture = $('#namePicture');
                formUIActions();
            });
        });        
    };
    var clickInput = function()
    {
        s.fileUpload.click();
    };
    var verification = function()
    {
        if (s.fileUpload.val() == "") 
        {
            Translator.translation("emptyFile").done(function(data){
                Notification.notification("warning", data);
            });
        }
    };
    var validation = function()
    {
        var element = $(this)[0];
        var result = Validation.validationText(element.value);
        if (result != '')
        {
            Translator.translation(result).done(function(data){
                element.setCustomValidity(data);
            });
        }
        else element.setCustomValidity('');
    }
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
                        
                        uploadFile(canvasDataUpload, 'png', img.name, type);

                        if (type == 'add')
                        {
                            s.divUpload.empty();
                            s.divUpload.append('<i class="fa fa-times-circle cancelUpload" aria-hidden="true"></i><img class="vignette" src="'+canvasDataUpload+'" style="max-width:'+s.vignetteSize+'px;">');
                            s.namePicture.attr("value", img.name+'.png');
                            s.cancelUpload = $('.cancelUpload');
                            uploadUIActions();
                        }
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
    var uploadFile = function(f, ext, n, type)
    {
        $.post("index.php?w=equipment.upload",
            {file: f, extension : ext, name : n},
            function (result)
            {
                if (type == "update") 
                {
                    $.post("index.php?w=equipment.update",
                        {name: n+'.png', idEquipment : s.idEquipment},
                        function (result)
                        {
                            var str = result.split('+');
                            Translator.translation(str[1]).done(function(data){
                                Notification.notification(str[0], data);
                                if (str[0] == "success") s.picture.attr('src', 'web/pictures/Equipment/'+n+'.png');
                            });
                        }
                    );
                }
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
    var updateLogo = function()
    {
        s.fileUpdateLogo.click();
        s.idEquipment = $(this).parent().parent().parent().attr('data-idequipment');
        s.picture = $(this).parent().parent().prev().children();
    };
    var deleteEquipment = function()
    {
        var selection = s.idEquipment = $(this).parent().parent().parent();
        s.idEquipment = $(this).parent().parent().parent().attr('data-idequipment');
        $.post("index.php?w=equipment.delete",
            {idEquipment : s.idEquipment},
            function (result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                    if (str[0] == "success") selection.remove();
                });
            }
        );
    };
    var showButtonCancel = function()
    {
        $(this).find('img').css('opacity', 0.5);
        $(this).find('i').addClass('visible');
    };
    var disappearButtonCancel = function()
    {
        $(this).find('img').css('opacity', 1);
        $(this).find('i').removeClass('visible');
    };
    var cancelUpload = function()
    {
        s.divUpload.off();
        s.divUpload.empty();
        s.divUpload.append('<img class="uploadLogo" src="web/pictures/upload.png"/>');
        s.pictureUpload = $('.uploadLogo');
        refreshUIAction();
    };
    var deleteLinkEquipment = function(item)
    {
        console.log("delete : "+item);
    };
    var updateCategoryEquipment = function(item)
    {
        idE = item.attr("data-idequipment");
        idC = item.prevUntil(".active").last().prev().attr("data-idcategory");
        idC = (idC != undefined) ? idC : (item.prev().hasClass("active")) ? item.prev().attr("data-idcategory") : 0;
        $.post("index.php?w=equipment.updateCategory",
            {idEquipment : idE, idCategory : idC},
            function (result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                });
            }
        );
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
