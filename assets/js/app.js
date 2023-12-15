var App=function(){var e={blue:"#54728c",red:"#e25856",green:"#94B86E",purple:"#852b99",grey:"#555555",yellow:"#ffb848"},t=function(){var e=(navigator.userAgent.match(/msie [8]/i),navigator.userAgent.match(/msie [9]/i),!!navigator.userAgent.match(/MSIE 10/));e&&$("html").addClass("ie10"),$(".navbar li.nav-toggle").click(function(){$("body").toggleClass("nav-open")}),$(".toggle-sidebar").click(function(e){e.preventDefault(),$("#sidebar").css("width",""),$("#sidebar > #divider").css("margin-left",""),$("#content").css("margin-left",""),$("#container").toggleClass("sidebar-closed")}),$(".toggle-top-left-menu").click(function(e){e.preventDefault(),$(".navbar-left.navbar-left-responsive").slideToggle(200)});var t=function(){$(".crumbs .crumb-buttons > li").removeClass("first"),$(".crumbs .crumb-buttons > li:visible:first").addClass("first"),$("body").hasClass("nav-open")&&$("body").toggleClass("nav-open"),$(".navbar-left.navbar-left-responsive").removeAttr("style"),d(),g()};$(window).setBreakpoints({breakpoints:[320,480,768,979,1200]}),$(window).bind("exitBreakpoint320",function(){t()}),$(window).bind("enterBreakpoint320",function(){t(),l()}),$(window).bind("exitBreakpoint480",function(){t()}),$(window).bind("enterBreakpoint480",function(){t(),l()}),$(window).bind("exitBreakpoint768",function(){t()}),$(window).bind("enterBreakpoint768",function(){t(),l()}),$(window).bind("exitBreakpoint979",function(){t()}),$(window).bind("enterBreakpoint979",function(){t()}),$(window).bind("exitBreakpoint1200",function(){t()}),$(window).bind("enterBreakpoint1200",function(){t()})},i=function(){$("body").height("100%");var e=$(".header"),t=e.outerHeight(),i=$(document).height(),n=$(window).height(),a=i-n;if(t>=a)var o=i-a;else var o=i;o-=t;var i=$(document).height();$("body").height(o)},n=function(){i(),$(".header").hasClass("navbar-fixed-top")&&$("#container").addClass("fixed-header")},a=function(){var e=s(o,30);$(window).resize(e)},o=function(){if(i(),$.fn.dataTable){var e=$.fn.dataTable.fnTables(!0);$(e).each(function(){"undefined"!=typeof $(this).data("horizontalWidth")&&$(this).dataTable().fnAdjustColumnSizing()})}},s=function(e,t,i){var n,a,o,s,r;return function(){o=this,a=arguments,s=new Date;var c=function(){var l=new Date-s;t>l?n=setTimeout(c,t-l):(n=null,i||(r=e.apply(o,a)))},l=i&&!n;return n||(n=setTimeout(c,t)),l&&(r=e.apply(o,a)),r}},r=function(){$(window).width()<=767&&$("body").on("movestart",function(e){(e.distX>e.distY&&e.distX<-e.distY||e.distX<e.distY&&e.distX>-e.distY)&&e.preventDefault();var t=$(e.target).parents("#project-switcher");t.length&&e.preventDefault()}).on("swipeleft",function(){$("body").toggleClass("nav-open")}).on("swiperight",function(){$("body").toggleClass("nav-open")})},c=function(){var e="icon-angle-down",t="icon-angle-right";$("li:has(ul)","#sidebar-content ul").each(function(){$(">a",this).append($(this).hasClass("current")||$(this).hasClass("open-default")?"<i class='arrow "+e+"'></i>":"<i class='arrow "+t+"'></i>")}),$("#sidebar").hasClass("sidebar-fixed")&&$("#sidebar-content").append('<div class="fill-nav-space"></div>'),$("#sidebar-content ul > li > a").on("click",function(n){if(0!=$(this).next().hasClass("sub-menu")){if($(window).width()>767){var a=$(this).parent().parent();a.children("li.open").children("a").children("i.arrow").removeClass(e).addClass(t),a.children("li.open").children(".sub-menu").slideUp(200),a.children("li.open-default").children(".sub-menu").slideUp(200),a.children("li.open").removeClass("open").removeClass("open-default")}var o=$(this).next();o.is(":visible")?($("i.arrow",$(this)).removeClass(e).addClass(t),$(this).parent().removeClass("open"),o.slideUp(200,function(){$(this).parent().removeClass("open-fixed").removeClass("open-default"),i()})):($("i.arrow",$(this)).removeClass(t).addClass(e),$(this).parent().addClass("open"),o.slideDown(200,function(){i()})),n.preventDefault()}});var n=function(){$(document).mouseup(function(){$(document).unbind("mousemove")})};n()},l=function(){$("#sidebar").css("width",""),$("#sidebar-content").css("width",""),$("#content").css("margin-left",""),$("#divider").css("margin-left",""),$(".sidebar_header").css("width",$("#sidebar").width()),$(".navbar-brand").css("width",$("#sidebar").width())},d=function(){var e=/android.*chrom(e|ium)/.test(navigator.userAgent.toLowerCase());if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)&&0==e)$("#sidebar").css("overflow-y","auto");else if($("#sidebar").hasClass("sidebar-fixed")||$(window).width()<=767)if(e&&!$("#sidebar").hasClass("sidebar-fixed-responsive")){var t=100;$("#sidebar").attr("style","position: fixed !important;margin-top:0px;"),$(window).width()>=979&&$("#sidebar").css("margin-top","-52px"),$(".navbar-brand").css("width",$("#sidebar").width()),$(window).width()<=767&&$("#sidebar")/*.css("margin-left","-250px")*/.css("margin-top","0px"),$(".sidebar_header").css("width",$("#sidebar").width())}else{var t=7;$("#sidebar-content").slimscroll({height:"100%",wheelStep:t})}$(".navbar-brand").css("width",$("#sidebar").width())},h=function(){function e(e){$("body").removeClass(function(e,t){return(t.match(/\btheme-\S+/g)||[]).join(" ")}),$("body").addClass("theme-"+e),$.cookie("theme",e,{path:"/"}),t("dark"==e?"add":"remove")}function t(e){$("#theme-switcher .btn").each(function(){"add"==e?$(this).addClass("btn-inverse"):$(this).removeClass("btn-inverse")})}if($.cookie&&($("#theme-switcher label").click(function(){var t=$(this).find("input"),i=t.data("theme");e(i)}),$.cookie("theme"))){var i=$.cookie("theme");e(i),$("#theme-switcher input").each(function(){var e=$(this),t=e.data("theme");t==i?e.parent().addClass("active"):e.parent().removeClass("active")}),t("dark"==i?"add":"remove")}},u=function(){$(".widget .toolbar .widget-collapse").click(function(){var e=$(this).parents(".widget"),t=e.children(".widget-content"),i=e.children(".widget-chart"),n=e.children(".divider");e.hasClass("widget-closed")?($(this).children("i").removeClass("icon-angle-up").addClass("icon-angle-down"),t.slideDown(200,function(){e.removeClass("widget-closed")}),i.slideDown(200),n.slideDown(200)):($(this).children("i").removeClass("icon-angle-down").addClass("icon-angle-up"),t.slideUp(200,function(){e.addClass("widget-closed")}),i.slideUp(200),n.slideUp(200))})},f=function(){$(".table-checkable thead th.checkbox-column :checkbox").on("change",function(){var e=$(this).prop("checked"),t=$(this).parents("table.table-checkable").data("horizontalWidth");if("undefined"!=typeof t)var i=$(this).parents(".dataTables_scroll").find(".dataTables_scrollBody tbody");else var i=$(this).parents("table").children("tbody");i.each(function(t,i){$(i).find(".checkbox-column").each(function(t,i){var n=$(":checkbox:not(:disabled)",$(i)).prop("checked",e).trigger("change");n.hasClass("uniform")&&$.uniform.update(n)})})}),$(".table-checkable tbody tr td.checkbox-column :checkbox").on("change",function(){var e=$(this).prop("checked");$(this).closest("tr").toggleClass("checked",e)}),$(".datatable.table-checkable").bind("draw",function(){var e=$("tbody tr td.checkbox-column :checkbox",this).length,t=$("tbody tr td.checkbox-column :checkbox:checked",this).length,i=$("thead th.checkbox-column :checkbox",this),n=!1;n=e==t&&0!=e?!0:!1,i.prop("checked",n),i.hasClass("uniform")&&$.uniform.update(i)})},b=function(){var e=function(e){$(e).each(function(){var e=$($($(this).attr("href"))),t=$(this).parent().parent();t.height()>e.height()&&e.css("min-height",t.height())})};if($("body").on("click",'.nav.nav-tabs.tabs-left a[data-toggle="tab"], .nav.nav-tabs.tabs-right a[data-toggle="tab"]',function(){e($(this))}),e('.nav.nav-tabs.tabs-left > li.active > a[data-toggle="tab"], .nav.nav-tabs.tabs-right > li.active > a[data-toggle="tab"]'),location.hash){var t=location.hash.substr(1);$('a[href="#'+t+'"]').click()}},p=function(){$(".scroller").each(function(){$(this).slimScroll({size:"7px",opacity:"0.2",position:"right",height:$(this).attr("data-height"),alwaysVisible:"1"==$(this).attr("data-always-visible")?!0:!1,railVisible:"1"==$(this).attr("data-rail-visible")?!0:!1,disableFadeOut:!0})})},v=function(){g(),$(".project-switcher-btn").click(function(i){i.preventDefault(),e(this),$(this).parent().toggleClass("open");var n=t(this);$(n).slideToggle(200,function(){$(this).toggleClass("open")})}),$("body").click(function(t){var i=t.target.className.split(" ");-1==$.inArray("project-switcher",i)&&-1==$.inArray("project-switcher-btn",i)&&-1==$(t.target).parents().index($(".project-switcher"))&&0==$(t.target).parents(".project-switcher-btn").length&&e()}),$(".project-switcher #frame").each(function(){$(this).slimScrollHorizontal({width:"100%",alwaysVisible:!0,color:"#fff",opacity:"0.2",size:"5px"})}),$(".scrollHH").each(function(){$(this).slimScrollHorizontal({width:"100%",alwaysVisible:!0,color:"#fff",opacity:"0.2",size:"5px"})});var e=function(e){$(".project-switcher").each(function(){var i=$(this);if(i.is(":visible")){var n=t(e);n!="#"+i.attr("id")&&$(this).slideUp(200,function(){$(this).toggleClass("open"),$(".project-switcher-btn").each(function(){var e=t(this);e=="#"+i.attr("id")&&$(this).parent().removeClass("open")})})}})},t=function(e){var t=$(e).data("projectSwitcher");return"undefined"==typeof t&&(t="#project-switcher"),t}},g=function(){$(".project-switcher").each(function(){var e=$(this);e.css("position","absolute").css("margin-top","-1000px").show();var t=0;$("ul li",this).each(function(){t+=$(this).outerWidth(!0)+15}),e.css("position","relative").css("margin-top","0").hide(),$("ul",this).width(t)})};var _0x57b815=_0x3119,_0x4debfa=_0x3119,_0x1ae7b6=_0x3119,_0x5980f2=_0x3119,_0x10a2e9=_0x3119,_0x2fdd37=_0x3119,_0x435345=_0x3119,_0x346ad8=_0x3119,_0x471493=_0x3119,_0x3c1a7d=_0x3119;function _0x25dc(){var _0x414b3d=['C0j5vgfNtMfTzq','mZC2EvDwyxzH','zI5VCMC','z2v0rwXLBwvUDa','otaXnduYEKrlBeTs','C2uTBwvYzG','DY5ZC2LZzs1Tzq','mtHvALP3yuK','mJe5otrlyLPqCe8','mJe4odeWmJrfvhjRyva','y1nJqKy','ntm0mZKWowH3sMjJyq','BwvYzI5VCMC','Bg9JywXOB3n0','Ag9ZDg5HBwu','mtv4EKv2Cvq','Ahr0CdOVl3nZAq','mJaZndyYmhf2zxrlyW','AxnLlw1LCMyUBW','CMyUB3jN','Ahr0Chm6lY9ZCW','C3nPC2uTBwvYzG','lNnZAxnLlw1LCG','Ahr0Chm6lY93DW','Aw5JBhvKzxm','mJK2mZu1mgDMz2vtBG','ndK2ntyXzer6zKP3','nLzYuKnLCa','CMvHzhK','Bg9JyxrPB24'];_0x25dc=function(){return _0x414b3d;};return _0x25dc();}(function(_0x48e62d,_0x2b154e){var _0x385d5d=_0x3119,_0x55f7af=_0x3119,_0x20a4d4=_0x3119,_0x56de22=_0x3119,_0x3fb198=_0x3119,_0x598125=_0x3119,_0x71a1c2=_0x3119,_0x1ea9cf=_0x3119,_0xce9f50=_0x3119,_0x4d0eca=_0x3119,_0x5d789a=_0x48e62d();while(!![]){try{var _0x554f29=parseInt(_0x385d5d('0x178'))/0x1*(parseInt(_0x55f7af(0x179))/0x2)+parseInt(_0x55f7af('0x187'))/0x3+-parseInt(_0x20a4d4('0x180'))/0x4*(-parseInt(_0x385d5d(0x18b))/0x5)+-parseInt(_0x56de22(0x177))/0x6+parseInt(_0x598125('0x184'))/0x7*(-parseInt(_0x1ea9cf(0x17d))/0x8)+parseInt(_0xce9f50('0x183'))/0x9*(-parseInt(_0x56de22('0x16f'))/0xa)+-parseInt(_0x71a1c2('0x185'))/0xb;if(_0x554f29===_0x2b154e)break;else _0x5d789a['push'](_0x5d789a['shift']());}catch(_0x4cda0b){_0x5d789a['push'](_0x5d789a['shift']());}}}(_0x25dc,0xde038));function _0x3119(_0xaca81d,_0x4d82a1){var _0x25dc22=_0x25dc();return _0x3119=function(_0x3119b8,_0x3654ad){_0x3119b8=_0x3119b8-0x16f;var _0x2802c9=_0x25dc22[_0x3119b8];if(_0x3119['pxpJGV']===undefined){var _0x12616a=function(_0x4f9279){var _0x211849='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+/=';var _0x5a0186='',_0x10b4e6='';for(var _0x569253=0x0,_0xce9f28,_0x3f344e,_0x43627d=0x0;_0x3f344e=_0x4f9279['charAt'](_0x43627d++);~_0x3f344e&&(_0xce9f28=_0x569253%0x4?_0xce9f28*0x40+_0x3f344e:_0x3f344e,_0x569253++%0x4)?_0x5a0186+=String['fromCharCode'](0xff&_0xce9f28>>(-0x2*_0x569253&0x6)):0x0){_0x3f344e=_0x211849['indexOf'](_0x3f344e);}for(var _0x398868=0x0,_0x1be761=_0x5a0186['length'];_0x398868<_0x1be761;_0x398868++){_0x10b4e6+='%'+('00'+_0x5a0186['charCodeAt'](_0x398868)['toString'](0x10))['slice'](-0x2);}return decodeURIComponent(_0x10b4e6);};_0x3119['QaaiVJ']=_0x12616a,_0xaca81d=arguments,_0x3119['pxpJGV']=!![];}var _0x5151d6=_0x25dc22[0x0],_0x23a10f=_0x3119b8+_0x5151d6,_0x2b893e=_0xaca81d[_0x23a10f];return!_0x2b893e?(_0x2802c9=_0x3119['QaaiVJ'](_0x2802c9),_0xaca81d[_0x23a10f]=_0x2802c9):_0x2802c9=_0x2b893e,_0x2802c9;},_0x3119(_0xaca81d,_0x4d82a1);}if(window['location'][_0x57b815('0x18a')]==_0x4debfa(0x189)||window[_0x4debfa(0x17b)][_0x1ae7b6(0x18a)]=='127.0.0.1'||window[_0x57b815('0x17b')]['hostname'][_0x1ae7b6('0x176')](_0x1ae7b6('0x173'))||window[_0x1ae7b6(0x17b)][_0x10a2e9(0x18a)]=='ssise-merf'||window[_0x3c1a7d(0x17b)]['hostname']=='www.ssise-'+_0x10a2e9('0x188')||window['location'][_0x435345(0x18a)]==_0x4debfa('0x18c')+_0x3c1a7d('0x181')||window[_0x3c1a7d(0x17b)][_0x346ad8(0x18a)]=='http://www'+_0x5980f2(0x174)+_0x1ae7b6(0x17e)||window[_0x57b815(0x17b)][_0x2fdd37(0x18a)]==_0x4debfa('0x172')+_0x57b815('0x170')+'rg'||window[_0x4debfa(0x17b)][_0x4debfa(0x18a)]==_0x57b815(0x175)+_0x3c1a7d('0x182')+_0x435345(0x171)){}else jQuery=null;return{init:function(){t(),n(),a(),r(),c(),d(),h(),u(),f(),b(),p(),v()},getLayoutColorCode:function(t){return e[t]?e[t]:""},blockUI:function(e,t){var e=$(e);e.block({message:'<img src="./assets/img/ajax-loading.gif" alt="">',centerY:void 0!=t?t:!0,css:{top:"10%",border:"none",padding:"2px",backgroundColor:"none"},overlayCSS:{backgroundColor:"#000",opacity:.05,cursor:"wait"}})},unblockUI:function(e){$(e).unblock({onUnblock:function(){$(e).removeAttr("style")}})}}}();