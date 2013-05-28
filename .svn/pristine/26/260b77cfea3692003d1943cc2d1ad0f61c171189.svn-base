var DatePickers = new Class({

    Implements: [Events, Options],

    options: {
        onShow: function(datepicker){
         if(Browser.ie6)
              $$('select').setStyle('visibility','hidden');
            datepicker.setStyle('visibility', 'visible');
        },
        onHide: function(datepicker){
              if(Browser.ie6)
              $$('select').setStyle('visibility','visible');
            datepicker.setStyle('visibility', 'hidden').setStyle('left',-300);
        },
        showDelay: 100,
        hideDelay: 100,
        className: 'calendar',
        offsets: {x: 0, y: 20},

        dateformat: '-',

        days: ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FIR', 'STA'], // days of the week starting at sunday
        months: LANG_formplus['months'],
        weekFirstDay : 1 // first day of the week: 0 = sunday, 1 = monday, etc..
    },

    initialize: function(){
        var params = Array.link(arguments, {options: Type.isObject, elements: function(e){return e !=null;}});
        this.setOptions(params.options || {});
        this.lock = false;

        this.datepicker = new Element('div').addEvents({
            'mouseover': function(){
                this.lock = true;
            }.bind(this),
            'mouseout': function(){
                this.lock = false;
            }.bind(this)
        });

        if (this.options.className) this.datepicker.addClass(this.options.className);

        var top = new Element('div', {'class': 'datepicker-top'}).inject(this.datepicker);
        this.container = new Element('div', {'class': 'datepicker'}).inject(this.datepicker);
        var bottom = new Element('div', {'class': 'datepicker-bottom'}).inject(this.datepicker);

        this.datepicker.setStyles({position: 'absolute', top: 0, left: 0, visibility: 'hidden'});

        if (params.elements){
            params.elements.each(function(el){
              el.store('bindColorPicker',true);

            });
            this.attach(params.elements);
        }
    },

    attach: function(elements){
        $$(elements).each(function(element){
            var dateformat = element.retrieve('datepicker:dateformat', element.get('accept'));
            if(!dateformat) {
                dateformat = this.options.dateformat;
                element.store('datepicker:dateformat', dateformat);
            }
            var datevalue = element.retrieve('datepicker:datevalue', element.get('value'));
            if(!datevalue) {
                datevalue = this.format(new Date(), dateformat);
                element.store('datepicker:datevalue', datevalue);
            }
            element.store('datepicker:current', this.unformat(datevalue,dateformat));

            var inputFocus = element.retrieve('datepicker:focus', this.elementFocus.bindWithEvent(this, element));
            var inputBlur = element.retrieve('datepicker:blur', this.elementBlur.bindWithEvent(this, element));
            element.addEvents({focus: inputFocus, blur: inputBlur});

            element.store('datepicker:native', element.get('accept'));
            element.erase('dateformat');
        }, this);
        return this;
    },

    detach: function(elements){
        $$(elements).each(function(element){
            element.removeEvent('onfocus', element.retrieve('datepicker:focus') || function(){});
            element.removeEvent('onblur', element.retrieve('datepicker:blur') || function(){});
            element.eliminate('datepicker:focus').eliminate('datepicker:blur');
            var original = element.retrieve('datepicker:native');
            if (original) element.set('dateformat', original);
        });
        return this;
    },

    elementFocus: function(event, element){
        if(!this.datepicker.retrieve('injected')){
           this.datepicker.inject(document.body);
           this.datepicker.store('injected',true);
        }
        this.el = element;

        var current = element.retrieve('datepicker:current');
        this.curFullYear = current[0];
        this.curMonth = current[1];
        this.curDate = current[2];

        this.build();

        this.timer = clearTimeout(this.timer);
        this.timer = this.show.delay(this.options.showDelay, this);

        this.position({page: element.getPosition()});
    },

    elementChange: function() {

        if(this.el.get('real')){
            var curDateObj = new Date(this.curFullYear, this.curMonth, this.curDate);
            var now = new Date();

            if(curDateObj<new Date(now.getFullYear(),now.getMonth(),now.getDate())){
                this.hide();
                return alert(LANG_formplus['dateerror']);
            }
        }


        this.el.store('datepicker:current', Array(this.curFullYear,this.curMonth,this.curDate));
        this.el.set('value',this.format(new Date(this.curFullYear, this.curMonth, this.curDate),this.el.retrieve('datepicker:dateformat')));

        clearTimeout(this.timer);
        this.timer = this.hide.delay(this.options.hideDelay, this);
    },

    elementBlur: function(event){
        if(!this.lock) {
            clearTimeout(this.timer);
            this.timer = this.hide.delay(this.options.hideDelay, this);
        }
    },

    position: function(event){
        var size = window.getSize(), scroll = window.getScroll();
        var datepicker = {x: this.datepicker.offsetWidth, y: this.datepicker.offsetHeight};
        var props = {x: 'left', y: 'top'};
        for (var z in props){
            var pos = event.page[z] + this.options.offsets[z];
            if ((pos + datepicker[z] - scroll[z]) > size[z]) pos = event.page[z] - this.options.offsets[z] - datepicker[z];
            this.datepicker.setStyle(props[z], pos);
        }
    },

    show: function(){
        this.fireEvent('show', this.datepicker);
    },

    hide: function(){
        this.fireEvent('hide', this.datepicker);
    },

    build: function() {
        Array.from(this.container.childNodes).each(Element.dispose);

        var table = new Element('table').set({cellpadding:'0',cellspacing:'0'}).inject(this.container);
        var caption = this.caption().inject(table);
        var thead = this.thead().inject(table);
        var tbody = this.tbody().inject(table);
    },

    // navigate: calendar navigation
    // @param type (str) m or y for month or year
    // @param d (int) + or - for next or prev
    navigate: function(type, d) {
        switch (type) {
            case 'm': // month
                var i = this.curMonth + d;

                if (i < 0 || i == 12) {
                    this.curMonth = (i < 0) ? 11 : 0;
                    this.navigate('y', d);
                }
                else
                    this.curMonth = i;

                break;
            case 'y': // year
                this.curFullYear += d;

                break;
        }

        this.el.store('datepicker:current', Array(this.curFullYear,this.curMonth,this.curDate));

        this.el.focus(); // keep focus and do automatique rebuild ;)
    },

    // caption: returns the caption element with header and navigation
    // @returns caption (element)
    caption: function() {
        // start by assuming navigation is allowed
        var navigation = {
            prev: { 'month': true, 'year': true },
            next: { 'month': true, 'year': true }
        };

        var caption = new Element('caption');

        var prev = new Element('a').addClass('prev').appendText('\x3c'); // <
        var next = new Element('a').addClass('next').appendText('\x3e'); // >



        var year = new Element('span').addClass('year').inject(caption);
        if (navigation.prev.year) { prev.clone().addEvent('click', function() { this.navigate('y', -1); }.bind(this)).inject(year); }
        new Element('span').set('text', this.curFullYear).addEvent('mousewheel', function(e){ e.stop();this.navigate('y', (e.wheel < 0 ? -1 : 1));this.build();}.bind(this)).inject(year);
        if (navigation.next.year) { next.clone().addEvent('click', function() { this.navigate('y', 1); }.bind(this)).inject(year); }
        var month = new Element('span').addClass('month').inject(caption);
        if (navigation.prev.month) { prev.clone().addEvent('click', function() { this.navigate('m', -1); }.bind(this)).inject(month); }
        new Element('span').set('text', this.options.months[this.curMonth]).addEvent('mousewheel', function(e){ e.stop();this.navigate('m', (e.wheel < 0 ? -1 : 1));this.build();}.bind(this)).inject(month);
        if (navigation.next.month) { next.clone().addEvent('click', function() { this.navigate('m', 1); }.bind(this)).inject(month); }
        return caption;
    },

    // thead: returns the thead element with day names
    // @returns thead (element)
    thead: function() {
        var thead = new Element('thead');
        var tr = new Element('tr').inject(thead);
        for (i = 0; i < 7; i++) {
            new Element('th').set('text', this.options.days[(this.options.weekFirstDay + i)%7].substr(0, 3)).inject(tr);
        }

        return thead;
    },

    // tbody: returns the tbody element with day numbers
    // @returns tbody (element)
    tbody: function() {
        var d = new Date(this.curFullYear, this.curMonth, 1);

        var offset = ((d.getDay() - this.options.weekFirstDay) + 7) % 7; // day of the week (offset)
        var last = new Date(this.curFullYear, this.curMonth + 1, 0).getDate(); // last day of this month
        var prev = new Date(this.curFullYear, this.curMonth, 0).getDate(); // last day of previous month

        var v = (this.el.get('value')) ? this.unformat(this.el.get('value'),this.el.retrieve('datepicker:dateformat')) : false;
        var current = new Date(v[0], v[1], v[2]).getTime();
        var d = new Date();
        var today = new Date(d.getFullYear(), d.getMonth(), d.getDate()).getTime(); // today obv

        var tbody = new Element('tbody');

        tbody.addEvent('mousewheel', function(e){
            e.stop(); // prevent the mousewheel from scrolling the page.
            this.navigate('m', (e.wheel < 0 ? -1 : 1));
            this.build();
        }.bind(this));

        for (var i = 1; i < 43; i++) { // 1 to 42 (6 x 7 or 6 weeks)
            if ((i - 1) % 7 == 0) { tr = new Element('tr').inject(tbody); } // each week is it's own table row

            var td = new Element('td').inject(tr);
            var day = i - offset;
            var date = new Date(this.curFullYear, this.curMonth, day);

            if (day < 1) { // last days of prev month
                day = prev + day;
                td.addClass('inactive');
            }
            else if (day > last) { // first days of next month
                day = day - last;
                td.addClass('inactive');
            }
            else {
                if(date.getTime() == current)  { td.addClass('hilite');  }
                else if (date.getTime() == today) { td.addClass('today');  } // add class for today

                td.addEvents({
                    'click': function(day) {
                        this.curDate = day;
                        this.elementChange();
                    }.bind(this, day),
                    'mouseover': function(td) {
                        td.addClass('hilite');
                    }.bind(this, td),
                    'mouseout': function(td, date) {
                        if(date.getTime() != current)
                            td.removeClass('hilite');
                    }.bind(this, [td, date])
                }).addClass('active');
            }

            td.set('text',day);
        }
        return tbody;
    },
    unformat: function(val, f) {
       var _dates=val.split(f);
       var dates=new Array(3);
       if(_dates.length<3||!_dates[0]||!_dates[1]||!_dates[2])
       return [new Date().getFullYear(),new Date().getMonth(),new Date().getDate()];
        dates[0] = _dates[0].toInt();
        dates[1] =_dates[1].toInt()-1;
        dates[2] =_dates[2].toInt();
       return dates;

    },
    format: function(date, format) {
        if (date) {
                var j = date.getDate(); // 1 - 31
                var w = date.getDay(); // 0 - 6
                var l = this.options.days[w]; // Sunday - Saturday
                var n = date.getMonth() + 1; // 1 - 12
                var f = this.options.months[n - 1]; // January - December
                var y = date.getFullYear() + ''; // 19xx - 20xx

                return [y,n,j].join(format);
            }else{
               return '';
            }
    }

});


