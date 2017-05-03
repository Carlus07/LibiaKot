(function(yourcode) {
    yourcode(window.jQuery, window, document);
}(function($, window, document) {
    $(function() {
        // The DOM is ready!
        Main = (function() {
            var s = {
                dropdown : $(".dropdown-menu"),
                dropdownMenu : $(".dropdownMenu"),
                subMenu : $(".textSubMenu"),
                logo : $(".unamur"),
                width : $(window).width()
            };

            var init = function() {
                bindUIActions();
                activationMenu();
                if(s.width <= 767) deactivationMenu();
            };

            var bindUIActions = function() {
                $(window).resize(adjustment);
                s.subMenu.on('mouseover', function(){
                    $(this).css('color', '#333');
                })
                s.subMenu.on('mouseout', color);
                s.logo.on('mouseover', function(){
                    $(this).attr('src', 'web/pictures/unamur2.png');
                });
                s.logo.on('mouseout', function(){
                    $(this).attr('src', 'web/pictures/unamur.png');
                });
            };
            var adjustment = function()
            {
                s.width = $(window).width();
                if(s.width <= 767) deactivationMenu();
                else activationMenu();
            };
            var deactivationMenu = function()
            {
                s.dropdownMenu.off();
                s.dropdown.each(function(){
                    $(this).css("position", "relative");
                });
                s.subMenu.css('color', 'white');
            };
            var activationMenu = function()
            {
                s.subMenu.css('color', '#333');
                s.dropdownMenu.each(function(){
                    $(this).removeClass( "open" );
                });
                s.dropdown.each(function(){
                    $(this).css("position", "absolute");
                });
                s.dropdownMenu.on("mouseover", function(){ 
                    $(this).addClass( "open" );
                });
                s.dropdownMenu.on("mouseout", function(){ 
                    $(this).removeClass("open");
                });
            };
            var color = function()
            {
                if(s.width <= 767) $(this).css('color','white');
                else $(this).css('color','#333');
            };
            var refreshBind = function() {
                s.dropdown = $(".dropdown-menu");
                s.dropdownMenu = $(".dropdownMenu");
                s.subMenu = $(".textSubMenu");
                s.logo = $(".unamur");
                init();
            };
            return {
                init: init,
                refreshBind: refreshBind
            }
        })();

        ModuleManager = (function() {
            var s = {
                activeModule: [],
                moduleInHtml: $('#module').attr('data')
            };

            var init = function() {
                if (s.moduleInHtml != undefined) {
                    loadModule(s.moduleInHtml);
                }
            };

            var loadModule = function(pathMod) {
                if (pathMod != '')
                {
                    $.getScript(pathMod, function() {
                        var module = makeModuleName(pathMod);
                        if (typeof window[module]['init'] != "undefined") {
                            window[module]['init']();
                        }
                        s.activeModule.push(module);
                    });
                }
            };

            var makeModuleName = function(pathMod) {
                var str = pathMod.split('/');
                var acti = str[str.length - 2];
                var method = str[str.length - 1];
                method = method.substr(0, method.length - 3);
                return (acti == 'tools') ? method : acti.charAt(0).toUpperCase() + acti.substr(1) + method.charAt(0).toUpperCase() + method.substr(1);
            };

            var closeModules = function() {
                while (s.activeModule != undefined && s.activeModule.length != 0) {
                    var module = s.activeModule.pop();
                    //window[module]['close']();
                }
            };
            return {
                init: init,
                loadModule: loadModule,
                closeModules: closeModules
            }
        })();

        PageManager = (function() {
            var s = {
                body: $('body'),
                topBar : $('.account-login'),
                navTop : $('.navTopResult'),
                navBar : $('.navbar'),
                content : $('.content'),
                footer : $('.footerResult'),
                urlStack : ['?p=home.index']
            };

            var init = function() {
                bindUIActions();
            };

            var bindUIActions = function() {
                s.body.on('submit', 'form', function(event) {
                    event.preventDefault();
                    sendForm($(this));
                });
                s.body.on('click', 'a', function(event) {
                    event.preventDefault();
                    followLink($(this).attr('href'));
                });
                window.addEventListener("popstate", function(){
                    s.urlStack.pop();
                    var url = getUrl();
                    if (url == '?p=undefined') loadPage('?p=home.index', '', true);
                    else loadPage(s.urlStack[s.urlStack.length-1], '', true);
                });
            };
            var sendForm = function(form) {
                var urlToSend = form.attr('action');
                if (urlToSend == '') {
                    urlToSend = getUrl();
                }
                loadPage(urlToSend, form.serialize());
            };
            var getUrl = function() {
                var gets = getGET();
                var adress = '?p=' + gets['p'];
                delete(gets['p']);
                for (var key in gets) {
                    adress += '&' + key + '=' + gets[key];
                }
                return adress;
            };
            var getGET = function() {
                GETS = {};
                if (document.location.toString().indexOf('?') !== -1) {
                    var query = document.location
                        .toString()
                        // get the query string
                        .replace(/^.*?\?/, '')
                        // and remove any existing hash string
                        .replace(/#.*$/, '')
                        .split('&');

                    for (var i = 0, l = query.length; i < l; i++) {
                        var aux = decodeURIComponent(query[i]).split('=');
                        GETS[aux[0]] = aux[1];
                    }
                }
                return GETS;
            };
            var followLink = function(url) {
                if (undefined != url)
                {
                    if (url[0] == '?') {
                        loadPage(url);
                    }
                    else if (url[0] != '#') document.location.href=url;
                }
            };
            var loadPage = function(url, dataToAdd, back) {
                s.url = url || getUrl();
                dataToAdd = dataToAdd || '';
                back = back || false;
                if (!back) 
                {
                    length = s.urlStack.length;
                    if (length > 0)
                    {
                        if((s.urlStack[length-1] != s.url) && (s.url.substring(0,2) == "?p") && (s.url.substring(0,1) != "#")) s.urlStack.push(s.url);
                    }
                    else s.urlStack.push(s.url);
                }
                dataToSend = 'ajaxCall=true&' + dataToAdd;
                $.ajax({
                    type: "POST",
                    dataType: 'html',
                    url: s.url,
                    data: dataToSend,
                    success: function(html) {
                        var tabHtml = html.split('/-/');
                        refreshTop(tabHtml[1]);
                        refreshSearch(tabHtml[2]);
                        refreshMenu(tabHtml[3]);
                        refreshFooter(tabHtml[4]);
                        refreshContent(tabHtml[0], tabHtml[5], back);
                        refreshTitle(tabHtml[6]);
                    }
                });
            };
            var refreshTitle = function(tabTitleHtml) {
                if (tabTitleHtml != ' ') {
                    document.title = tabTitleHtml;
                }
            };
            var refreshTop = function(topBarHtml) {
                if (topBarHtml != ' ') {
                    s.topBar.fadeOut('fast',function() {
                        s.topBar.empty();
                        s.topBar.append(topBarHtml);
                        s.topBar.fadeIn('fast');
                    });
                }
            };
            var refreshSearch = function(navTopHtml)
            {
                if (navTopHtml != ' ') {
                    s.navTop.fadeOut('fast',function() {
                        s.navTop.empty();
                        s.navTop.append(navTopHtml);
                        s.navTop.fadeIn('fast');
                    });
                }
            };
            var refreshMenu = function(navBarHtml) {
                if (navBarHtml != ' ') {
                    s.navBar.fadeOut('fast',function() {
                        s.navBar.empty();
                        s.navBar.append(navBarHtml);
                        s.navBar.fadeIn('fast');
                    });
                }
            };
            var refreshFooter = function(footerHtml){
                if (footerHtml != ' ') {
                    s.footer.fadeOut('fast',function() {
                        s.footer.empty();
                        s.footer.append(footerHtml);
                        s.footer.fadeIn('fast');
                    });
                }
            };
            var refreshContent = function(contentHtml, module, back) {
                s.content.fadeOut('fast', function() {
                    if (!back) 
                    {
                        length = s.urlStack.length;
                        if (length > 1)
                        {
                            if((s.urlStack[length-2] != s.url) && (s.url.substring(0,2) == "?p") && (s.url.substring(0,1) != "#")) window.history.pushState({"html": contentHtml},"", s.url);
                        }
                        else if ((s.url.substring(0,2) == "?p") && (s.url.substring(0,1) != "#")) window.history.pushState({"html": contentHtml},"", s.url);
                    }
                    s.content.empty();
                    s.content.append(contentHtml);
                    s.content.fadeIn('fast');
                    ModuleManager.closeModules();
                    ModuleManager.loadModule(module);
                    Main.refreshBind();
                });
            };

            var refreshPage = function() {
                loadPage();
            };

            return {
                init: init,
                loadPage: loadPage,
                refreshPage: refreshPage
            };
        })();
        Translator = (function() {
            var translation = function(label) {
                return $.post("?o=lang.getTraductionByLabel",
                    {label: label}
                );
            };
            return {
                translation : translation
            }
        })();
        LoaderScript = (function() {
            var s = {
                scripts : []
            };
            var loadScript = function(script) {
                if (s.scripts.indexOf(script) == -1)
                {
                    var DSLScript  = document.createElement("script");
                    DSLScript.src = (script.match("http")) ? script : "web/js/libs/"+script+".js";
                    DSLScript.stype = "text/javascript";
                    s.scripts.push(script);
                    document.body.appendChild(DSLScript);
                    document.body.removeChild(DSLScript);
                }
            };
            return {
                loadScript : loadScript,
            }
        })();
        ModuleManager.init();
        Main.init();
        PageManager.init();
    })
}));