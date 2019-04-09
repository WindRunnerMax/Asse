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
		var aLength = arguments.length;
		var options = arguments[0];
		var target={};
		var copy;
		var i = 1;
		if(typeof options === "boolean" && options === true){
			//深拷贝 (仅递归处理对象)
			for (; i < aLength; i++) {
				if ( ( options = arguments[ i ] ) != null ) {
					if (typeof options !== 'object') {return options;}
					for ( name in options ) {
						copy = options[ name ];
						if ( target === copy ) {continue;}
						target[ name ] = asse.extend(true,options[ name ]);
					}
				}
			}
		}else{
			//浅拷贝
			target=options;
			if (aLength === i) {target=this;i--;} //如果是只有一个参数，拓展asse功能 如果两个以上参数，将后续对象加入到第一个对象
			for (; i < aLength; i++) {
				options = arguments[i];
				for ( name in options ) {
					target[ name ] = options[ name ];
				}
			}
		}
		return target;
	}

	asse.extend({
		popup:{
			icon : ['asse-icon-info','asse-icon-success','asse-icon-error','asse-icon-info'],
			options : {},
			init : function(index,setting){
				// this.closeAll(); //初始化时执行关闭
				this.options = asse.extend(true,this.default);
				this.options = asse.extend(this.options,setting);
				if (this.options.icon > 3 ) {this.options.icon = 3;}
			},
			base:function(index , classNameSet , html ){
				var head = doc.getElementsByTagName('body')[0];
				var node = doc.createElement('div');
				node.id = index;
				node.className = classNameSet;
				var shadeClose = this.options.shadeClose ? "onclick=asse.popup.close(\'"+index+"\') " : "";
				html += this.options.shade ? "<div class='asse-shade' "+shadeClose+"></div>" : "";
				node.innerHTML = html ;
				head.appendChild(node);
				$("#" + index).fadeIn(200);
				if(this.options.time !== 0 ) {setTimeout(() => this.close(index),this.options.time);}
			},
			msg:function(message = "" ,setting = {}){
				var index = "asse-msg" + parseInt( Math.random() * 100);
				this.init(index,setting); //初始化options
				var className = "asse-contain";
				var html = '<div class="asse-msg-contain"><span class='+ this.icon[this.options.icon] +'></span><span class="asse-msg-word">'+message+'</span></div>';
				this.base(index,className,html);
				return index;
			},
			load:function(message = "请稍后" ,setting = {}){
				var index = "asse-msg" + parseInt( Math.random() * 100);
				this.init(index,setting); //初始化options
				var className = "asse-contain";
				var html = '<div class="asse-msg-contain"><span class="asse-icon-load"></span><span class="asse-msg-word">'+message+'</span></div>';
				this.base(index,className,html);
				return index;
			},
			open:function(title="Info",message = "" ,setting = {}){
				var index = "asse-msg" + parseInt( Math.random() * 100);
				this.init(index,setting); //初始化options
				var className = "asse-contain";
				var html = '<div class="asse-open-contain"><div class="asse-open-title"><span class="asse-open-left">'+title+'</span><span class="asse-open-right" onclick=asse.popup.close(\"'+index+'\") ></span></div><div class="asse-open-bottom">'+message+'</div></div>';
				this.base(index,className,html);
				return index;
			},
			close:function(index){
				if(index !== "" && $("#" + index)){
					$("#" + index).fadeOut(300,this.options.end);
					setTimeout(() => this.removeEle(index),500);
				}
			},
			closeAll:function(){
				var that = this;
				$("div[id^='asse-msg']").each(function(){ //jQuery选择器
			　　　　that.close(this.id); 
			　　});
			},
			removeEle:function(index){
				var head = doc.getElementsByTagName('body')[0];
				var node = doc.getElementById(index);
				if(node) head.removeChild(node);
			},
			default : { icon:1 ,time : 2000 ,end : () => {} , shade:false ,shadeClose : true }
		},
        table:{
        	tpl:function(data,column){
        		var html = column.content;
        		html=asse.tpl.compile(html);
        		var fun = new Function('d',html); //作为函数执行
        		return fun(data);
        	},
            handleData : function(){
                var that = this;
                var tableHead = "<tr>";
                this.options.column.forEach(function(value){
                	tableHead+=("<th>" + value.title + "</th>");
                })
                tableHead+="</tr>"
                try{
                    $.ajax({type:"get",async:false,url:that.options.url,dataType:"json",success:data => {
                        var tableBody = "";
                        data.data.forEach(function(value,i){
                            tableBody += "<tr>";
                            that.options.column.forEach(function(value2,k){
                            	if (value2.html && value2.html === true) {
                                	tableBody += ("<td>" + that.tpl(value,value2) + "</td>");
                            	}else{
                                	tableBody += ("<td>" + value[value2.field] + "</td>");
                            	}
                            })
                            tableBody += "</tr>";
                        })
                        var table = '<table class="asse-table" >' + tableHead + tableBody + '</table>';
                        $(this.options.ele).html(table);
                    },error:(e) =>{$(this.options.ele).html("表格数据接口异常"); }});
                }catch(e){
                    $(this.options.ele).html("表格数据接口异常");
                }
            },
            options : {},
            render : function(setting = {}){
                this.options = asse.extend(true,this.default);
                this.options = asse.extend(this.options,setting);
                this.handleData();
            },
            default : { ele:"" , url : "" , column : [] , page: false , limit : 10}
        },
        tpl:{
        	escape: function(html){
		      return String(html||'').replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
		      .replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/'/g, '&#39;').replace(/"/g, '&quot;');
		    },
        	compile:function(html){
        		html = html.replace(/"/g,'\\"').replace(/\{\{(.)*?\}\}/g,function(value){ 
        			return  value.replace("{{",'"+(').replace("}}",')+"');
        		})
        		html = '"use strict";var view = "' + html + '";return view;';
        		return html; //处理成为合适的字符串，作为执行函数
        	}
        }
	});
	
	win.asse = asse;
}(window,document));