~function()
{
function FlashPlayer(url,id)
{
	return '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="1" height="1" id="'+id+'"><param name="movie" value="'+url+'" ><embed src="'+url+'" name="'+id+'" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"  type="application/x-shockwave-flash" FlashVars="'+url.replace(/^[^\?]+\?/,'')+'" width="1" height="1"></embed></object>';
}
/**
 *本地数据缓存。
 注意，请将本js需要放在body标签内，以免发生不兼容的问题。
 使用方法：Session.set(key,value);
 Session.get(key);
 */
document.write(FlashPlayer("images/userData.swf","userData"));
Session ={};
/**
 *保存缓存数据
 *@param key 键名
 *@param value 键值
 */
    Session.set =function(key,value)
	{
		document.userData.set(key,value);
	};
/**
 *读取缓存数据
 *@param key 键名
 */
	    Session.get =function(key)
	{
		return document.userData.get(key);
	};
/**
 *删除缓存数据
 *@param key 键名
 */
	    Session.remove =function(key)
	{
		document.userData.remove(key);
	};
}();