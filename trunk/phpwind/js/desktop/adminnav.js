/**
 * 后台导航功能
 * 2009-11-8 lh
 * @use 
 * var menus = adminNavClass.get('Sphinx全文索引',mains,menus);
 */
var adminNavClass = { 
	/*导航数组 全局*/
	navArray : [],
	navMain  : [],
	/*分割标识符*/
	sing : "|",
	
	/*剥离对象组装数组*/
	strip : function(obj,prefix){
		for(i in obj){
			this.navArray.push(prefix+this.sing+obj[i].id+this.sing+obj[i].name);
			if(obj[i].items){
				this.strip(obj[i].items,prefix+this.sing+obj[i].id);
			}
		}
		return this.navArray;
	},

	/*剥离主菜单与子菜单*/
	stripMain : function(mainobj,menuobj){
		for(i in mainobj){
			var id = mainobj[i].id;
			var name = mainobj[i].name;
			this.navArray.push(id+this.sing+name);
			var obj = menuobj[id] ? menuobj[id]['items'] : 0;/*菜单对象数组访问*/
			obj ? this.strip(obj,id) : 0;
		}
		return this.navArray;
	},
	
	/*组装主菜单*/
	buildMain : function(mainobj){
		for(i in mainobj){
			var id = mainobj[i].id;
			var name = mainobj[i].name;
			this.navMain[id] = name;
		}
	},

	/*初始化菜单数据*/
	init : function(mainobj,menuobj){
		this.stripMain(mainobj,menuobj);
		this.buildMain(mainobj);
	},
	
	/*获取菜单导航*/
	get : function(name,mainobj,menuobj){
		if(typeof(mainobj) != "object" || typeof(menuobj) != "object" || name == ""){
			//return alert(this.language('data_error'));
			return null;
		}
		this.init(mainobj,menuobj);/*初始化*/
		if(this.navArray.length <= 0){
			//return alert(this.language('data_error'));
			return null;
		}
		var result = null;/*是否存在多个相同的菜单*/
		for(var i=0;i<this.navArray.length;i++){
			if(this.navArray[i].indexOf(name) != "-1"){
				result = this.navArray[i];
			}
		}
		if(result === null){
			//return alert(this.language('data_not_exist'));
			return null;
		}
		/*分割*/
		var keys = result.split(this.sing);
		var length = keys.length;/*菜单层次length-1级*/
		var menus = [];
		var topmenu = keys[0] ? keys[0] : '';
		if(topmenu){
			this.navMain[topmenu] ? menus.push(this.navMain[topmenu]) : '';/*主菜单部分*/
		}
		for(var i=0;i<length;i++){
			var menu = this.getNav(keys[i],i+2);
			menu ? menus.push(menu) : 0;
		}
		return menus;
	},

	/*获取导航*/
	getNav : function(id,depth){
		for(var i=0;i<this.navArray.length;i++){
			if(this.navArray[i].indexOf(this.sing+id+this.sing) != "-1" && this.navArray[i].split(this.sing).length == depth){
				return this.navArray[i].split(this.sing)[depth-1];
			}
		}
	},
	
	/*语言*/
	language : function(key){
		var m = [];
		m['data_error'] = "数据格式不正确";
		m['data_not_exist'] = "查找的菜单不存在";
		return m[key];
	}
}