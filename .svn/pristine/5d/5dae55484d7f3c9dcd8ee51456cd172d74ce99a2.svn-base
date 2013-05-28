/*shopAdmin Widgets
    Extends DragDropPlus.js
*/
var Widgets = new Class({
	Extends: DragDropPlus,
	initialize: function(drags, drops, options) {
		this.parent(drags, drops, options);

		this.drags.each(function(drag) {
			if (drag.getProperty('ishtml')) {
				drag.getElement('.content-html').set('html',drag.getElement('.content-textarea').get('value'));
			}

		});

	},
	inject: function(widget, theme) {
		this.addWidget(this.curEl, widget, theme ? theme: this.theme);
	},
	ghostDrop: function(widget, theme) {
		this.drag_operate_box.setStyle('visibility', 'hidden').store('lock', true);
		$('tempDropBox') && $('tempDropBox').destroy();
		//parent._showWidgets_tip('在您需要放入版块的蓝色区域点击鼠标左键即可添加版块。点击鼠标右键则取消添加版块操作。');
		this.tempDropBox = new Element('div', {
			'id': 'tempDropBox'
		}).inject(document.body);
		try {
			this.drops.each(function(item, index) {
				if (!item) return;
				var _this = this;
				var cis = item.getCoordinates();
				if (cis.height > 5 && cis.width > 5) {
					cis.height -= 5;
					cis.width -= 5;
				}
				var dropghost = new Element('div', {
					'class': 'widgets_drop_ghost',
					'styles': Object.merge(cis, {
						'opacity': 0.3
					}),
					'text': '[' + (index + 1) + ']',
					'title': LANG_shopwidgets['dropghostTitle1'] + widget.name + LANG_shopwidgets['dropghostTitle2']
				}).addEvents({
					'mouseover': function() {
						this.addClass('widgets_drop_ghost_on');
					},
					'mouseleave': function() {
						this.removeClass('widgets_drop_ghost_on');
					},
					'click': function() {
						_this.tempDropBox.empty();
						_this.addWidget(item, widget, theme);
						_this.drag_operate_box.store('lock', false);
						//    parent._hideWidgets_tip();
					}
				}).inject(_this.tempDropBox);
			},
			this);
		} catch(e) {
			alert(JSON.encode(e));
		}
		document.body.addEvent('contextmenu', function(e) {
			e.stop();
			$('tempDropBox') && $('tempDropBox').destroy();
			// parent._hideWidgets_tip();
			this.drag_operate_box.store('lock', false);
			document.body.removeEvent('contextmenu', arguments.callee);
		}.bind(this));
	},
	copyWidgets: function(el) {
		/*var elsource=el.source;
             new Ajax('index.php?ctl=system/template&act=copyWg&p[0]='+elsource.getProperty('id')+'&p[1]='+elsource.getProperty('widgets_id'),{onComplete:function(re){
                var widgets_id=JSON.decode(re).widgetid;
                elsource.setProperty('widgets_id',widgets_id);
             }}).request();
             elsource.setStyle('border','1px dashed #333');*/
	},
	addWidget: function(drop, widget, theme) {
		var dialogSetting = {
			modal: true,
			title: LANG_shopwidgets['addWidget'] + (widget.label || ""),
			ajaxoptions: {
				render: false
			},
			width: 0.7,
			height: 0.7
		};
		//this.curdialog=new top.Dialog(top.SHOPADMINDIR+'index.php?ctl=themes&act=doAddWidgets&p[0]='+widget.name+'&p[1]='+theme,dialogSetting);
		this.curdialog = new top.Dialog(top.SHOPADMINDIR + 'index.php?&app=site&ctl=admin_theme_widget&act=do_add_widgets&widgets=' + widget.name + '&widgets_app=' + widget.app + '&widgets_theme=' + widget.theme + '&theme=' + theme, dialogSetting);
		this.curDrop = drop;
	},
	editWidget: function(widget) {

		var dialogSetting = {
			modal: true,
			title: LANG_shopwidgets['editWidget'] + (widget.label || widget.title),
			ajaksable: false,
			width: 0.7,
			height: 0.7
		};
		this.curWidget = $(widget);
		if (widget.get('ishtml')) return this.curdialog = new top.Dialog(top.SHOPADMINDIR + 'index.php?ctl=content/pages&act=editHtml', Object.merge(dialogSetting, {
			ajaksable: true,
			ajaxoptions: {
				method: 'post',
				data: 'htmls=' + encodeURIComponent(widget.getElement('.content-html').get('html').clean().trim())
			},
			title: LANG_shopwidgets['editHTML']
		}));

		//this.curdialog=new top.Dialog(top.SHOPADMINDIR+'index.php?ctl=themes&act=editwidgets&p[0]='+widget.get('widgets_id')+'&p[1]='+widget.get('widgets_theme'),dialogSetting);
		return this.curdialog = new top.Dialog(top.SHOPADMINDIR + 'index.php?app=site&ctl=admin_theme_widget&act=do_edit_widgets&widgets_id=' + widget.get('widgets_id') + '&theme=' + widget.get('widgets_theme'), dialogSetting);

	},
	saveAll: function() {
		var params = [];
		var wpanels = this.drops;
		var file = {};
		wpanels.each(function(item, index) {
			var widgets = item.getElements('.shopWidgets_box');
			widgets.each(function(widgetbox) {
				params.push(("widgets[{widgetsId}]={baseFile}:{baseSlot}:{baseId}").substitute({
					widgetsId: widgetbox.get('widgets_id'),
					baseFile: this.bf,
					baseSlot: this.bs,
					baseId: this.bi
				}));

				if (widgetbox.get('ishtml')) {
					var ch = widgetbox.getElement('.content-html');
					params.push(('html[{widgetsId}]={htmls}').substitute({
						widgetsId: widgetbox.get('widgets_id'),
						htmls: encodeURIComponent(ch.get('html'))
					}));
				}
			},
			{
				mce: this.mce,
				bf: item.get('base_file'),
				bs: item.get('base_slot'),
				bi: item.get('base_id')
			});
			file[item.get('base_file')] = 1;
		}.bind(this));

		for (f in file) {
			params.push(("files[]={file}").substitute({
				file: f
			}));
		}

		new Request({
			url:top.SHOPADMINDIR + 'index.php?app=site&ctl=admin_theme_widget&act=save_all',
			method:'post',
			data:params.join('&'),
			onRequest: function() {
				new top.MessageBox(LANG_shopwidgets['saving']);
			},
			onSuccess: function(re) {
				try {
					re = JSON.decode(re);
					for (dom in re) {
						if ($(dom) && re[dom]) $(dom).setProperty('widgets_id', re[dom]);
					}
					top.MessageBox.success(LANG_shopwidgets['saveSuccess']);
				} catch(e) {
					top.MessageBox.error(LANG_shopwidgets['saveError'] + e.message);
				}
			}
		}).send();
	}
});

window.addEvent('domready', function() {

	this.shopWidgets = new Widgets('.shopWidgets_box', '.shopWidgets_panel', {
		onEdit: function(widget, widget_panel) {
			shopWidgets.editWidget(widget);
		},
		onDelete: function(widget) {
			if (!confirm(LANG_shopwidgets['comfirmDel'])) return;
			var dob = this.drag_operate_box;
			var _this = this;
			dob.setStyle('visibility', 'hidden').store('lock', true);
			var drop = widget.getParent();
			new Fx.Tween(widget).start('opacity', 0).chain(function() {
				widget.destroy();
				dob.store('lock', false);
				_this.checkEmptyDropPanel(drop);
			});
		}
	});

});

