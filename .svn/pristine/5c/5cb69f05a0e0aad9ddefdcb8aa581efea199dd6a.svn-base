Element.implement({
    zoomImg: function(maxwidth, maxheight, v) {
        if (this.get('tag') != 'img' || ! this.width) return;
        var thisSize = {
            'width': this.width,
            'height': this.height
        };
        if (thisSize.width > maxwidth) {
            var overSize = thisSize.width - maxwidth;
            var zoomSizeW = thisSize.width - overSize;
            var zommC = (zoomSizeW / thisSize.width).toFloat();
            var zoomSizeH = (thisSize.height * zommC).toInt();
            Object.append(thisSize, {
                'width': zoomSizeW,
                'height': zoomSizeH
            });
        }
        if (thisSize.height > maxheight) {
            var overSize = thisSize.height - maxheight;
            var zoomSizeH = thisSize.height - overSize;
            var zommC = (zoomSizeH / thisSize.height).toFloat();
            var zoomSizeW = (thisSize.width * zommC).toInt();
            Object.append(thisSize, {
                'width': zoomSizeW,
                'height': zoomSizeH
            });
        }
        if (!v) return this.set(thisSize);
        return thisSize;
    },
    getPadding: function() {
        return {
            'x': (this.getStyle('padding-left').toInt() || 0) + (this.getStyle('padding-right').toInt() || 0),
            'y': (this.getStyle('padding-top').toInt() || 0) + (this.getStyle('padding-bottom').toInt() || 0)
        };
    },
    getMargin: function() {
        return {
            'x': (this.getStyle('margin-left').toInt() || 0) + (this.getStyle('margin-right').toInt() || 0),
            'y': (this.getStyle('margin-top').toInt() || 0) + (this.getStyle('margin-bottom').toInt() || 0)
        };
    },
    getBorderWidth: function() {
        return {
            'left': this.getStyle('border-left-width').toInt() || 0,
            'right': this.getStyle('border-right-width').toInt() || 0,
            'top': this.getStyle('border-top-width').toInt() || 0,
            'bottom': this.getStyle('border-bottom-width').toInt() || 0,
            'x': (this.getStyle('border-left-width').toInt() || 0) + (this.getStyle('border-right-width').toInt() || 0),
            'y': (this.getStyle('border-top-width').toInt() || 0) + (this.getStyle('border-bottom-width').toInt() || 0)
        };
    },
    getTrueWidth: function() {
        return this.getSize().x - (this.getPadding().x + this.getBorderWidth().x);
    },
    getTrueHeight: function() {
        return this.getSize().y - (this.getPadding().y + this.getBorderWidth().y);
    },
    hide: function(){
        var d;
        try {
            //IE fails here if the element is not in the dom
            d = this.getStyle('display');
        } catch(e){}
        if (d == 'none') return this;
        return this.store('element:_originalDisplay', d || '').setStyle('display', 'none');
    },
    show: function(display){
        if (!display && this.isDisplay()) return this;
        this.fireEvent('onshow', this);
        display = display || this.retrieve('element:_originalDisplay', '');
        return this.setStyle('display', (display == 'none') ? 'block' : display);
    },
    isDisplay: function() {
        if (this.getStyle('display') == 'none' || (this.offsetWidth + this.offsetHeight) === 0) {
            return false;
        }
        return true;
    },
    getSelectedRange: function() {
        if (!Browser.ie) return {
            start: this.selectionStart,
            end: this.selectionEnd
        };
        var pos = {
            start: 0,
            end: 0
        };
        var range = this.getDocument().selection.createRange();
        if (!range || range.parentElement() != this) return pos;
        var dup = range.duplicate();
        if (this.type == 'text') {
            pos.start = 0 - dup.moveStart('character', - 100000);
            pos.end = pos.start + range.text.length;
        } else {
            var value = this.value;
            var offset = value.length - value.match(/[\n\r]*$/)[0].length;
            dup.moveToElementText(this);
            dup.setEndPoint('StartToEnd', range);
            pos.end = offset - dup.text.length;
            dup.setEndPoint('StartToStart', range);
            pos.start = offset - dup.text.length;
        }
        return pos;
    },
    selectRange: function(start, end) {
        if (Browser.ie) {
            var diff = this.value.substr(start, end - start).replace(/\r/g, '').length;
            start = this.value.substr(0, start).replace(/\r/g, '').length;
            var range = this.createTextRange();
            range.collapse(true);
            range.moveEnd('character', start + diff);
            range.moveStart('character', start);
            range.select();
        } else {
            this.focus();
            this.setSelectionRange(start, end);
        }
        return this;
    },
    //获取padding,margin,border值
    getPatch: function() {
        var args = arguments.length ? Array.from(arguments) : ['margin', 'padding', 'border'];
        var _return = {
            x: 0,
            y: 0
        };

        Object.each({x: ['left', 'right'], y: ['top', 'bottom']}, function(p2, p1) {
            p2.each(function(p) {
                try {
                    args.each(function(arg) {
                        arg += '-' + p;
                        if (arg == 'border') arg += '-width';
                        _return[p1] += this.getStyle(arg).toInt() || 0;
                    }, this);
                } catch(e) {}
            }, this);
        }, this);
        return _return;
    },
    // the elements outer size
    outerSize: function(){
        if(this.getStyle('display') === 'none') return {x: 0, y: 0};
        return {
            x: (this.getStyle('width').toInt() || 0) + this.getPatch().x,
            y: (this.getStyle('height').toInt() || 0) + this.getPatch().y
        };
    }
});
String.implement({
    format: function() {
        if (arguments.length == 0) return this;
        var reg = /{(\d+)?}/g;
        var args = arguments;
        var string = this;
        var result = this.replace(reg, function($0, $1) {
            return args[$1.toInt()] || "";
        });
        return result;
    }
});

