String.implement({
      format:function(){
        if(arguments.length == 0)
          return this;
         var reg = /{(\d+)?}/g;
         var args = arguments;
         var string=this;
         var result = this.replace(reg,function($0, $1) {
           return  args[$1.toInt()]||"";
          }
     );
     return result;
  }
});

/*
Script: Scroller.js
    Class which scrolls the contents of any Element (including the window) when the mouse reaches the Element's boundaries.

License:
    MIT-style license.
*/

var Scroller = new Class({

    Implements: [Events, Options],

    options: {
        area: 20,
        velocity: 1,
        onChange: function(x, y){
            this.element.scrollTo(x, y);
        }
    },

    initialize: function(element, options){
        this.setOptions(options);
        this.element = $(element);
        this.listener = (typeOf(this.element) != 'element') ? $(this.element.getDocument().body) : this.element;
        this.timer = null;
        this.coord = this.getCoords.bind(this);
    },

    start: function(){
        this.listener.addEvent('mousemove', this.coord);
    },

    stop: function(){
        this.listener.removeEvent('mousemove', this.coord);
        this.timer = clearInterval(this.timer);
    },

    getCoords: function(event){
        this.page = (this.listener.get('tag') == 'body') ? event.client : event.page;
        if (!this.timer) this.timer = this.scroll.periodical(50, this);
    },

    scroll: function(){
        var size = this.element.getSize(), scroll = this.element.getScroll(), pos = this.element.getPosition(), change = {'x': 0, 'y': 0};
        for (var z in this.page){
            if (this.page[z] < (this.options.area + pos[z]) && scroll[z] != 0)
                change[z] = (this.page[z] - this.options.area - pos[z]) * this.options.velocity;
            else if (this.page[z] + this.options.area > (size[z] + pos[z]) && size[z] + size[z] != scroll[z])
                change[z] = (this.page[z] - size[z] + this.options.area - pos[z]) * this.options.velocity;
        }
        if (change.y || change.x) this.fireEvent('change', [scroll.x + change.x, scroll.y + change.y]);
    }

});
/*
 DragDropPlus  -用于拖拽模板区块的类.

  <div id='drag_operate_box' class='drag_operate_box' style='visibility:hidden;'>
       <div class='drag_handle_box'>
             <table cellpadding='0' cellspacing='0' width='100%'>
                                           <tr>
                                           <td><span class='dhb_title'>标题</span></td>
                                           <td width='40'><span class='dhb_edit'>编辑</span></td>
                                           <td width='40'><span class='dhb_del'>删除</span></td>
                                           </tr>
              </table>
              </div>
          </div>

          <div id='drag_ghost_box' class='drag_ghost_box' style='visibility:hidden'>

          </div>


*/


