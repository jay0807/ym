var editor_style_1=new Abstract({style_init:function(){this.mce_handle=this.el;this.addEvent("click",this.status.bind(this));this.addEvent("click",function(){if(!this.$init){this.mce_handle.setOpacity(1);if(this.inc.doc.body.innerHTML.substr(0,6)=="&nbsp;")this.inc.doc.body.innerHTML=this.inc.doc.body.innerHTML.slice(6);var a=this;$ES("img",this.mce_handle).addEvent("click",function(){this.inc.win.focus()}.bind(this));$E(".ft_select",this.mce_handle).addEvent("change",function(){var b=this.getValue();
if(b){window.ie&&a.range&&a.range.select();a.set("fontName",b)}});$E(".fs_select",this.mce_handle).addEvent("change",function(){var b=this.getValue();if(b){window.ie&&a.range&&a.range.select();a.set("fontSize",b)}});$$($E(".fontColorPicker",this.el),$E(".fontBGColorPicker",this.el),$E(".ft_select",this.mce_handle),$E(".fs_select",this.mce_handle)).addEvent("click",function(){this.range=null;if(window.ie&&this.getSelection().type.toLowerCase()!="none")this.range=this.getRange();if(!window.ie)this.range=
this.getRange()}.bind(this));Ex_Loader("picker",function(){new GoogColorPicker($E(".fontColorPicker",this.el),{onSelect:function(b){window.ie&&this.range&&this.range.select();this.set("forecolor",b)}.bind(this),onShow:function(){window.ie&&this.range&&this.range.select()}.bind(this)});new GoogColorPicker($E(".fontBGColorPicker",this.el),{onSelect:function(b){if(window.ie){this.range&&this.range.select();return this.set("backColor",b)}this.set("hilitecolor",b)}.bind(this),onShow:function(){window.ie&&
this.range&&this.range.select()}.bind(this)})}.bind(this))}this.$init=true}.bind(this));this.styler=$ES(".x-section",this.el);this.stylerEl=$ES(".x-style",this.el);this.align=$ES(".x-align",this.el);this.setting=$ES(".x-enable",this.el)},status:function(a){new Event(a);if(!this.target)this.target=a.target.getElementsByTagName("body")[0]||a.target;a=this.target.style;if(a["background-color"]=="transparent")a["background-color"]="#fff";this.styler.setStyle("background-color",a["background-color"]==
"transparent"?"#fff":a["background-color"]);this.stylerEl.setStyle("color",a.color);var b=this.queryValues("Bold","Italic","Underline","strikeThrough","subscript","superscript","align","CreateLink","FontName","FontSize","ForeColor","FormatBlock","insertOrderedList","insertUnorderedList");if(b.align=="center"){this.align[0].parentNode.className="";this.align[1].parentNode.className="in";this.align[2].parentNode.className=""}else if(b.align=="right"){this.align[0].parentNode.className="";this.align[1].parentNode.className=
"";this.align[2].parentNode.className="in"}else{this.align[0].parentNode.className=b.align=="left"?"in":"";this.align[1].parentNode.className="";this.align[2].parentNode.className=""}this.setting.each(function(c){c.parentNode.className=b[c.getAttribute("value")]?"in":""})},set:function(a,b){if(this.inc&&this.inc.win){window.ie&&this.inc.win.focus();this.exec(a,b);try{this.status.call(this)}catch(c){}}}});function $cleanNull(a){for(p in a)a[p]||delete a[p];return a}
var mceInstance=new Class({Implements:[Events,Options],options:{acitve:false,maskOpacity:0.5,autoHeight:false,cleanup:true,includeBase:true},initialize:function(a,b){this.seri=a;var c="mce_body_"+a;this.el=$(c);this.setOptions(b);this.input=$E("textarea",this.el).setProperty("ishtml","true");this.frmContainer=$(c+"_frm_container");var d=this,f=this.options.includeBase,g=0,h=this.frm=(new Element("iframe",{src:DESKTOPRESFULLURL+"/about.html",id:c+"_frm",name:c+"_frm",frameborder:0,border:0})).addEvent("load",
function(){if(!g){g=1;var e=h.contentWindow,i="<html><head>"+(f?"<base href="+SHOPBASE+"/>":"")+'<script>window.onerror=function(){return false;}<\/script><link rel="stylesheet" type="text/css" href="'+DESKTOPRESURL+'/wysiwyg_editor.css"/></head><body spellcheck="false" id="'+a+'" style="break-word:break-all;word-wrap:break-word;">'+(d.cleanup(d.input.getProperty("value"))||"&nbsp;")+"</body></html>";e.document.open();e.document.write(i);e.document.close();e.document.designMode="on";e.document.addEventListener?
e.document.addEventListener("mousedown",d.active.bind(d),false):e.document.attachEvent("onmousedown",d.active.bind(d));d.win=e;d.doc=e.document;d.input.setAttribute("filled","true");this.removeEvent("load",arguments.callee)}});h.inject(this.frmContainer);this.input.getValue=function(){if(!this.input.getAttribute("filled"))return"textarea-unfilled";if("textarea"==this.editType)return this.input.value;var e=this.getValue();return this.input.value=e}.bind(this);this.options.autoHeight&&this.autoHeight.call(this);
if($(c))this.el=$(c).setStyle("visibility","visible");this.input.store("mce:instance",this)},autoHeight:function(){try{this.frm.setStyle("height",this.doc.body.offsetHeight+50)}catch(a){}},setValue:function(){this.doc.body.innerHTML=this.input.value},getValue:function(){return this.cleanup(this.doc.body.innerHTML)},regexpReplace:function(a,b,c,d){if(a==null)return a;if(typeof d=="undefined")d="g";return a.replace(RegExp(b,d),c)},cleanup:function(a){var b=[[/<br class\="webkit-block-placeholder">/gi,
"<br />"],[/<span class="Apple-style-span">(.*)<\/span>/gi,"$1"],[/ class="Apple-style-span"/gi,""],[/<span style="">/gi,""],[/^([\w\s]+.*?)<div>/i,"<p>$1</p><div>"],[/<div>(.+?)<\/div>/ig,"<p>$1</p>"]],c=[[/<br\s*\/?>/gi,"<br/>"],[/^<br\/?>/g,""],[/<br\/?>$/g,""],[/<br\/?>\s*<\/(h1|h2|h3|h4|h5|h6|li|p)/gi,"</$1"],[/<p>\s*<br\/?>\s*<\/p>/gi,"<p>\u00a0</p>"],[/<p>(&nbsp;|\s)*<\/p>/gi,"<p>\u00a0</p>"],[/<\/p>\s*<\/p>/g,"</p>"],[/<[^> ]*/g,function(d){return d.toLowerCase()}],[/<[^>]*>/g,function(d){return d=
d.replace(/ [^=]+=/g,function(f){return f.toLowerCase()})}],[/<[^>]*>/g,function(d){return d=d.replace(/( [^=]+=)([^"][^ >]*)/g,'$1"$2"')}]];c.extend([[/(<(?:img|input)[^\/>]*)>/g,"$1 />"]]);c.extend([[/<li>\s*<div>(.+?)<\/div><\/li>/g,"<li>$1</li>"],[/<span style="font-weight: bold;">(.*)<\/span>/gi,"<strong>$1</strong>"],[/<span style="font-style: italic;">(.*)<\/span>/gi,"<em>$1</em>"],[/<b\b[^>]*>(.*?)<\/b[^>]*>/gi,"<strong>$1</strong>"],[/<i\b[^>]*>(.*?)<\/i[^>]*>/gi,"<em>$1</em>"],[/<u\b[^>]*>(.*?)<\/u[^>]*>/gi,
'<span style="text-decoration: underline;">$1</span>'],[/<p>[\s\n]*(<(?:ul|ol)>.*?<\/(?:ul|ol)>)(.*?)<\/p>/ig,"$1<p>$2</p>"],[/<\/(ol|ul)>\s*(?!<(?:p|ol|ul|img).*?>)((?:<[^>]*>)?\w.*)$/g,"</$1><p>$2</p>"],[/<br[^>]*><\/p>/g,"</p>"],[/<p>\s*(<img[^>]+>)\s*<\/p>/ig,"$1\n"],[/<p([^>]*)>(.*?)<\/p>(?!\n)/g,"<p$1>$2</p>\n"],[/<\/(ul|ol|p)>(?!\n)/g,"</$1>\n"],[/><li>/g,">\n\t<li>"],[/([^\n])<\/(ol|ul)>/g,"$1\n</$2>"],[/([^\n])<img/ig,"$1\n<img"],[/^\s*$/g,""]]);Browser.Engine.webkit&&c.extend(b);c.each(function(d){a=
a.replace(d[0],d[1])});return a=a.trim()},active:function(){if(!this.actived){this.actived=true;var a=this.doc,b=function(c){this.fireEvent("docClick",new Event(c))}.bind(this);a.addEventListener?a.addEventListener("click",b,false):a.attachEvent("onclick",b)}this.fireEvent("active",this)},sleep:function(){}}),mceHandler=new Class({Implements:[Events,Options],initialize:function(a,b,c){try{this.el=$(a);$ES("img",this.el).each(function(f){new DropMenu(f)});this.setOptions(c);if(b)b.length?b.each(this.addInstance.bind(this)):
this.addInstance.call(this,b)}catch(d){alert(d.message)}"style_init"in this&&this.style_init()},addInstance:function(a){a.addEvent("active",this.active.bind(this));a.addEvent("docClick",this.docClick.bind(this))},active:function(a){(this.inc=a)&&this.inc.sleep.call(this.inc)},docClick:function(a){this.currentEl=a.target||a.srcElement;this.fireEvent("click",a)},getRange:function(){if(!this.inc)return false;if(this.range)return this.range;return window.ie?this.inc.doc.selection.createRange():this.inc.win.getSelection()},
getSelection:function(){return window.ie?this.inc.doc.selection:this.inc.win.getSelection()},getRangeText:function(){return window.ie?this.inc.doc.selection.createRange().text:this.inc.win.getSelection().toString()},exec:function(a,b){if(!this.busy){this.busy=true;if(a&&this.inc){if(this.dlg){window.ie&&this.range&&this.range.select();this.dlg.hide();this.dlg=null}switch(a){case "formatblock":this.inc.doc.execCommand("FormatBlock",false,"<"+b+">");break;case "wrap":this.exec("insertHTML",b[0]+this.getRangetext()+
b[1]);break;case "insertHTML":window.ie&&this.getSelection().clear();if(this.replaceEl&&this.replaceEl.tagName&&!this.replaceEl.tagName.match(/body/g))try{var c=this.inc.doc.createElement("div");c.innerHTML=b;c=c.firstChild;this.replaceEl.parentNode.replaceChild(c,this.replaceEl)}catch(d){MessageBox.error(d)}finally{this.replaceEl=null}else if(window.ie){this.inc.win.focus();if(!this.getRange().pasteHTML)return;this.getRange().pasteHTML(b)}else this.inc.doc.execCommand("inserthtml",false,b);break;
default:try{this.inc.doc.execCommand(a,false,b)}catch(f){MessageBox.error(f)}}this.busy=false}}},mklink:function(){if(this.inc){this.replaceEl=null;var a=this.currentEl,b;if(!("body"==a.tagName.toLowerCase()&&!this.getRangeText())){if(a&&a.tagName&&a.tagName.toLowerCase()=="img")return MessageBox.error(LANG_Editor.error);if(a&&a.tagName&&a.tagName.toLowerCase()=="a"){b={text:this.currentEl.innerHTML,href:this.currentEl.href,alt:this.currentEl.alt,title:this.currentEl.title,target:this.currentEl.target};
this.replaceEl=a}else b={text:this.getRangeText()};this.dialog("link",{height:null,width:450,ajaxoptions:{method:"post",data:b}})}}},editHTML:function(){var a=this;if(this.inc){var b=$("mce_handle_htmledit_"+this.inc.seri),c=$("mce_handle_"+this.inc.seri);this.inc.input.getValue();b.show();c.hide();this.inc.frm.getSize();this.inc.input.show();this.inc.frmContainer.hide();b.getElement(".returnwyswyg").addEvent("click",function(){c.show();b.hide();a.inc.doc.body.innerHTML=a.inc.input.value.clean().trim();
a.inc.input.hide();a.inc.frmContainer.show();a.inc.editType="wysiwyg";this.removeEvent("click",arguments.callee)});this.inc.editType="textarea"}},dialog:function(a,b){if(this.inc){this.inc.win.focus();this.range=null;this.range=this.getRange();var c="image"==a?"index.php?app=desktop&act=alertpages&goto="+encodeURIComponent("index.php?app=image&ctl=admin_manage&act=image_broswer&type=big"):"index.php?ctl=editor&act="+a;b=$cleanNull(b);var d=this;if("image"==a)return Ex_Loader("modedialog",function(){new imgDialog(c,
{onCallback:function(f,g){d.insertImg(f,g)}})});this.dlg=new Dialog(c,$merge(b,{modal:true}));window.curEditor=this}},insertImg:function(a,b,c){SHOPBASE&&b.contains(SHOPBASE);a=new Element("img",{src:b});var d="img"+$uid(a);a.set("id",d).set("turl",b);b=(new Element("div")).adopt(a);c=c?"<center>"+b.get("html")+"</center>":b.get("html");this.exec.bind(this)("insertHTML",c);if(c=this.inc.doc.getElementById(d)){c.src=a.get("turl");c.removeAttribute("turl","id")}},queryValue:function(a,b){if(a=="align")a=
"justifyRight";try{return b?this.inc.doc.queryCommandState(a):this.inc.doc.queryCommandValue(a)}catch(c){}},queryValues:function(){for(var a={},b=["Bold","Italic","Underline","strikeThrough","subscript","superscript","insertOrderedList","insertUnorderedList"],c=0;c<arguments.length;c++)a[arguments[c]]=this.queryValue(arguments[c],b.contains(arguments[c]));return a}});