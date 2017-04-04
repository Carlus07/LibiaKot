ModuleManager.loadModule("web/js/tools/Validation.js");
ModuleManager.loadModule("web/js/tools/Notification.js");

ActivityIndex = (function() {
    var s = {
        searchBox : $('.searchBoxActivity'),
        divTable : $('.tabResult'),
        data : $('.dataLabel'),
        table : $('#tableLabel'),
        dialog : $('#dialog-confirm'),
        language : $('.language')
    };

    var init = function() {
        bindUIActions();
    };

    var bindUIActions = function() {
        s.searchBox.on("change", displayTab);
    };
    var tableUIActions = function() {
        s.table.editableTableWidget({editor: $('<textarea>')});
        s.caseTable.on("change", validation);
        s.delete.on("click", deleteLabel);
    };
    var displayTab = function() {
        $.post("?r=activity.displayTab",
            {activity: $(this).val()},
            function(result)
            {
                s.data.empty();
                s.divTable.addClass("visible");
                var parsed = JSON.parse(result);
                var iterator = 1;
                
                for(var idLabel in parsed)
                {
                    var split = idLabel.split('+');
                    var nbrTranslation = 0;
                    var classTh = (split[0] == "NR") ? "deleteTranslation" : "notRemovable";
                    var labelId = (split[0] == "NR") ? split[1] : split[0];
                    var column = "<tr><th class=''><i class='fa fa-times "+classTh+" aria-hidden='true'></i></th><td value='text'";
                    for(var label in parsed[idLabel])
                    {
                        column = column+"alt='"+label+"'>"+label+"</td><input type='hidden' value='label' alt='"+labelId+"'/>";
                        for(var idTranslation in parsed[idLabel][label])
                        {
                            column = column+"<td value='text' data-order='"+nbrTranslation+"' alt='"+idTranslation+"''>";
                            nbrTranslation++;
                            for(var langue in parsed[idLabel][label][idTranslation])
                            {
                                column = column+parsed[idLabel][label][idTranslation][langue]+"</td><input type='hidden' value='"+langue+"' alt='"+idTranslation+"'/>";
                            }
                        }
                    }
                    var langues = s.language.attr("value").split("+");
                    for(var i = nbrTranslation; i < langues.length -1; i++)
                    {
                        column = column+"<td value='text' data-order='"+i+"'></td>";
                    }
                    column = column+"</tr>";
                    s.data.append(column);
                    iterator++;
                }
                addLineEmpty(s.language.attr("value"));
                s.caseTable = $('#tableLabel td');
                s.delete = $('.deleteTranslation');
                tableUIActions();
            }
        );
    };
    var validation = function() 
    {
        s.selection = $(this);
        value = $(this).attr("value");
        result = (value == "text") ? Validation.validationText($(this).text()) : Validation.validationNumber($(this).text()); 
        if (result == "") 
        {
            var type = $(this).next().attr('value');
            var id = $(this).next().attr('alt');
            var content = $(this).text();
            var labelOriginal = $(this).attr('alt');
            var order = $(this).attr('data-order');
            var idLabel = $(this).parent().find('td').first().next().attr("alt");
            if (id != 0)
            {
                if (type == "label") updateLabel(content, id);
                else if (id == undefined) addTranslation(idLabel, content, order);
                else updateTranslation(content, id);
            }
            else
            {
                var fields = $(this).parent().find('td');
                addLabel(fields);
            }
        }
        else 
        {
            Translator.translation(result).done(function(data){
                Notification.notification("warning", data);
            });
            return false;
        }
    };
    var addLineEmpty = function(str)
    {
        var langues = str.split('+');
        var line = '<tr><th></th><td value="text" alt="0"></td><input type="hidden" value="label" alt="0"/>';
        langues.forEach(function(key){
            if (key != "") line = line + '<td value="text" alt="0"></td><input type="hidden" value="'+key+'" alt="0"/>'
        });
        line = line + '</tr>';
        s.data.append(line);
    };
    var emptyFieldCheck = function(array)
    {
        var textEmpty = false;
        array.each(function(key) {
            if ($(this).text() == "")  textEmpty = true;
        });
        return textEmpty;
    };
    var updateLabel = function(content, id)
    {
        Translator.translation("confirmUpdate").done(function(data){
            s.dialog.html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+data+'</p>').dialog({
                title : "Confirmation",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Ok": function() {
                        s.dialog.dialog( "close" );
                        $.post("?w=label.update",
                            {text: content, idLabel : id},
                            function(result)
                            {
                                var str = result.split('+');
                                Translator.translation(str[1]).done(function(data){
                                    Notification.notification(str[0], data);
                                    return (str[0] == "success") ? true : false;
                                });
                            }
                        );
                    },
                    Cancel: function() {
                        s.dialog.dialog( "close" );
                        s.selection.text(labelOriginal);
                        return false;
                    }
                }
            });
        });
    };
    var addLabel = function(fields)
    {
        if (!emptyFieldCheck(fields))
        {
            var lab = "";
            var arrayTranslation = [];
            fields.each(function(key) {
                 if(key == 0) lab = $(this).text();
                 else arrayTranslation.push($(this).text());
            });
            $.post("?w=label.add",
                {activity : s.searchBox.val(), label: lab, translation : arrayTranslation},
                function(result)
                {
                    var str = result.split('+');
                    Translator.translation(str[1]).done(function(data){
                        Notification.notification(str[0], data);
                        if (str[0] == "success")
                        {
                            s.selection.parent().remove();
                            s.data.append(str[2]);
                            addLineEmpty(s.language.attr("value"));
                            s.caseTable = $('#tableLabel td');
                            s.delete = $('.deleteTranslation');
                            tableUIActions();
                        }
                    });
                }
            );
        }
    };
    var deleteLabel = function() {
        var tr = $(this).parent().parent();
        var inputs = $(this).parent().parent().find('input');
        var idLabel = 0;
        var idTranslation = [];
        inputs.each(function(key) {
            if($(this).attr('value') == "label") 
            {
                var split = $(this).attr('alt').split('+');
                idLabel = (split[0] == "NR") ? split[1] : split[0];
            }
            else idTranslation.push($(this).attr('alt'));
        });
        Translator.translation("confirmRemove").done(function(data){
            s.dialog.html('<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>'+data+'</p>').dialog({
                title : "Confirmation",
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: {
                    "Ok": function() {
                        s.dialog.dialog( "close" );
                        $.post("?p=label.delete",
                            {idLab: idLabel, idTranslations : idTranslation},
                            function(result)
                            {
                                var str = result.split('+');
                                Translator.translation(str[1]).done(function(data){
                                    if (str[0] == "success") tr.remove();
                                    Notification.notification(str[0], data);
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
    var updateTranslation = function(content, id)
    {
        $.post("?w=translation.update",
            {text: content, idTranslation : id},
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
    var addTranslation = function(label, content, ord)
    {
        console.log(ord);
        $.post("?w=translation.add",
            {idLabel: label, text: content, order: ord},
            function(result)
            {
                var str = result.split('+');
                Translator.translation(str[1]).done(function(data){
                    Notification.notification(str[0], data);
                });
            }
        );
    };
    return {
        init: init
    };
})();

