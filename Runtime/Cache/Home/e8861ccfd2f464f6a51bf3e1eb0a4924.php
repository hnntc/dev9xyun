<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>美图WEB开放平台</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="http://open.web.meitu.com/sources/xiuxiu.js" type="text/javascript"></script>
<script type="text/javascript">

window.onload=function(){
    var btn=document.getElementById("imgP");
	 btn.onclick=function(){
		/*第1个参数是加载编辑器div容器，第2个参数是编辑器类型，第3个参数是div容器宽，第4个参数是div容器高*/
		xiuxiu.embedSWF("altContent",2,"600px","400px");
		   //修改为您自己的图片上传接口
		   var uploader='<?php echo addons_url("Xiuxiu://Xiuxiu/upload");?>';
		xiuxiu.setUploadURL(uploader);
			xiuxiu.setUploadType(2);
			xiuxiu.setUploadDataFieldName("upload_file");
		xiuxiu.onInit = function ()
		{
			xiuxiu.loadPhoto("");
			//xiuxiu.loadPhoto("http://open.web.meitu.com/sources/images/1.jpg");//有初始化的图片
		}	
		xiuxiu.onUploadResponse = function (data)
		{
			//alert("上传响应" + data);  //可以开启调试
			 var show=document.getElementById("show");
			 show.innerHTML=data;
		}
	}
 
}
</script>
<style type="text/css">
	html, body { height:100%; overflow:hidden; }
	body { margin:0; }
</style>
</head>
<body>
<div >
<input type="button" id="imgP" value="拼图">
</div>
<div id="altContent">
	<h1>美图秀秀</h1>
</div>
<div id="show">
</div>
</body>
</html>