(function(){finderGroup={};this.finderDestory=function(){for(var a in finderGroup)delete finderGroup[a]};var s=function(a,d,c){d=d||"controller";var b={},e={};e[d]=e[d]||{};e[d][c[0]]=c[1];b[a]=e;return b},t=new Class({Extends:Drag,start:function(a){this.parent(a)}});Finder=new Class({Implements:[Events],options:{selectName:"items[]"},detailStatus:{},initialize:function(a,d){$extend(this.options,d);this.id=a;this.initStaticView();this.initView();this.listContainer=this.list.getContainer();this.attachStaticEvents();
this.attachEvents();this.options.packet&&this.loadPacket()},initStaticView:function(){$each(["action","form","filter","search","tip","header","footer","pager","packet"],function(a){this[a]=$("finder-"+a+"-"+this.id)},this)},initView:function(){this.list=$("finder-list-"+this.id).store("visibility",true);this.tip=$("finder-tip-"+this.id)},isVisibile:function(){if(!this.list.retrieve)return false;return $chk(this.list.retrieve("visibility"))},attachStaticEvents:function(){var a=this;if(a.search){var d=
a.search.getElement("input[search]").addEvent("keypress",function(b){if(b.code===13){b.stop();if(!this.value.trim().length)a.filter.value="";b=a.form.retrieve("rowselected",[]);a.refresh(b.length&&!b.contains("_ALL_")&&!confirm(LANG_Finder.refresh_confirm))}});a.search.getElement(".finder-search-btn").addEvent("click",function(){d.fireEvent("keypress",{stop:$empty,code:13})})}a.action&&a.action.getElements("*[submit]").addEvent("click",function(b){b&&b.stop&&b.stop();var e=this.get("target");b=this.get("submit");
var h=a.form.retrieve("rowselected"),l=a.form.retrieve("_rowindex",$H());h=h[0]=="_ALL_"?h:[];!h.length&&l&&l.getValues().sort(function(p,f){return p.toInt()-f.toInt()}).each(function(p){h.push(l.keyOf(p))});var k=new Element("form"),i=document.createDocumentFragment(),n=false;h.each(function(p){var f=a.options.selectName;if(p=="_ALL_")n=f="isSelectedAll";i.appendChild(new Element("input",{type:"hidden",name:f,value:p}))});k.appendChild(i);if(!k.getFirst())return MessageBox.error(LANG_Finder.error);
var q=e,o={};if(e&&e.contains("::")){q=e.split("::")[0];o=JSON.decode(e.split("::")[1]);if($type(o)!="object")o={}}e=this.getProperty("confirm");if(!(e&&!window.confirm(e))){if(n){e=a.form.action.match(/\?([\s\S]+$)/);e=e[1]?e[1]:"";e=[e,a.form.toQueryString(),a.filter.value].join("&");k.adopt(e.toFormElements())}switch(q){case "refresh":W.page(b,$extend({data:k,method:"post",onComplete:a.refresh.bind(a)},o));break;case "command":new cmdrunner(actionurl,{onSuccess:a.refresh.bind(a)});break;case "dialog":new Dialog(b,
$extend({title:this.get("dialogtitle")||this.get("text"),ajaxoptions:{data:k,method:"post"},onClose:function(){a.unselectAll();a.refresh.call(a)}},o));break;case "_blank":b=k.set({action:b,name:q,target:"_blank",method:"post"}).inject(document.body);b.submit();b.remove.delay(1E3,b);break;default:W.page(b,$extend({data:k,method:"post"},o))}}});if(a.header){a.header.addEvent("click",function(b){var e=$(b.target);e.hasClass("orderable")||(e=e.getParent(".orderable"));if(e){e=["desc"==e.get("order")?
"asc":"desc",e.get("key")].link({"_finder[orderType]":String.type,"_finder[orderBy]":String.type});a.fillForm(e).refresh();b.stopPropagation()}});var c=function(){try{a.header.setStyles({width:a.listContainer.clientWidth-a.listContainer.getPatch().x})}catch(b){}};c();LAYOUT.content_main.addEvent("resizelayout",c);a.header.addEvent("dispose",function(){LAYOUT.content_main.removeEvent("resizelayout",c)})}},selectAll:function(a){this.header.getElement(".sellist")&&this.header.getElement(".sellist").set("checked",
!a).fireEvent("change");if(a){this.form.retrieve("rowselected").empty();this.form.retrieve("_rowindex",$H()).empty();this.tip.fireEvent("_hide")}else{this.form.retrieve("rowselected").empty().push("_ALL_");this.tip.fireEvent("_update","selectedall").fireEvent("_show")}},unselectAll:function(){this.selectAll(true)},selectFav:function(a){this.form.retrieve("rowselected").empty();this.form.retrieve("_rowindex",$H()).empty();this.list.getElements(".row .sel").each(function(d){d.hasClass("isfav")?d.set("checked",
!a).fireEvent("change"):d.set("checked",!!a).fireEvent("change")})},selectunFav:function(){this.selectFav(true)},attachEvents:function(){var a=this,d=this.listContainer;a.list.retrieve("eventInfo",{});var c=a.form.retrieve("rowselected",[]);if(a.header&&a.list.getElement("tr")){var b=a.header.getElement(".finder-header"),e=b.getElements(".finder-col-resizer"),h=b.getElements("col"),l=b.getElement("tr").getChildren(),k=a.list.getElements("col");new t(b,{modifiers:{x:false,y:false},limit:{x:[35,1E3]},
handle:Array.from(e),onStart:function(f,g){f.addClass("col-resizing");var j;j=g.target.getParent(".cell")?g.target.getParent(".cell").getParent("td"):g.target.getParent("td")?g.target.getParent("td"):g.target;var m=l.indexOf(j);if(m<0)return this.cancel();j=l[m].getElement(".finder-col-resizer");f.store("_dragTargetIndex",m);h[m].addClass("resizing").setStyle("background","#e9e9e9");m=f.retrieve("_dragTargetMoveEl");if(!m){m=(new Element("div",{"class":"resize-move-el",styles:{height:a.header.offsetHeight+
d.offsetHeight,width:j.offsetWidth,position:"absolute",top:j.getPosition().y,left:j.getPosition().x,background:"#e9e9e9",zIndex:65535,cursor:"col-resize",opacity:0.8,borderRight:"1px #cccccc solid"}})).inject(document.body);f.store("_dragTargetMoveEl",m)}},onDrag:function(f){f.retrieve("_dragTargetMoveEl").setStyle("left",this.mouse.now.x)},onComplete:function(f){f.removeClass("col-resizing");var g=f.retrieve("_dragTargetIndex"),j=h[g].removeClass("resizing").setStyle("background","");if(f.retrieve("_dragTargetMoveEl")){f.retrieve("_dragTargetMoveEl").dispose();
f.eliminate("_dragTargetMoveEl");f=this.mouse.now.x-this.mouse.start.x;var m=j.getStyle("width").toInt();f=(m+f).limit(this.options.limit.x[0],this.options.limit.x[1]);var r=$$(j,k[g]);j=a.list.getElement("tr").getChildren()[g];if(window.webkit)r=$$(r,l[g],j);r.setStyle("width",f);g=d.scrollLeft;r=d.offsetWidth;var u=d.scrollWidth;if(g>0&&g+r>=u){m=f-m;if(m<0)d.scrollLeft=(d.scrollLeft-Math.abs(m)).limit(0,d.scrollWidth)}if(j){m=j.get("key");EventsRemote.post({events:s("finder_colset",a.options.object_name+
"_"+a.options.finder_aliasname,[m,f])})}}}})}if(a.tip){a.tip.addEvents({_update:function(f,g){this.retrieve("arg:class","NULL")!=f&&$$(this.childNodes).hide();var j=this.getElement("."+f);if(j){j.innerHTML=j.innerHTML.replace(/<em>([\s\S]*?)<\/em>/ig,function(){return"<em>"+g+"</em>"});j.setStyle("display","block")}this.store("arg:class",f);this.retrieve("tipclone")||this.store("tipclone",(new Element("div",{"class":"hide",html:"&nbsp;",styles:{height:this.offsetHeight}})).injectTop(d))},_show:function(){if(this.style.visibility==
"hidden"){this.setStyle("visibility","visible");this.retrieve("tipclone").removeClass("hide")}},_hide:function(){if(this.style.visibility!="hidden"){this.setStyle("visibility","hidden");this.retrieve("tipclone").addClass("hide")}}});var i=c.length;if(i>1)i==a.tip.get("count").toInt()||c.contains("_ALL_")?a.tip.fireEvent("_update",["selectedall",i]).fireEvent("_show"):a.tip.fireEvent("_update",["selected",i]).fireEvent("_show")}a.list.addEvents({selectrow:function(f){f.getParent(".row").addClass("selected")},
unselectrow:function(f){f.getParent(".row").removeClass("selected")}});var n=a.list.getElements(".row .sel");a.rowCount=n.length;if(a.header&&a.header.getElement(".sellist"))var q=a.header.getElement(".sellist").addEvent("change",function(){n.set("checked",this.checked).fireEvent("change")});n.addEvents({click:function(){this.fireEvent("change")},focus:function(){this.blur()},change:function(){if(q){c[this.checked?"include":"erase"](this.value);var f=a.form.retrieve("_rowindex",$H());this.checked?
f.set(this.value,this.get("rowindex")):f.erase(this.value)}else c.empty().push(this.value);if(!this.checked&&c.contains("_ALL_")){c.erase("_ALL_");return a.unselectAll()}i=c.length;var g=0;if(i>1)if(i==a.tip.get("count").toInt()||c.contains("_ALL_"))a.tip.fireEvent("_update",["selectedall",i]).fireEvent("_show");else{a.tip.fireEvent("_update",["selected",i]);g=function(){$clear(g);if(!(c.length<2)){if(a.list.retrieve("eventState")=="mousedown")return g=arguments.callee.delay(200);a.tip.fireEvent("_show")}}.delay(200)}else{$clear(g);
a.tip.fireEvent("_update",["selected"]).fireEvent("_hide")}a.list.fireEvent(this.checked?"selectrow":"unselectrow",this)}});n.filter(function(f){return c&&c.push&&(c.contains(f.value)||c.contains("_ALL_"))}).set("checked",true).fireEvent("change");(b=a.list.getElement("tr[item-id="+a.detailStatus.rowId+"]"))&&a.showDetail(b.getElement("span[detail]").get("detail"),{},b);a.list.addEvent("click",function(f){var g=$(f.target);if(g){if(g.match("img"))g=$(g.parentNode);if(g.hasClass("fav-star")){g.toggleClass("fav-star-on");
f=g.getParent("tr[item-id]");f.getElement(".sel").toggleClass("isfav");return EventsRemote.post({events:s("finder_favstar",a.options.object_name+"_"+a.options.finder_aliasname,["id-"+f.get("item-id"),g.hasClass("fav-star-on")?1:0])})}var j=g.get("detail");if(j){f.stopPropagation();return a.showDetail(j,{},g.getParent(".row"))}if(g.hasClass("cell")||g.hasClass("cell-inside"))g=g.getParent("td");if(g.match("td"))if(/row/.test(g.parentNode.className))if(a.detailStatus.row)if((f=g.getParent(".row").getElement("*[detail]"))&&
!g.getParent(".row").hasClass("view-detail"))return a.showDetail(f.get("detail"),{},g.getParent(".row"))}});attachEsayCheck(a.list,"td:nth-child(first) .span-auto");var o=0,p=function(){$clear(o);o=function(){if(this.listContainer.scrollLeft!=this.header.scrollLeft){var f=this.header.scrollLeft=this.listContainer.scrollLeft,g=this.listContainer.getElement(".finder-detail-content");g&&g.setStyle("margin-left",f)}this.tip&&this.tip.style.visibility!="none"&&this.tip.setStyles({left:this.listContainer.scrollLeft,
top:this.listContainer.scrollTop})}.delay(200,this)}.bind(this);p();this.listContainer.addEvent("scroll",p);this.list.addEvent("dispose",function(){this.listContainer.removeEvent("scroll",p)}.bind(this));this.cellOpts.call(this)},fillForm:function(a){if(!(!a||"object"!=$type(a))){a=$H(a);var d=this;a.each(function(c,b){(d.form.getElement("input[name^="+b.slice(0,-1)+"]")||(new Element("input",{type:"hidden",name:b})).inject(d.form)).set("value",c)});return d}},eraseSelected:function(){var a=this,
d=a.form.retrieve("rowselected",[]);if(d[0]!="_ALL_"){var c=a.list.getElements(".row .sel");$splat(arguments).flatten().each(function(b){var e=c.filter(function(h){return h.value==b});if(e.length)e.set("checked",false).fireEvent("change");else{d.erase(b);a.tip.fireEvent("_update",["selected",d.length])}})}},eraseFormElement:function(){var a=Array.flatten(arguments),d=this;$each(a,function(c){d.form.getElement("input[name="+c+"]").remove()});return d},showDetail:function(a,d,c){var b=this,e=c.getNext();
d=c.get("item-id");if(this.detailCurTab&&this.detailCurTab[0]==d)a=this.detailCurTab[1];if(e&&e.hasClass("finder-detail"))return this.hideDetail(c,e);this.hideDetail(this.detailStatus.row,this.detailStatus.dp);var h=new Element("tr",{"class":"finder-detail"}),l=new Element("td",{colspan:c.getElements("td").length,"class":"finder-detail-colspan"}),k=(new Element("div",{"class":"finder-detail-content clearfix",id:"finder-detail-"+this.id})).set({container:true});h.adopt(l.adopt(k));var i=this.list.getContainer();
(e=this.detailStatus.Request)&&e.cancel();this.detailStatus.row=c.addClass("view-detail");this.detailStatus.rowId=d;this.detailStatus.Request=(new Request.HTML({evalScripts:false,url:a+(a.indexOf("&")>0?"&":"")+"finder_name="+this.id,onRequest:function(){new MessageBox(LANG_Finder.detail.request,{type:"notice"})},onComplete:function(n,q,o,p){h.injectAfter(c);new MessageBox(LANG_Finder.detail.complete,{autohide:1});W.render(k.set("html",o));var f=function(){try{k.setStyle("width",i.clientWidth-l.getPatch().x)}catch(g){}};
f();LAYOUT.content_main.addEvent("resizelayout",f);b.list.addEvent("dispose",function(){LAYOUT.content_main.removeEvent("resizelayout",f)});$globalEval(p)}.bind(this),onFailure:function(){new MessageBox(LANG_Finder.detail.failure+[this.xhr.status],{type:"error",autohide:true})}})).send().chain(function(){delete this.detailStatus.Request;this.detailStatus.dp=h}.bind(this))},hideDetail:function(a,d){a&&a.removeClass("view-detail");d&&d.remove();delete this.detailCurTab;delete this.detail;delete this.detailStatus.row;
delete this.detailStatus.dp;delete this.detailStatus.rowId},getFormQueryString:function(){return this.form.toQueryString()},page:function(a){this.form.store("page",a||1);this.request({method:this.form.method||"post"})},loadPacket:function(){var a=this.packet;this.options.packet&&(new Request.HTML({url:this.form.action+"&action=packet",update:a,onRequest:function(){a.addClass("loading")},onComplete:function(){a.removeClass("loading")}})).get()},storeTab:function(){var a=$("finder-detail-"+this.id);
if(a)if(a=a.getElement(".finder-tabs-wrap .current"))this.detailCurTab=[a.get("item-id"),a.get("url")]},refresh:function(a){this.storeTab();this.request({method:this.form.method||"post",onComplete:function(){this.loadPacket();a&&this.unselectAll()}.bind(this)})},filter2packet:function(){var a=this.filter.value;a&&new Dialog(this.form.action+"&action=filter2packet",{width:400,height:200,ajaxoptions:{method:"post",data:$H({filterquery:a,finder_id:this.id})}})},setCount:function(){var a=this.tip.get("count").toInt(),
d=$E(".finder-title .count");d&&d.setText(a);return this},request:function(){var a=Array.flatten(arguments).link({options:Object.type,action:String.type});a.action=a.action||this.form.action+"&page="+(this.form.retrieve("page")||1);a.options=a.options||{};var d=a.options.onComplete;if($type(d)!="function")d=$empty;$extend(a.options,{clearUpdateMap:false,updateMap:{".innerheader":this.header,".pager":this.pager},onComplete:function(){this.initView();this.setCount().attachEvents();d.apply(this,Array.flatten(arguments));
var e=$("filter-tip-"+this.id);if(e)this.filter.value.trim().length?e.setStyle("visibility","visible").highlight("#FFFFCC"):e.setStyle("visibility","hidden")}.bind(this)});if(this.search&&this.search.getElement("input[search]").value.trim().length)this.filter.value=this.search.toQueryString();var c=this.getFormQueryString().concat("&"+this.filter.value),b=a.options.data;switch($type(b)){case "string":a.options.data=[c,b].join("&");break;case "object":case "hash":a.options.data=[c,Hash.toQueryString(b)].join("&");
break;case "element":a.options.data=[c,$(b).toQueryString()].join("&");break;default:a.options.data=c}for(v in this.detailStatus)$type(this.detailStatus[v])=="element"&&delete this.detailStatus[v];W.page(a.action,a.options)},cellOpts:function(){var a=this,d=a.list.getElements(".opt-handle");d&&d.each(function(c){var b=c.getSize().x,e=c.getPatch().x;new DropMenu(c,{eventType:"mouse",stopState:true,offset:{x:b-e-3,y:0},relative:$("main"),delay:0,size:true,onPosition:function(h){if(!this.offset)this.offset=
this.options.offset.x;if(h=="x"){h=c.getParent("td").getSize().x;h=b>h?h-e-12:this.offset;this.options.offset.x=this.options.relative.getScroll().x+h;this.options.offset.y=this.options.relative.getScroll().y}},onHide:function(){this.element.style.position="static"},onInitShow:function(){if(a.detailStatus.rowId)this.status=true},onShow:function(h){this.element.style.position="relative";if(!this.bind){h.getElements("a").addEvent("click",function(l){var k=this.get("submit")||this.get("url"),i=this.get("target");
if(i&&k){l.preventDefault();l=i.split("::")[0]||i;var n=JSON.decode(i.split("::")[1])||{};switch(l){case "dialog":var q=new Dialog(k,$extend(n,{onLoad:function(){this.dialog.getElement("form").store("target",{onComplete:function(o){q.close();if(o)a.refresh();else return MessageBox.error("REFRESH ERROR!")}})}}));break;case "tab":a.showDetail(k,n,this.getParent("tr"));break;case "request":(new Request({url:k,method:"post",data:n.data,onComplete:function(){a.showDetail(n.url,{},this.getParent("tr"))}.bind(this)})).send();
break;case "confirm":if((i=this.get("confirm"))&&!confirm(i))break;W.page(k,{onComplete:function(){a.refresh()}})}}});this.bind=true}}})})}});Filter=new Class({Implements:[Events,Options],options:{onPush:$empty,onRemove:$empty,onChange:$empty},initialize:function(a,d,c){this.finderId=d;this.filter=$(a);this.finderObj=window.finderGroup[d];this.setOptions(c)},update:function(){var a=this.filter,d=a.toQueryString(function(c){var b=$(c).getParent("dl");if(!(!b||!b.isDisplay()||!$(c).value)){if(b=c.name.match(/_([\s\S]+)_search/))if(!a.getElement("*[name="+
b[1]+"]").value)return;if(c.name.match(/_DTYPE_TIME/))if(!a.getElement("*[name="+c.value+"]").value)return;if(b=c.name.match(/_DTIME_\[([^\]]+)\]\[([^\]]+)\]/))if(!a.getElement("*[name="+b[2]+"]").value)return;return true}},true);if(this.finderObj.search)this.finderObj.search.getElement("input[search]").value="";this.finderObj.filter.value=d;d=this.finderObj.form.retrieve("rowselected",[]);if(d.length&&!d.contains("_ALL_")&&!confirm(LANG_Finder.refresh_confirm)){this.finderObj.form.eliminate("rowselected");
this.finderObj.refresh(true)}else this.finderObj.refresh();this.fireEvent("change")},retrieve:function(){var a=this.finderObj.filter.value||"";if(this.finderObj.search)this.finderObj.search.getElement("input[search]").value="";var d=this;a.replace(/([^&]+)\=([^&]+)/g,function(){var c=arguments,b=c[1],e=d.filter.getElement("[name="+b+"]");if(b&&b.slice(-1)&&b.slice(-1)=="]")e=d.filter.getElement("[name^="+b.substr(0,b.length-1)+"]");if(e)e.value=decodeURIComponent(c[2])})}})})();
(function(){var s=new Tips({onShow:function(c,b){b.addClass("active");var e;c.setStyle("display","block").store("tip:imgsource",e=$pick(b.get("href"),b.get("src")));var h=c.getElement(".tip-text").set("html","&nbsp;").addClass("loading");Asset.image(e,{onload:function(){if(this.src==c.retrieve("tip:imgsource")){h.empty().adopt(this.zoomImg(h.offsetWidth,h.offsetHeight)).removeClass("loading");this.setStyle("margin-top",(h.offsetHeight-this.height)/2)}}})},text:function(){return"&nbsp;"},className:"finder-col-img-tip"}),
t=new Tips({onShow:function(c,b){b.addClass("active");c.setStyle("display","block");var e=b.retrieve("loaded:html"),h=c.getElement(".tip-text");if(e)return h.set("html",e);h.set("html","&nbsp;").addClass("loading");(new Request({url:b.get("data-load"),onSuccess:function(l){h.removeClass("loading").empty().set("html",l);b.store("loaded:html",l)}})).get()},text:function(){return"&nbsp;"},className:"finder-col-desc-tip"}),a=new Tips({onShow:function(c,b){b.addClass("active");c.setStyle("display","block")},
text:function(c){return c.get("title")||c.get("rel")},className:"finder-col-text-tip"}),d=new Tips({onShow:function(c,b){b.addClass("active");c.setStyle("display","block")},text:function(c){return c.getElement("textarea").value},className:"finder-col-desc-tip"});this.bindFinderColTip=function(c){c=new Event(c);var b=c.target;if(b){b.onmouseover=null;if(b.hasClass("img-tip"))s.attach(b);else if(b.hasClass("desc-tip"))d.attach(b);else b.hasClass("load-tip")?t.attach(b):a.attach(b);b.addEvent("mouseleave",
function(){this.removeClass("active")});b.fireEvent("mouseenter",c)}}})();