Element.implement({
        makeCalable:function(options){
        if(this.retrieve('bindColorPicker'))return;
           return new DatePickers([this],options);
        }
});


var validatorMap={
    'required':[LANG_formplus['validate']['required'],function(element,v){
        return v!=null && v!='';
    }],
    'number':[LANG_formplus['validate']['number'],function(element,v){
        return !isNaN(v) && !/^\s+$/.test(v);
    }],
    'msn':[LANG_formplus['validate']['msn'],function(element,v){
        return v == null || v == '' ||/\S+@\S+/.test(v);
    }],
    'skype':[LANG_formplus['validate']['skype'],function(element,v){
        return !/\W/.test(v) || /^[a-zA-Z0-9]+$/.test(v);
    }],
    'digits':[LANG_formplus['validate']['digits'],function(element,v){
        return !/[^\d]/.test(v);
    }],
    'unsignedint':[LANG_formplus['validate']['unsignedint'],function(element,v){
        return (!/[^\d]/.test(v) && v > 0);
    }],
    'unsigned':[LANG_formplus['validate']['unsigned'],function(element,v){
        return (!isNaN(v) && !/^\s+$/.test(v) && v >= 0);
    }],
    'positive':[LANG_formplus['validate']['positive'],function(element,v){
        return (!isNaN(v) && !/^\s+$/.test(v) && v > 0);
    }],
    'alpha':[LANG_formplus['validate']['alpha'],function(element,v){
        return v == null || v == '' ||/^[a-zA-Z]+$/.test(v);
    }],
    'alphaint':[LANG_formplus['validate']['alphaint'],function(element,v){
        return !/\W/.test(v) || /^[a-zA-Z0-9]+$/.test(v);
    }],
    'alphanum':[LANG_formplus['validate']['alphanum'],function(element,v){
        return !/\W/.test(v) || /^[\u4e00-\u9fa5a-zA-Z0-9]+$/.test(v);
    }],
    'unzhstr':[LANG_formplus['validate']['unzhstr'],function(element,v){
        return !/\W/.test(v) || !/^[\u4e00-\u9fa5]+$/.test(v);
    }],
    'date':[LANG_formplus['validate']['date'],function(element,v){
        return v == null || v == '' ||  /^(19|20)[0-9]{2}-([1-9]|0[1-9]|1[012])-([1-9]|0[1-9]|[12][0-9]|3[01])$/.test(v);
    }],
    'email':[LANG_formplus['validate']['email'],function(element,v){
        return v == null || v == '' ||/(\S)+[@]{1}(\S)+[.]{1}(\w)+/.test(v);
    }],
    'url':[LANG_formplus['validate']['url'],function(element,v){
        return v==null || v=='' || /^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*)(:(\d+))?\/?/i.test(v);
    }],
    'area':[LANG_formplus['validate']['area'],function(element,v){
        return  element.getElements('select').every(function(sel){
            var selValue=sel.get('value');
            sel.focus();
            return selValue!=''&&selValue!='_NULL_';
        });
    }],
    'requiredcheckbox':[LANG_formplus['validate']['requiredonly'], function(element) {
        var parent =  element.getParent(),chkbox;
        if(element.get('name')) chkbox = parent.getElements('input[type=checkbox][name="'+element.get('name')+'"]');
        else chkbox = parent.getElements('input[type=checkbox]');
        return chkbox.some(function(chk) {
            return chk.checked == true;
        });
    }],
    'requiredradio':[LANG_formplus['validate']['requiredonly'], function(element) {
        var parent =  element.getParent(),radio;
        if(element.get('name')) radio = parent.getElements('input[type=radio][name="'+element.get('name')+'"]');
        else radio = parent.getElements('input[type=radio]');
        return radio.some(function(rd) {
            return rd.checked == true;
        });
    }]
};

