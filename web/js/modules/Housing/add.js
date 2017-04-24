ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

HousingAdd = (function() {
    var s = {
        checkBox : $('.checkBox'),
        availability : $("#availability"),
        inputCapacity : $("#capacity"),
        inputSpace : $("#spaceAvailable"),
        easeNearby : $("#easeNearby"),
        area : $("#area"),
        floor : $("#floor"),
        rent : $("#rent"),
        charge : $("#charge"),
        deposit : $("#deposit"),
        rentComment : $("#rentComment"),
        codeInput : $('#code'),
        maxW : 1280,
        maxH : 1024,
        //Taille de la miniature affichée
        tailleVignette : 175,
        //Taille maximale des images miniaturisées
        maxHMiniature : 500,
        maxWMiniature : 500,
        qualiteCompression : 0.8,
        infoHousing : $('.infoHousing'),
        housingType : $('#housingType'),
        capacity : $('.infoCapacity'),
        space : $('.infoSpaceAvailable'),
        buttonAddPicture : $('.buttonAddPicture'),
        fileUploadPictures : $('#fileUploadPictures'),
        divUploadPictures : $(".divUploadPictures"),
        errors : $("#errorAddHousing"),
        id : $("#id").val(),
        method : $('#method').val(),
        //Code unique par page utilisé lors de l'upload dans le nom de l'image
        code : (new Date().getTime() + Math.floor((Math.random()*10000)+1)).toString(16),
        divMap : $("#map-canvas"),
        selectZipCode : $('#zipCode'),
        selectCity : $('#city'),
        inputStreet : $("#street"),
        inputNumber : $("#number"),
        GPSPosition : $('.GPSPosition'),
        geoCoder : null,
        map : null,
        marker : null,
        cancelUpload : $('.cancelUploadHousing'),
        divUpload : $('.carre')
    };

    var init = function() {
        bindUIActions();
        if (s.divUpload[0] != null) uploadUIActions();
        if (s.errors.val() != "") notification();
        if ((s.method == "addHousing") || (s.method == "updateHousing"))
        {
            Translator.translation('textEmpty').done(function(data){
                if (s.availability.val() == "") s.availability[0].setCustomValidity(data);
                if (s.area.val() == "") s.area[0].setCustomValidity(data);
                if (s.floor.val() == "") s.floor[0].setCustomValidity(data);
                if (s.rent.val() == "") s.rent[0].setCustomValidity(data);
                if (s.charge.val() == "") s.charge[0].setCustomValidity(data);
                if (s.deposit.val() == "") s.deposit[0].setCustomValidity(data);
            });
        }
        else
        {
            Translator.translation('textEmpty').done(function(data){
                if (s.selectZipCode.val() == "null") s.selectZipCode[0].setCustomValidity(data);
                if (s.inputStreet.val() == "") s.inputStreet[0].setCustomValidity(data);
                if (s.inputNumber.val() == "") s.inputNumber[0].setCustomValidity(data);
                if (s.availability.val() == "") s.availability[0].setCustomValidity(data);
                if (s.area.val() == "") s.area[0].setCustomValidity(data);
                if (s.floor.val() == "") s.floor[0].setCustomValidity(data);
                if (s.rent.val() == "") s.rent[0].setCustomValidity(data);
                if (s.charge.val() == "") s.charge[0].setCustomValidity(data);
                if (s.deposit.val() == "") s.deposit[0].setCustomValidity(data);
            });
            LoaderScript.loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCvRAuFQQ04OVRXeimrBPdMMHRnpMXYw8Q&language=fr");
            setTimeout(function(){ initializeMap(); }, 3000);
        }
        s.codeInput.attr('value', s.code);
    };
    var bindUIActions = function() {
        s.checkBox.checkboxradio({
            icon: false
        });
        $('#datetimepicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY-MM-DD',
            minDate: new Date()
        });
        if ((s.method != "addHousing") && (s.method != "updateHousing"))
        {
            s.selectZipCode.on('change', selectZipCode);
            s.selectCity.on('change', emptyField);
            s.inputNumber.on('change', createAddress);
            s.inputStreet.on('change', function(){validation(this,"text");});
        }
        s.checkBox.on('click', changeState);
        s.housingType.on('change', loadHousing);
        s.buttonAddPicture.on('click', click);
        s.availability.on('blur', selectAvailability);
        s.fileUploadPictures.on('change', function(event){ compression(event); });
        s.inputCapacity.on('change', function(){validation(this,"number");});
        s.inputSpace.on('change', function(){validation(this,"number");});
        s.area.on('change', function(){validation(this,"number");});
        s.floor.on('change', function(){validation(this,"number");});
        s.rent.on('change', function(){validation(this,"number");});
        s.charge.on('change', function(){validation(this,"number");});
        s.deposit.on('change', function(){validation(this,"number");});
        s.easeNearby.on('change', function(){validation(this,"text");});
        s.rentComment.on('change', function(){validation(this,"text");});
    };
    var uploadUIActions = function() {
        s.cancelUpload.on('click', cancelUpload);
        s.divUpload.on('mouseover', showButtonCancel);
        s.divUpload.on('mouseout', disappearButtonCancel);
    };
    var selectZipCode = function() 
    {
        if ($(this).val() != "null")
        {
            getCity($(this).val()); 
            s.selectZipCode[0].setCustomValidity('');
        }
        else
        {
            Translator.translation('textEmpty').done(function(data){
                s.selectZipCode[0].setCustomValidity(data);
            });
            s.selectCity.prop('disabled', true);
            s.inputStreet.prop('disabled', true);
            s.inputNumber.prop('disabled', true);
        }
    };
    var selectAvailability = function()
    {
        if (s.availability.val() != "") s.availability[0].setCustomValidity('');
        else
        {
            Translator.translation('textEmpty').done(function(data){
                s.availability[0].setCustomValidity(data);
            });
        }
    };
    var validation = function(element, type) 
    {
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
    var click = function()
    {
        s.fileUploadPictures.click();
    };
    var compression = function(event, type) {
        // verification de la compatibilité du navigateur avec  File
        if(window.File && window.FileList && window.FileReader)
        {
            // la liste complete de fichiers
            var files = event.target.files;
            var j = 0;
            var test = files.length;
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
                    img.name = s.code+'_'+uniquid();
                    img.onload = function(e) {
                        j++;
                        var orrigineW=img.width;
                        var orrigineH=img.height;
                        // appel de fuction qui defini les tailles finales
                        var taille=finalSize(orrigineW, orrigineH, s.maxW, s.maxH);
                        // creation du canvas 2d pour les miniatures
                        var canvasUpload = document.createElement('canvas');
                        canvasUpload.width = s.maxWMiniature;
                        canvasUpload.height = s.maxHMiniature;
                        var contextUpload = canvasUpload.getContext('2d');
 
                        // integration de notre image dans le canvas
                        if (taille.height<taille.width) contextUpload.drawImage(img, (orrigineW-orrigineH)/2,0, orrigineH, orrigineH,0,0,s.maxWMiniature,s.maxHMiniature);
                        else contextUpload.drawImage(img, 0, (orrigineH-orrigineW)/2, orrigineW, orrigineW,0,0,s.maxWMiniature,s.maxHMiniature);

                        // recuperation du nouveau fichier image en jpg compression qualité 80%
                        canvasDataUpload = canvasUpload.toDataURL('image/png', s.qualiteCompression)
                        
                        s.divUploadPictures.append(
                            '<div value="'+img.name+'" alt="new" class="carre">'+
                                '<img class="vignette" src="'+canvasDataUpload+'" style="max-width:'+s.tailleVignette+'px;">'+
                                '<i class="fa fa-times-circle cancelUploadHousing" aria-hidden="true"></i>'+
                            '</div>');
                        
                        uploadFile(canvasDataUpload, 'png', img.name, 'miniature');
                        delete canvasUpload;
                        delete canvasDataUpload;
                        delete contextUpload; 

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
                                                
                        uploadFile(canvasDataUpload, 'png', img.name, 'original');

                        delete canvasUpload;
                        delete canvasDataUpload;
                        delete contextUpload; 
                        delete taille;    
                        if (j == test)
                        {
                            s.cancelUpload.off();
                            s.divUpload.off();
                            s.cancelUpload = $('.cancelUploadHousing');
                            s.divUpload = $('.carre');
                            uploadUIActions();
                        }                
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
    var uploadFile = function(f, ext, n, t)
    {
        $.post("index.php?w=housing.addPicture",
            {file: f, extension : ext, name : n, type : t}
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
    var cancelUpload = function()
    {
        var selection = $(this).parent();
        $.post("index.php?r=housing.deletePicture",
            {picture : selection.attr("value"), status : selection.attr("alt")},
            function (result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                    selection.remove();
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
    var getCity = function(code)
    {
        $.post("index.php?r=housing.getCity",
            {zipCode : code},
            function (result)
            {
                s.selectCity.empty();
                s.inputStreet.val("");
                s.inputNumber.val("");
                var parsed = JSON.parse(result);                
                for(var iterator in parsed)
                {
                    s.selectCity.append($('<option>', {
                        value: parsed[iterator],
                        text: parsed[iterator]
                    }));
                }
                s.selectCity.prop('disabled', false);
                getStreet(code);
            }
        );
    };
    var getStreet = function(code)
    {
        $.post("index.php?r=housing.getStreet",
            {zipCode : code},
            function (result)
            {
                s.inputStreet.autocomplete({
                    source: JSON.parse(result)
                });
                s.inputStreet.prop('disabled', false);
                s.inputNumber.prop('disabled', false);
            }
        );
    };
    var emptyField = function()
    {
        s.inputStreet.val("");
        s.inputNumber.val("");
    };
    var initializeMap = function()
    {
        if (s.GPSPosition.val() != "")
        {
            var coordinated = s.GPSPosition.val().split(',');
            var latlng = {lat: parseFloat(coordinated[0]), lng: parseFloat(coordinated[1])};
            var map = new google.maps.Map(s.divMap[0], {
                zoom: 15,
                center: latlng
            });
            var marker = new google.maps.Marker({
                position: latlng,
                map: map
            }); 
        }
        else
        {
            if (window['google'] == undefined) setTimeout(initializeMap, 1000);
            s.geoCoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(50.466667,4.867222);
            var mapOptions = {
                zoom      : 14,
                center    : latlng
            }
            s.map = new google.maps.Map(s.divMap[0], mapOptions);
        }
    };
    var createAddress = function()
    {
        validation($(this)[0],"number");
        var address = s.inputNumber.val() + " " + s.inputStreet.val() + " " + s.selectZipCode.val() + " " + s.selectCity.val();
        findAddress(address, true);
    };
    var findAddress = function(address, redirection = false)
    {
        if (s.marker != null) s.marker.setMap(null);
        s.geoCoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                //Place le marker au milieu de la map
                s.map.setCenter(results[0].geometry.location);
                // Récupération des coordonnées GPS
                var strposition = results[0].geometry.location+"";
                strposition=strposition.replace('(', '');
                strposition=strposition.replace(')', '');
                s.GPSPosition.val(strposition);
                // Création du marqueur du lieu
                s.marker = new google.maps.Marker({
                    map: s.map,
                    animation: google.maps.Animation.DROP,
                    position: results[0].geometry.location
                });
            }
            else if (redirection) {
                var address = s.inputStreet.val() + " " + s.selectZipCode.val() + " " + s.selectCity.val();
                findAddress(address);
            }
            else
            {
                Translator.translation("noResultMap").done(function(data){
                    Notification.notification("warning", data);
                });
            }
        });
    };
    var changeState = function()
    {
        var element = $(this);
        if (element.attr("data-state") == "false")
        {
            element.attr("data-state", "true");
            Translator.translation("yes").done(function(data){
                element.prev().text(data);
            });
        }
        else
        {
            element.attr("data-state", "false");
            Translator.translation("no").done(function(data){
                element.prev().text(data);
            });
        }
    };
    var loadHousing = function()
    {
        switch($("#housingType option:selected").attr("data-type"))
        {
            case "kot" :
            {
                s.capacity.addClass("fade");
                s.infoHousing.addClass("fade");
                s.space.addClass("fade");
                s.inputCapacity[0].setCustomValidity('');
                s.inputSpace[0].setCustomValidity('');
                break;
            }
            case "studio" :
            {
                s.capacity.removeClass("fade");
                Translator.translation('textEmpty').done(function(data){
                    if (s.inputCapacity.val() == "") s.inputCapacity[0].setCustomValidity(data);
                });
                s.infoHousing.addClass("fade");
                s.space.addClass("fade");
                s.inputSpace[0].setCustomValidity('');
                break;
            }
            case "appartment" :
            case "house" : 
            {
                s.capacity.removeClass("fade");
                s.infoHousing.removeClass("fade");
                s.space.addClass("fade");
                s.inputSpace[0].setCustomValidity('');
                Translator.translation('textEmpty').done(function(data){
                    if (s.inputCapacity.val() == "") s.inputCapacity[0].setCustomValidity(data);
                });
                break;
            }
            case "flatsharing" :
            {
                s.capacity.removeClass("fade");
                s.infoHousing.removeClass("fade");
                s.space.removeClass("fade");
                Translator.translation('textEmpty').done(function(data){
                    if (s.inputCapacity.val() == "") s.inputCapacity[0].setCustomValidity(data);
                    if (s.inputSpace.val() == "") s.inputSpace[0].setCustomValidity(data);
                });
                break;
            }
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