(function() {
    broswerStore = null;
    withBroswerStore = function(callback) {
        if (broswerStore) return callback(broswerStore);
        window.addEvent('domready', function() {
            if (broswerStore = new BrowserStore()) {
                callback(broswerStore);
            } else {
                window.addEvent('load', function() {
                    callback(broswerStore = new BrowserStore());
                });
            }
        });
    };
})();

var MessageBox = new Class({
    options: {
        delay: 1,
        onFlee: function(){},
        FxOptions: {}
    },
    initialize: function(msg, type, options) {
        Object.append(this.options, options);
        this.createBox(msg, type);

    },
    flee: function(mb) {
        var mbFx = new Fx.Morph(mb, this.options.FxOptions);
        var begin = false;
        var obj = this;
        mb.addEvents({
            mouseenter: function() {
                if (begin) mbFx.pause();
            },
            mouseleave: function() {
                if (begin) mbFx.resume();
            }
        });
        (function() {
            begin = true;
            mbFx.start({
                //'left': 0,
                'opacity': 0
            }).chain(function() {
                this.element.destroy();
                begin = false;

                if (obj.options.onFlee) {
                    obj.options.onFlee.apply(obj, [obj]);
                }
                if (window.MessageBoxOnFlee) {
                    window.MessageBoxOnFlee();
                    window.MessageBoxOnFlee = function(){};
                }
            });
        }).delay(this.options.delay * 1000);

    },
    createBox: function(msg, type) {

        var msgCLIP = /<h4[^>]*>([\s\S]*?)<\/h4>/;
        var tempmsg = msg;
        if (tempmsg.test(msgCLIP)) {
            tempmsg.replace(msgCLIP, function() {
                msg = arguments[1];
                return '';
            });
        }
        var box = new Element('div').setStyles({
            'position': 'absolute',
            'visibility': 'hidden',
            'width': 238,
            'height':90,
            'opacity': 0,
            'zIndex': 65535
        }).inject(document.body);
        var obj = this;

        new Fx.Tween(box.addClass(type).set('html',"<p></p><h4>"+ msg+ "</h4>").amongTo(window)).start('opacity', 1).chain(function() {
            obj.flee(this.element);
        });
        return box;
    }

});

