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
		var options = arguments[0];
		var target=this;
		for ( name in options ) {
			var copy = options[ name ];
			target[ name ] = copy;
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
			msg:function(){
				console.log($);
			},
			load:function(){

			},
			close:()=>{

			}
		}
	});

	win.asse = asse;
}(window,document));