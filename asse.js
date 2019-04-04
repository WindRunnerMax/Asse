"use strict";
/**
 * 检查jQuery是否引入 [若引入务必在引入此Js前引入]
 */
(function(win,doc){
	if (!win.jQuery) {
		var head = doc.getElementsByTagName('head')[0];
		var node = doc.createElement('script');
		node.type = "text/javascript";
		node.src = "static/jquery-1.11.1.min.js";
		head.appendChild(node);
	}
}(window,document));

/**
 * 参考jQuery的源码实现
 */

(function(win,doc) {
	var asse = function(){
		return new asse.fn.init();
	}
	asse.fn=asse.prototype = {
		init : function(){
			return this;
		}
	}
	asse.fn.init.prototype=asse.fn;

	asse.extend = asse.fn.extend = function (){
		//实现浅拷贝
		var aLength = arguments.length;
		var options = arguments[0];
		var target=options;
		var i = 1;
		if (aLength === i) {target=this;i--;} //如果是只有一个参数，拓展asse功能 如果两个以上参数，将后续对象加入到第一个对象
		for (; i < aLength; i++) {
			options = arguments[i];
			for ( name in options ) {
				var copy = options[ name ];
				target[ name ] = copy;
			}
		}
		return target;
	}

	asse.extend({
		popup:{
			icon : {
				success : '.asse-icon-success',
				error : '.asse-icon-error',
				info : '.asse-icon-info'
			},
			options :{
				message : "" ,
				time : 2000 ,
				end : () => {}
			},
			msg:function(setting = {}){
				this.options = asse.extend(this.options,setting);
				var index = "asse-suc";
				var head = doc.getElementsByTagName('body')[0];
				var node = doc.createElement('div');
				node.id = index;
				node.className = "asse-contain";
				var msg = '<div class="asse-msg-contain"><span class="asse-icon-success"></span><span class="asse-msg-word">'+this.options.message+'</span></div>';
				node.innerHTML = msg;
				head.appendChild(node);
				$("#" + index).fadeIn(200);
				if(this.options.time !== 0) {setTimeout(() => this.close(index),this.options.time);}
				return index;
			},
			load:function(){

			},
			close:function(index){
				$("#" + index).fadeOut(300,this.options.end);
				setTimeout(() => this.removeEle(index),500);
			},
			removeEle:function(index){
				var head = doc.getElementsByTagName('body')[0];
				var node = doc.getElementById(index);
				head.removeChild(node);
			}
		}
	});

	win.asse = asse;
}(window,document));