MessageBox.success = function(msg, options) {
    return new MessageBox(msg || LANG_jstools['messageSuccess'], 'success-message', options);
};
MessageBox.error = function(msg, options) {
    return new MessageBox(msg || LANG_jstools['messageError'], 'error-message', options);
};
MessageBox.show = function(msg, options) {
    if (msg.contains('failedSplash')) {
        return new MessageBox(msg || LANG_jstools['messageError'], 'error-message', options);
    }
    return new MessageBox(msg || LANG_jstools['messageSuccess'], 'success-message', options);

};

_open = function(url, options) {
    options = Object.append({
        width: window.getSize().x * 0.8,
        height: window.getSize().y
    },
    options || {});
    var params = 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width={width},height={height}';
    params = params.substitute(options);

    window.open(url || 'about:blank', '_blank', params);

};

/*fix Image size*/
var fixProductImageSize = function(images, ptag) {
    if (!images || ! images.length) return;
    images.each(function(img) {
        if (!img.src) return;
        new Asset.image(img.src, {
            onload: function() {
                var imgparent = img.getParent((ptag || 'a'));
                if (!this || ! this.get('width')) return imgparent.adopt(img);
                var imgpsize = {
                    x: imgparent.getTrueWidth(),
                    y: imgparent.getTrueHeight()
                };
                var nSize = this.zoomImg(imgpsize.x, imgpsize.y, true);
                img.set(nSize);
                var _style = {
                    'margin-top': 0
                };
                if (img && img.get('height')) if (img.get('height').toInt() < imgpsize.y) {
                    _style = Object.merge(_style, {
                        'margin-top': (imgpsize.y - img.get('height').toInt()) / 2
                    });
                }
                img.setStyles(_style);
                return true;
            },
            onerror: function() {

            }
        });
    });
};