var DragDropPlus=new Class({
  Implements:[Options,Events],
  options:{
    ddScope:window
    /*onInitDrags:function(){},
    onInitDrops:function(){},
    onEdit:function(){},
    onDelete:function(){}*/
  },
   initialize:function(drags,drops,options){
          this.dragSelecterString=drags;
          this.dropSelecterString=drops;
          this.drags=$$(drags);
          this.drops=$$(drops);
          this.setOptions(options);
          if(this.options.ddScope){
          this.winScroll= new Scroller(this.options.ddScope, {velocity: 1});
          }
          this.drag_operate_box=$('drag_operate_box');
		  if(!this.drag_operate_box)return;
          this.drag_operate_box.store('lock',false);
          this.drag_handle_box=this.drag_operate_box.getElement('.drag_handle_box');
          this.dobFx=new Fx.Morph(this.drag_operate_box, {fps:50,duration:200,link: 'cancel'});

          this.dragSign=$('drag_ghost_box').inject(document.body);

          this.initDOBBase(this.drops);
          this.initDrags(this.drags);
          this.initDrops(this.drops);

   },
   checkEmptyDropPanel:function(dp){

      if(!dp||!dp.hasClass(this.dropSelecterString.substring(1,this.dropSelecterString.length)))return;
      if(!dp.getElement(this.dragSelecterString)){
          if(!dp.getElement('.empty_drop_box')){
             new Element('div.empty_drop_box').set('text',LANG_dragdropplus['empty_box']).inject(dp);
             if(this.dragmoveInstance){
                dp.store('droppanel',true);
                this.dragmoveInstance.droppables.include(dp);
             }
          }
      }else{
        if(dp.getElement('.empty_drop_box')){
           dp.getElement('.empty_drop_box').destroy();
        }
      }
   },
   dragLeave:function(){
     //this.checkEmptyDropPanel(arguments[1]);
   },
   dargInject:function($dob,element){
     var dragging=this.dragging;
       if(!dragging)return;
       var where = 'inside';
        if(!element.retrieve('droppanel')){
          where = dragging.getAllPrevious().contains(element) ? 'before' : 'after';
        }
        dragging.inject(element, where);
        this.checkEmptyDropPanel($dob.retrieve('droped'));
        this.checkEmptyDropPanel(element);
        $dob.store('droped',element);
        this.dragSign.setStyles(dragging.getCoordinates());
        //$dob.setStyles({width:dragging.getSize().x,height:dragging.getSize().y});
   },
   getDropables:function(){
    var drag=this.dragging;
    var dropables=Array.from(this.drags).erase(drag).combine(this.drops.filter(function(el){
                     if(el.getElement(this.dragSelecterString)){
                       el.store('droppanel',false);
                       return false;
                     }else{
                       el.store('droppanel',true);
                       return true;
                     }
               }.bind(this)));
        return dropables;
   },
   initDOBBase:function(drops){
     var dob=this.drag_operate_box;
     var dhb=this.drag_handle_box;
     var obj=this;
     if(!drops)return;
     var winScroll=this.winScroll;
     dhb.getElement('.dhb_edit').addEvent('click',function(e){
                  e.stop();
                  this.fireEvent('onEdit',[dob.retrieve('drag')],this);
     }.bind(this));
     dhb.getElement('.dhb_del').addEvent('click',function(e){
                 e.stop();
                 this.fireEvent('onDelete',[dob.retrieve('drag')],this);
     }.bind(this));
         dhb.getElement('.dhb_title').addEvents({'mousedown':function(e){
             obj.dragging=dob.retrieve('drag');
             dob.setStyle('left',dob.getPosition().x+10);
             dob.store('droped',obj.dragging.getParent(obj.dropSelecterString));
             dob.store('lock',true);
             obj.dragmoveInstance=new Drag.Move(dob,{
                        handle:this,
                        droppables:obj.getDropables.call(obj),
                        snap:0,
						preventDefault: true,
                        onEnter:obj.dargInject.bind(obj),
                        onStart:function(el){
                           obj.dragSign.setStyles(Object.append(obj.dragging.getCoordinates(),{'visibility':'visible'}));
                           if(winScroll)winScroll.start();
                        },
                        onComplete:function(el){
                           if(winScroll)winScroll.stop();
                           obj.dobFx.start(obj.dragging.getCoordinates()).chain(function(){
                                  this.element.store('lock',false);
                           });
                        }
             });
             obj.dragmoveInstance.start(e);
         },'mouseup':function(e){
            obj.dragmoveInstance.stop(e);
            obj.dragmoveInstance.cancel(e);
            obj.dragmoveInstance.detach();
            if(obj.dragSign)obj.dragSign.setStyle('visibility','hidden');
         }});
   },
   initDrags:function(drags){
        var drops=this.drops;
        var obj=this;
        drags.each(function(drag,index){
            obj.fireEvent('onInitDrags',[drag,drags],this);
            drag.addEvents({
            'mouseenter':function(){
                   var dob=obj.drag_operate_box;
                   if(dob.retrieve('lock'))return;
                   dob.getElement('.dhb_title').set('html',drag.get('title')||"&nbsp;");
                   dob.setStyle('visibility','visible');
                   dob.store('drag',this);
                   var toStyles=drag.getCoordinates();
                   toStyles=Object.append(toStyles,{height:toStyles.height.limit(26,Number.MAX_VALUE)-2,width:toStyles.width.limit(130,Number.MAX_VALUE)});
                   obj.dobFx.start(toStyles);
              }
            });
            drag.getElements('a').removeEvents().addEvent('click',function(e){e.stop();});
            drag.getElements('form').removeEvents().addEvent('submit',function(e){e.stop();});
        });

   },
   initDrops:function(drops){
        var obj=this;
         drops.each(function(drop, index){
         obj.checkEmptyDropPanel(drop);
         obj.fireEvent('onInitDrops',[drop,drops],this);
        });
   }
});
/*------------------------------------------------------------------------------------------------*/