var validate = function(_form) {
    if (!_form) return true;
    var formElements = _form.match('form') ? _form.getElements('[vtype]') : [_form];
    var err_log = false;
    var _return = formElements.every(function(element) {
        var vtype = element.get('vtype');
        if (!vtype) return true;
        if (!element.isDisplay() && (element.getAttribute('type') != 'hidden')) return true;
        var valiteArr = vtype.split('&&');
        if (element.get('required')) {
            valiteArr = ['required'].combine(valiteArr.clean());
        }
        return vtype.split('&&').every(function(key) {
            if (!validatorMap[key]) return true;
            var _caution = element.getNext('.caution');
            var cautionInnerHTML = element.get('caution') || validatorMap[key][0];
            if (validatorMap[key][1](element, element.get('value'))) {
                if (_caution && _caution.hasClass('error')) {
                    _caution.destroy();
                }
                return true;
            }
            if (!_caution || ! _caution.hasClass('caution')) {
                new Element('span', {
                    'class': 'error caution notice-inline',
                    'html': cautionInnerHTML
                }).inject(element,'after');
                element.removeEvents('blur').addEvent('blur', function() {
                    if (validate(element)) {
                        if (_caution && _caution.hasClass('error')) {
                            _caution.destroy();
                        }
                        element.removeEvent('blur', arguments.callee);
                    }
                });
            } else if (_caution && _caution.hasClass('caution') && _caution.get('html') != cautionInnerHTML) {
                _caution.set('html', cautionInnerHTML);
            }
            if(element.type!='hidden'&&element.isDisplay()&&!err_log)err_log=element;
            return false;
        });
    });
    if(_form.match('form')&&err_log){try{err_log.focus();}catch(e){}}
    return _return;
};