(function() {

    var Request_Cache = {
        cache: {},
        getCache: function(target, body, fn) {
            var text;
            if (text = target.retrieve('cache:uid'))
            //  if(text=this.cache['cache_'+target.uid])
            return this.progress(text, body, fn);
            return false;
        },
        progress: function(text, body, fn) {
            var javascript, html = text.stripScripts(function(script) {
                javascript = script;
            });
            if (body) body.set('html', html);
            Browser.exec(javascript);
            if (fn) fn(text);
            return text;
        },
        setCache: function(target, value) {
            target.store('cache:uid', value);
            // this.cache['cache_'+target.uid]=value;
        }
    };

    var Dialog = this.Dialog = new Class({
        Implements: [Options, Events],
        options: {
            iframe: false,
            title: '',
            width: 342,
            data: '',
            height: 187,
            target: '',
            rela: '',
            method: 'post',
            singlon: true,
            onShow: function() {
                this.dialog.setStyles({
                    'visibility': 'visible',
                    'opacity': '0'
                }).fade('in');
            },
            offset: {
                x: 70,
                y: 0
            },
            callback: function(){},
            diaglogCls: 'dialog'
        },
        initialize: function(url, options) {
            this.url = url;
            this.setOptions(options);
            options = this.options;

            var dialog = this.dialog = new Element('div', {
                'class': options.diaglogCls
            }).set('html', $('dialog-theme').value.substitute({
                title: options.title,
                content: 'loading....'
            })).setStyles({
                visibility: 'hidden',
                width: 0
            }).inject(document.body);

            if (options.singlon && Dialog.singlon['uid']) Dialog.singlon['instance'].close();
            Dialog.singlon = {
                'uid': $uid(dialog),
                'instance': this
            };

            this.body = dialog.getElement('.dialog-content');
            dialog.addEvent('callback', options.callback.bind(this));

            if (options.iframe) this.iframe = new Element('iframe', {
                src: 'javascript:void(0);',
                styles: {
                    position: 'absolute',
                    zIndex: - 1,
                    border: 'none',
                    top: 0,
                    left: 0,
                    'filter': 'alpha(opacity=0)'
                },
                width: '100%',
                height: '100%'
            }).inject(this.body.empty());

            this.show();
        },
        request: function(url, options) {
            if (options.iframe) return this.iframe.set('src', url + '?' + options.data).addEvent('load', this.success.bind(this));
            if (ajax) ajax.cancel();
            var target = $(options.target);

            if (Request_Cache.getCache(target, this.body)) return this.success();

            var ajax = new Request.HTML({
                url: url,
                update: this.body,
                method: options.method,
                data: options.data,
                onFailure: this.close.bind(this),
                onSuccess: function() {
                    Request_Cache.setCache(target, ajax.response.text);
                    this.success();
                }.bind(this)
            }).send();
        },
        success: function() {
            if (this.props) this.fireEvent('position');
            this.fireEvent('success');
        },
        show: function() {
            var closebtn = this.dialog.getElement('*[isCloseDialogBtn]'),
            options = this.options;
            if (closebtn) closebtn.addEvent('click', this.close.bind(this));
            if (!options.target) return this.dialog.amongTo(window);

            this.dialog.setStyles({
                position: 'absolute',
                width: options.width,
                height: options.height
            });
            this.fireEvent('show').position({
                page: options.target.getPosition()
            },
            options.rela);

            (function() {
                document.addEvent('click', function(e) {
                    var target = $(e.target),
                    diaglogCls = options.diaglogCls;
                    if (!target.hasClass(diaglogCls) && ! target.getParent('.' + diaglogCls)) this.close.call(this);
                    document.removeEvent('click', arguments.callee);
                }.bind(this));
            }).delay(200, this);

            this.request(this.url, options);
        },
        position: function(event, rela) {
            var size = (rela || window).getSize(),
            scroll = (rela || window).getScroll(),
            dialog = {
                x: this.dialog.offsetWidth,
                y: this.dialog.offsetHeight
            },
            props = {
                x: 'left',
                y: 'top'
            },
            obj = {};
            for (var z in props) {
                obj[props[z]] = event.page[z] + this.options.offset[z];
                if ((obj[props[z]] + dialog[z] - scroll[z]) > size[z] && z != 'y') {
                    obj[props[z]] = event.page[z] + this.options.offset[z] - dialog[z];
                    this.props = true;
                }
            }
            this.dialog.setStyles(obj);
        },
        close: function() {
            try {
                this.fireEvent('close').dialog.destroy();
            } catch(e) {}
        }
    });
    Dialog.singlon = {};
})();

/*AutoSize*/
var AutoSize = new Class({
    initialize: function(elements, hw) {
        this.elements = $$(elements);
        this.doAuto(hw);
    },
    doAuto: function(hw) {
        if (!hw) {
            hw = 'height';
        }
        var max = 0,
        prop = (typeof document.body.style.maxHeight != 'undefined' ? 'min-': '') + hw; //ie6 ftl
        offset = 'offset' + hw.capitalize();
        this.elements.each(function(element, i) {
            var calc = element[offset];
            if (calc > max) {
                max = calc;
            }
        },
        this);
        this.elements.each(function(element, i) {
            element.setStyle(prop, max - (element[offset] - element.getStyle(hw).replace('px', '')));
        });
        return max;
    }
});

