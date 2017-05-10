HousingView = (function() {
    var s = {
        slider : $( "#slider-range" ),
        sliderBedroom : $( "#slider-range-min" ),
        amountRent : $( "#amountRent"),
        amoutBedroom : $('#amountBedroom'),
        reference : $('#reference'),
        content : $('.contentHousing'),
        type : $('#housingType'),
        rentDuration : $('#rentalDuration'),
        map : null,
        divMap : $("#map-canvas"),
        housings : $('.marker')
    };

    var init = function() {
        bindUIActions();
        s.amountRent.val(s.slider.slider( "values", 0 ) + "€ - " + s.slider.slider( "values", 1 ) + "€");
        s.amoutBedroom.val(s.sliderBedroom.slider( "value" ) );
        if (s.divMap.length != 0)
        {
            LoaderScript.loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCvRAuFQQ04OVRXeimrBPdMMHRnpMXYw8Q&language=fr");
            setTimeout(function(){ initializeMap(); }, 3000);
        }
    };

    var bindUIActions = function() {
        s.slider.slider({
            range: true,
            min: 250,
            max: 1200,
            values: [250, 1200],
            slide: function( event, ui ) {
                s.amountRent.val(ui.values[ 0 ] + "€ - " + ui.values[ 1 ] + "€");
            }
        });
        s.sliderBedroom.slider({
            range: "min",
            min: 1,
            max: 10,
            value: 1,
            slide: function( event, ui ) {
                s.amoutBedroom.val( ui.value );
            }
        });
        s.slider.on("slidechange", loadByOthers);
        s.reference.on('change', loadByReference);
        s.type.on('change', loadByOthers);
        s.sliderBedroom.on("slidechange", loadByOthers);
        s.rentDuration.on("change", loadByOthers);
    };
    var loadByReference = function()
    {
        var selection = $(this).val();
        $.post("?w=housing.getHousingByReference",
            {reference: selection},
            function(result)
            {
                s.content.empty();
                var housing = JSON.parse(result);
                if (housing != '')
                {
                    var reference = housing.reference;
                    for (var i = 0; i < 5-Math.floor(Math.log(housing.reference) + 1); i++)
                    {
                        reference = '0'+reference;
                    }
                    reference = "LK "+reference;
                    var content = '<a href="?p=housing.viewHousing&id='+housing.id+'">'+
                                    '<div class=" col-md-4 col-sm-4 col-xs-12">'+
                                        '<div class="row frameUser" style="padding:5px;margin:0px;cursor:pointer;">'+
                                            '<div class="col-sm-12">'+
                                                '<h4 style="margin:0;"><span><i class="fa fa-tags" aria-hidden="true"></i></span>'+reference+'</h4>'+
                                             '</div>'+
                                            '<div class="col-sm-12 text-center" style="margin-bottom: 15px;">'+
                                                '<img class="img-responsive pictureUserList" src="'+housing.picture+'"/>'+
                                            '</div>'+
                                            '<div class="col-sm-12">'+
                                                '<h4 style="margin:0;color:#55ab26;">'+housing.label+'</h4>'+
                                            '</div>';
                    if (housing.capacity > 1)
                    {
                        content = content + '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;">';
                                                for (var i = 1; i <= housing.capacity; i++)
                                                {
                                                    content = content + '<span><i class="fa fa-child" aria-hidden="true"></i></span>';
                                                }
                        content = content +     '</h5>'+
                                            '</div>';
                    }
                    content = content +     '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>'+housing.city+'  -  '+housing.rent+'<span><i class="fa fa-eur" aria-hidden="true"></i></span></h5>'+
                                            '</div>'+
                                            '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;font-size:12px"><span><i class="fa fa-calendar" aria-hidden="true"></i></span>'+housing.availability+'</h5>'+
                                            '</div>'+
                                          '</div>'+
                                        '</div>'+
                                    '</a>';
                    s.content.append(content);
                }
                else
                {
                    Translator.translation("noResult").done(function(data){
                        s.content.append('<div class="col-sm-8 col-xs-12" style="margin-bottom:25px;">'+
                                        '<div class="row">'+
                                            '<div class="error col-xs-offset-1 col-xs-10 col-xs-offset-1 text-center">'+
                                                '<div class="col-xs-4">'+
                                                    '<img src="web/pictures/notFound.png" class="errorPicture img-responsive"/>'+
                                                '</div>'+
                                                '<div class="col-xs-8 messagePicture">'+
                                                    '<h3>'+data+'</h3>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>');
                    });
                }
            }
        );
    };
    var loadByOthers = function()
    {
        $.post("?w=housing.getHousingByOthers",
            {type: s.type.val(), rent : s.slider.slider("values"), bedroom : s.sliderBedroom.slider("value"), rentDuration : s.rentDuration.val()},
            function(result)
            {
                console.log(s.content);
                s.content.empty();
                var housings = JSON.parse(result);
                if (housings != '')
                {
                    for(var key in housings)
                    {
                        var reference = housings[key].reference;
                        for (var i = 0; i < 5-Math.floor(Math.log(housings[key].reference) + 1); i++)
                        {
                            reference = '0'+reference;
                        }
                        reference = "LK "+reference;
                        var content =  '<a href="?p=housing.viewHousing&id='+housings[key].id+'">'+
                                    '<div class=" col-md-4 col-sm-4 col-xs-12">'+
                                    '<div class="row frameUser" style="padding:5px;margin:0px;cursor:pointer;">'+
                                        '<div class="col-sm-12">'+
                                            '<h4 style="margin:0;"><span><i class="fa fa-tags" aria-hidden="true"></i></span>'+reference+'</h4>'+
                                         '</div>'+
                                        '<div class="col-sm-12 text-center" style="margin-bottom: 15px;">'+
                                            '<img class="img-responsive pictureUserList" src="'+housings[key].picture+'"/>'+
                                        '</div>'+
                                        '<div class="col-sm-12">'+
                                            '<h4 style="margin:0;color:#55ab26;">'+housings[key].label+'</h4>'+
                                        '</div>';
                        if (housings[key].capacity > 1)
                        {
                            content = content + '<div class="col-sm-12">'+
                                                    '<h5 style="margin:0;">';
                                                    for (var i = 1; i <= housings[key].capacity; i++)
                                                    {
                                                        content = content + '<span><i class="fa fa-child" aria-hidden="true"></i></span>';
                                                    }
                            content = content +     '</h5>'+
                                                '</div>';
                        }
                        content = content + '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>'+housings[key].city+'  -  '+housings[key].rent+'<span><i class="fa fa-eur" aria-hidden="true"></i></span></h5>'+
                                            '</div>'+
                                            '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;font-size:12px"><span><i class="fa fa-calendar" aria-hidden="true"></i></span>'+housings[key].availability+'</h5>'+
                                            '</div>'+
                                          '</div>'+
                                        '</div>'+
                                        '</a>';
                        s.content.append(content);
                    }
                }
                else
                {
                    Translator.translation("noResult").done(function(data){
                        s.content.append('<div class="col-sm-8 col-xs-12" style="margin-bottom:25px;">'+
                                        '<div class="row">'+
                                            '<div class="error col-xs-offset-1 col-xs-10 col-xs-offset-1 text-center">'+
                                                '<div class="col-xs-4">'+
                                                    '<img src="web/pictures/notFound.png" class="errorPicture img-responsive"/>'+
                                                '</div>'+
                                                '<div class="col-xs-8 messagePicture">'+
                                                    '<h3>'+data+'</h3>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>');
                    });
                }
            }
        );
    };
    var initializeMap = function()
    {
        if (window['google'] == undefined) setTimeout(initializeMap, 1000);
        s.geoCoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(50.466667,4.867222);
        var mapOptions = {
            zoom      : 14,
            center    : latlng
        }
        var map = new google.maps.Map(s.divMap[0], mapOptions);

       s.housings.each(function(){
            var myLatlng = new google.maps.LatLng($(this).attr('data-latitude'), $(this).attr('data-longitude'));
            var reference = $(this).attr('data-reference');
            for (var i = 0; i < 5-Math.floor(Math.log($(this).attr('data-reference')) + 1); i++)
            {
                reference = '0'+reference;
            }
            reference = "LK "+reference;
            var contentString =  '<a href="?p=housing.viewHousing&id='+$(this).attr('data-id')+'">'+
                                    '<div class="text-center col-xs-12">'+
                                    '<div class="row frameUser" style="width=100%;padding:5px;margin:0px;cursor:pointer;">'+
                                        '<div class="col-sm-12">'+
                                            '<h4 style="margin:0;"><span><i class="fa fa-tags" aria-hidden="true"></i></span>'+reference+'</h4>'+
                                         '</div>'+
                                        '<div class="col-sm-12 text-center" style="margin-bottom: 15px;">'+
                                            '<img style="width:50%;" lass="img-responsive pictureUserList" src="'+$(this).attr('data-picture')+'"/>'+
                                        '</div>'+
                                        '<div class="col-sm-12">'+
                                            '<h4 style="margin:0;color:#55ab26;">'+$(this).attr('data-type')+'</h4>'+
                                        '</div>';
                        if ($(this).attr('data-capacity') > 1)
                        {
                            contentString = contentString + '<div class="col-sm-12">'+
                                                    '<h5 style="margin:0;">';
                                                    for (var i = 1; i <= $(this).attr('data-capacity'); i++)
                                                    {
                                                        contentString = contentString + '<span><i class="fa fa-child" aria-hidden="true"></i></span>';
                                                    }
                            contentString = contentString +     '</h5>'+
                                                '</div>';
                        }
                        contentString = contentString + '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;"><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>'+$(this).attr('data-city')+'  -  '+$(this).attr('data-rent')+'<span><i class="fa fa-eur" aria-hidden="true"></i></span></h5>'+
                                            '</div>'+
                                            '<div class="col-sm-12">'+
                                                '<h5 style="margin:0;font-size:12px"><span><i class="fa fa-calendar" aria-hidden="true"></i></span>'+$(this).attr('data-availability')+'</h5>'+
                                            '</div>'+
                                          '</div>'+
                                        '</div>'+
                                        '</a>';



            var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 250
            });
            var marker = new google.maps.Marker({
                position: myLatlng,
                title:"LK "+$(this).attr('data-reference')
            });
            marker.setMap(map);
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
       });        
    };
    return {
        init: init
    }
})();