function selectArea(sel,path,depth){
    var sel=$(sel);
    if(!sel)return;

    var sel_value=sel.value;
    var sel_panel=sel.getParent();
    var selNext=sel.getNext();
    var areaPanel= sel.getParent('*[package]');
    var hid=areaPanel.getElement('input[type=hidden]');
    var curOption=$(sel.options[sel.selectedIndex]);

    var setHidden=function(sel){
        var rst=[];
        var sel_break = true;

        if (curOption && !curOption.get('has_c')){

            var _currChliSpan = sel.getNext('.x-region-child');
            if (_currChliSpan){
                _currChliSpan.destroy();
            }

        }

        var sels=areaPanel.getElements('select');
        sels.each(function(s){
          if(s.get('value')!= '_NULL_' && sel_break){
              rst.push($(s.options[s.selectedIndex]).get('text'));
          }else{
            sel_break = false;
          }
        });

        if(sel.value != '_NULL_'){
            areaPanel.getElement('input').value = areaPanel.get('package')+':'+rst.join('/')+':'+sel.value;
        }else{
            areaPanel.getElement('input').value =function(sel){
                          var s=sels.indexOf(sel)-1;
                          if(s>=0){
                             return areaPanel.get('package')+':'+rst.join('/')+':'+sels[s].value;
                          }
                          return '';
            }(sel);
        }
        hid.retrieve('onselect',function(){})(sel);
    };
    if(sel_value=='_NULL_'&&selNext&&(selNext.get('tag')=='span' && selNext.hasClass('x-areaSelect'))){
        sel.getNext().empty();
        setHidden(sel);
    }else{
        /*nextDepth*/
        if(curOption.get('has_c')){
          new Request({
                url:Shop.url.region + '?path='+path+'&depth='+depth+'&t='+(new Date()),
                onSuccess:function(response){
                    if(selNext &&
                        (selNext.get('tag')=='span'&& selNext.hasClass('x-region-child'))){
                        var e = selNext;
                    }else{
                        var e = new Element('span',{'class':'x-region-child'}).inject(sel_panel);
                    }
                    function timeTag(){
                        if(areaPanel.getElement('input')){
                            clearInterval(timeobj);
                            setHidden(sel);
                            if(response){
                                e.set('html',response);
                                if(hid){
                                   hid.retrieve('sel'+depth,function(){})();
                                   hid.retrieve('onsuc',function(){})();
                                }
                            }else{
                                sel.getAllNext().destroy();
                                setHidden(sel);
                                hid.retrieve('lastsel',function(){})(sel);
                            }
                        }else{
                            areaPanel.getElement('input');
                        }
                   }
                    var timeobj= timeTag.periodical(100);
                }
            }).get();

        }else{
            sel.getAllNext().destroy();
            setHidden(sel);

            if(!curOption.get('has_c')&&curOption.value!='_NULL_')
            hid.retrieve('lastsel',function(){})(sel);
        }
    }
}