(function() {
  var timeCount = this.timeCount = {
            init:function(timeStart,timeEnd,dom,isReload){
                this.isReload = isReload || true;
                var diff = Math.abs((timeStart.getTime() - timeEnd.getTime())/1000);
                var secondDiff = diff % 60;
                var minuteDiff = ((diff - secondDiff)/60) % 60;
                var hourDiff = (diff - secondDiff  - minuteDiff*60) / 3600;
                var timeDiff = [hourDiff,minuteDiff,secondDiff];
                this.s = this.calcTime.periodical(1000,this,{
                    time:timeDiff,
                    dom:dom
                });
                if(document.getElement('.desc')){
                this.desc = 10;
                this.d = this.calcDesc.periodical(100,this);
                (function(){$('timer').setStyle('display','block')}).delay(1100);
                }
            },
            addZero:function(timeDiff){
                for(var i=0;i<timeDiff.length;i++){
                    if(timeDiff[i].toString().length<2){
                        timeDiff[i] = "0" + timeDiff[i].toString();
                        return timeDiff;
                    }
                }
            },
            formatToInt : function(timeDiff){
                for(var i=0;i<timeDiff.length;i++){
                    parseInt(timeDiff[i]);
                }
                return timeDiff;
            },
            judgeTime : function(timeDiff){
                if(timeDiff[2]< 0  && timeDiff[1]>0){
                    timeDiff[2] = 59;
                    timeDiff[1]--;
                    return timeDiff;
                    }else if(timeDiff[2] <0 && timeDiff[1]==0 && timeDiff[0]>0){
                    timeDiff[2] = 59;
                    timeDiff[1] = 59;
                    timeDiff[0]--;
                    return timeDiff;
                    }else if(timeDiff[2]==0 && timeDiff[1]==0 && timeDiff[0]==0){
                    $clear(this.s);
                    if(document.getElement('.desc')){ $clear(this.d); document.getElement('.desc').innerHTML = 0; }
                    if(this.isReload) location.reload();
                    return;
                }
            },
            calcTime : function (obj){
                if(!obj.dom) return;
                var _timeDiff = obj.time;
                this.addZero(_timeDiff);
                this.formatToInt(_timeDiff);
                _timeDiff[2]--;
                this.judgeTime(_timeDiff);
                this.addZero(_timeDiff);
                var dom = obj.dom;
                if(dom.second) dom.second.innerHTML = _timeDiff[2];
                if(dom.minute) dom.minute.innerHTML = _timeDiff[1];
                if(dom.hour) dom.hour.innerHTML = _timeDiff[0];
            },
            calcDesc:function(){
                this.desc--;
                document.getElement('.desc').innerHTML = this.desc;
                if(this.desc == 0)
                this.desc = 10;
            }
        };
})();

//position the element
(function(){
Element.implement({
    position: function(options){
        options = Object.merge({
            target: document.body,
            to: {x:'center', y:'center'}, //定位到目标元素的基点
            base: {x:'center', y:'center'}, //此元素定位基点 --为数值时类似offset
            offset: {
                x: 0,
                y: 0
            },
            // true 或 to:滑动使this可视。in:把this限制在视窗内
            intoView: false
        }, options);

        this.setStyle('position', 'absolute');

        var el = options.target || $(document.body);
        var base = getOffset(this, options.base);
        var to = getOffset(el, options.to);
        var x = to.x - base.x + el.getPosition().x + el.getScroll().x + options.offset.x;
        var y = to.y - base.y + el.getPosition().y + el.getScroll().y + options.offset.y;

        if(options.intoView === 'in'){
            x = x.limit(0, window.getScroll().x + window.getSize().x - this.getSize().x);
            y = y.limit(0, window.getScroll().y + window.getSize().y - this.getSize().y);
        }

        this.setStyles({
            left: x,
            top: y
        });
        if(options.intoView === true || options.intoView === 'to')
            try{
                new Fx.Scroll(document).scrollIntoView(this);
            } catch(e){}
        return this;
    }
});
//取得九点定位的坐标
function getOffset(el, base) {
    var size = el.getSize(), x, y;
    base = base || {x: 'center', y: 'center'};
    switch(base.x.toString().toLowerCase()) {
    case '0':
    case 'left':
        x = 0;
        break;
    case '100%':
    case 'right':
        x = size.x;
        break;
    case '50%':
    case 'center':
        x = size.x / 2;
        break;
    default:
        x = base.x.toInt();
        break;
    }
    switch(base.y.toString().toLowerCase()) {
    case '0':
    case 'top':
        y = 0;
        break;
    case '100%':
    case 'bottom':
        y = size.y;
        break;
    case '50%':
    case 'center':
        y = size.y / 2;
        break;
    default:
        y = base.y.toInt();
        break;
    }

    return {x: x, y: y};
}
})();