(function(){

var disabled = 'disabled', ajaxName = '_ajax', attr = 'rel';

var Sync =this.Sync = new Class({
       Extends:Request.HTML,
       options:{
           syncCache:false,
           disabled:disabled,
           loadtip:'loading',
           tipCls:'-tip',
           ajaxTip:'ajax-tip',
           tipHidden:false, //'success'
           position:'before',
           evalScripts:true
       },
       initialize: function(target,options){
            this.sponsor = target;
           if(target)options = this._getOptions(target,options);
           this.parent(options);
       },
       _getOptions:function(target,options){
           try{var _options= JSON.decode(target.get('data-ajax-config'))||{};
           }catch(e){var _options= {};}

           var dataForm,opt, options = options || {},
               isSubmit= target.type ==='submit' ? true :false;

           if(isSubmit)dataForm =this.dataForm= target.getParent('form') || {};
           if(isSubmit)
               opt = {data:dataForm,url:dataForm.action,method:dataForm.method ||'post'};
           else
               opt = {url:target.get('href'),method:'get'};

           _options = Object.merge(opt,options,target.retrieve('_ajax_config',{}),_options);
           return _options;
       },
       _nearText : function(elem){
            var el =elem,node;
            while(elem){
                node = elem.firstChild;
                if(typeOf(node)==='whitespace')node = node.nextSibling;
                if(node && node.nodeType===3)return $(elem);
                elem = node;
            }
            return el;
       },
       _defaultState:function(){
           this.sponsor && this.sponsor.removeClass(this.options.disabled).retrieve('default:state',function(){})();
           return this;
       },
       onFailure: function(){
           this._defaultState().parent();
       },
       _validate:function(elem){
           var checkElems =elem.getElements('[vtype]');
           if(!!checkElems.length && !checkElems.every(validate)) return false;
           return true;
       },
       _getCache:function(sponsor) {
           return sponsor.retrieve('ajax:cache',false);
       },
       _clearCache:function(sponsor){
           sponsor.eliminate('ajax:cache');
       },
       _setCache: function(sponsor,value) {
           sponsor && sponsor.store('ajax:cache',value);
       },
       _progressCache:function(sponsor){
           var cache = this._getCache(sponsor);
           if(cache) return cache.success(cache.response.data) || true;
       },
       success:function(text,xml){
           this.response.data = text;
           if((/text\/jcmd/).test(this.getHeader('Content-type')))
           return this._jsonSuccess(text);

           if (['update','append','filter'].some(function(n){return this.options[n]},this))
           return this.parent(text,xml);

           this.onSuccess(this.processScripts(text), xml);
       },
       _jsonSuccess:function(text){
           try {var json = this.response.json = JSON.decode(text);
           } catch (e){var json = null;}
           this.onSuccess(json);
       },
       onSuccess:function(text){
           this._defaultState();
           if(this.response.json)this._progress(text);
           this._setCache(this.sponsor,this);
           this.parent(arguments);
       },
       _progress:function(cmd){
            if(!cmd)return;
            var redirect = cmd['redirect'],m;
            ['error','success'].each(function(v,k){
                m = cmd[v];
                if (this.options.inject && m){
                    if(v!=this.options.tipHidden)return this._injectTip(v,m);
                    this._clearTip(v,m);
                }
            },this);
            if((m=cmd['error'])){
                var state = this.options.progress ? this.options.progress(cmd):true;
                if(m && state!==false) return Message(m,'error',function(){
                    if(redirect){
                        if(redirect == 'back') history.back();
                        else location.href = redirect;
                    }else{
                        this._defaultState();
                    }
               }.bind(this));
            }else if((m=cmd['success'])){
                if(m && redirect) {
                    if(redirect == 'back') history.back();
                    else location.href = redirect;
               }
            }
       },
       _clearTip:function(){
           if(!this.inject || !this.tipElem)return;
           this.tipElem.destroy();
       },
       _injectTip:function(cls,html){
           var options = this.options,inject = this.inject = document.id(options.inject),
               position = options.position ,ajaxTip = options.ajaxTip,
               tipCls  = options.tipCls,cls = cls + tipCls, tipBox;

               if(!inject)return;
           tipBox = inject.getParent();
               if(tipBox && (this.tipElem = tipBox.getElement('.'+ajaxTip)))
           return this.tipElem.set('html',html);
           new Element('div',{'class':cls+' '+ajaxTip}).set('html',html).inject(inject,position);
       },
       _request:function(sponsor){
           if(!sponsor)return this;
           sponsor.addClass(this.options.disabled);
           var obj = {'INPUT':'value','BUTTON':'text'}, key, btnText,btn;
           if(key = obj[sponsor.tagName]){
               btnText = sponsor.get(key);
               btn = this._nearText(sponsor).set(key,this.options.loadtip);
           }
           if(sponsor.retrieve('default:state')) return this;
           sponsor.store('default:state',function(){
               btn && btn.set(key,btnText);
           });
           return this;
       },
       _isCheck:function(elem,options){
           var options = options || {},
               dataElem = this.dataForm || options.data || this.options.data;

           if(typeOf(dataElem)==='element' && !this._validate(dataElem)) return false;
           return true;
       },
       send:function(options){
           var target = this.sponsor;
           if(target){
            if(target.hasClass(this.options.disabled)||!this._isCheck(target,options))return;
            if(this.options.syncCache && this._progressCache(target))return;
           }
           this._request(target).parent(options);
       }
});


var async =function(elem,event,_form){
       if(elem.hasClass(disabled)) return false;

       if(_form){
           if(!validate(_form)) {
               elem.removeClass(disabled);
               return false;
           }
           if(!elem.get('isDisabled'))
           return elem.addClass(disabled);
       }
       if(sync = elem.retrieve('ajax:cache',false))return sync.send();
       sync = new Sync(elem).send();
};


var Ex_Event_Group = this.Ex_Event_Group ={_request:{fn:async}};

var nearest = function(elem,type,value){
       var i = 3, el;
       for(;i;i--){
           if(!elem || elem.nodeType===9)return el;
           if(elem.type==='submit' || ($(elem).get && $(elem).get(type))) return elem;
           elem = elem.parentNode;
       }
       return el;
};

$(document.documentElement || document.body).addEvent('click',function(e){
   var target = $(e.target), elem;
   if((elem = nearest(target,attr))){
       if(elem.type==='submit' && elem.get(attr)!=='_request')return async(elem,e,elem.getParent('form'));

       var type = elem.get(attr), eventType = Ex_Event_Group[type];
       if(eventType){
           var fn= eventType['fn'], loader = eventType['loader'];
           e.preventDefault();
           if(loader){Ex_Loader(type,function(){fn && fn(elem,e);});}
           else {fn && fn(elem,e);}
       }
   }
});

})();
