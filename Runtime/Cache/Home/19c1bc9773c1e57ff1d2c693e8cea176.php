<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WeiboEditor</title>
<script src="http://open.web.meitu.com/sources/xiuxiu.js" type="text/javascript"></script>
<script src="/weiphp5/Addons/WeiboEditor/js/jquery.js"></script>
<script src="/weiphp5/Addons/WeiboEditor/js/jquery.cursor.js"></script>
<link rel="stylesheet" href="/weiphp5/Addons/WeiboEditor/Uploadify/uploadify.css">
 <script type="text/javascript" src="/weiphp5/Addons/WeiboEditor/Uploadify/jquery.uploadify-3.1.js"></script>
<script>
var img_array=["empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty"];
$(function(){
	
	$('#text').val('');	
	$('#text').keyup(count_char);
	$pic=$('#pic');
	$emoji=$('#emoji');
	$topic=$('#topic');
	$('#text').click(function(){
		//清空样式	
		$pic.removeClass("chosen");
		$emoji.removeClass('chosen');
		$("#xiuxiuEditor").hide();
		//$("#showXiuxiu").hide();
	});
	var pic_url= new Array();
	$('#pic_link').click(function(){
		$pic.addClass("chosen");
		$emoji.removeClass('chosen');
		$("#xiuxiuEditor").hide();
		//$("#showXiuxiu").hide();		
	});
	$('#emoji_link').click(function(){
		$pic.removeClass("chosen");
		$emoji.addClass('chosen');
		$("#xiuxiuEditor").hide();	
		//$("#showXiuxiu").hide();		
		//加载表情
		var url="<?php echo addons_url('WeiboEditor://WeiboEditor/getEmoji');?>";
		$.get(url,function(data){
			$(".emoji_head").html(data);
			//添加表情
			$('.emoji_head li').click(function(){
				var emoji_title=$(this).attr('title');
				var emoji_name='['+emoji_title+']';
				$('#text').insertAtCursor(emoji_name);
				//重新统计字符
				count_char();
			});
			
		});
	});
	//话题
	$('#topic_link').click(function(){
		$pic.removeClass("chosen");
		$emoji.removeClass('chosen');
		$("#xiuxiuEditor").hide();
		//$("#showXiuxiu").hide();
		$('#text').insertAtCursor("##");
		var a=$('#text').getCurPos();
		$('#text').setCurPos(a-1);
	});
	//表情主题
	$('.emoji_li').click(function(){
		index=$(this).index()+1;
		//alert(index);
		$("#xiuxiuEditor").hide();
		$(this).siblings().removeClass('chosen');
		$(this).addClass('chosen');
		var url="<?php echo addons_url('WeiboEditor://WeiboEditor/getEmoji');?>";
		$.get(url,{'index':index},function(data){
			$(".emoji_head").html(data);
			//添加表情
			$('.emoji_head li').click(function(){
				var emoji_title=$(this).attr('title');
				var emoji_name='['+emoji_title+']';
				$('#text').insertAtCursor(emoji_name);
				//重新统计字符
				count_char();
			});
		});
		
		
	});
	
	//上传图片
	
	var img_count=0;
	var true_num=0;
	var max_img=1;
	var del_url='<?php echo addons_url("WeiboEditor://WeiboEditor/img_del");?>';
	$('#file_upload1').uploadify({
        'swf'      : '/weiphp5/Addons/WeiboEditor/Uploadify/uploadify.swf',
        'uploader' : '<?php echo addons_url("WeiboEditor://WeiboEditor/uploadify");?>',
		//'buttonImage' : '/weiphp5/Uploadify/upload.png',
        'buttonText' : '上传',
		'width':'40',
		'height':'20',
		'queueSizeLimit' :max_img,//队列中允许的最大文件数目
		'multi': false,//是否能选择多个文件
		'onUploadStart' : function(){
			var e=max_img-true_num;

			if(e==0){
				//$('#file_upload1').hide();
				$('#file_upload1').uploadify('cancel','*');
				alert("最多一次上传"+max_img+"张图片");
			}
		},
        'onUploadSuccess' : function(file, data, response) {
			//data=eval(data);
			//alert(data)
			var error=data.search(/error/);
			if(error==0){
				alert(data);
			}else{
				var src='/weiphp5/Uploads/'+ data;//图片路径 需要记录
				//$('#img1').attr('src',src);
				//pic_url[1]=src;
				var html='<div class="imgdiv"><img src="/weiphp5/Addons/WeiboEditor/Uploadify/delete.png" class="delete_icon" title="'+img_count+'"/><img  src="'+src+'"  class="result_img" /></div>';
					img_array[img_count]=data+"";
				//var html='<img  src="'+src+'" class="result_img" />';
				$("#file_upload1").before(html);
				//img_array+=data;
				img_count++;//计数
				true_num++;
				//加载删除按钮
					$(".delete_icon").unbind("click");  
					$('.delete_icon').bind('click',function(){
						var title=$(this).attr('title');
						title=parseInt(title);
						
						var img_name=img_array[title];
						$.get(del_url,{'img_name':img_name},function(data){
							
						});
						//alert("删除第"+title+"张图片");
						$(this).parent().remove();
						true_num--;
						img_array[title]="empty";
					});

			}
        },
    });
	//发送数据
	var EditCode="<?php echo ($EditCode); ?>";
	$('#submit').click(function(){
		//清空下拉样式
		$pic.removeClass("chosen");
		$emoji.removeClass('chosen');
		//数据处理
		//图片数组
		var index=0;
		var images_list=new Array();
		for(var i=0;i<img_array.length;i++){
			if(img_array[i]!="empty"){
				images_list[index]=img_array[i];
				index++;
			}
		}
		$("#showXiuxiu").hide();
		//图片数组
		//alert(images_list[0]);
		var text_info=$('#text').val();
		var info={'EditCode':EditCode,'text_info':text_info,'pic_urls':images_list};
		var url='<?php echo addons_url("WeiboEditor://WeiboEditor/send");?>';
		var str=$('#text').val();
		var length=getStrLength(str);
		if(length >=140){
			alert("想要说的太多，我无能为力啊！删点吧！");
		}else if(length==0){
			$('#message_content').html("您确定要发一条空的微博吗？好吧你确定也没用我就是不给你发");
		}else{
			var html='<img src="/weiphp5/Addons/WeiboEditor/img/waiting.gif" class="waiting_gif">';
			$('#message_content').html(html);
			$.post(url,info,function(data){
				$("#text").val("");
				$('#text').focus();
				data=eval(data);
				if(data['status'] == "nolgin"){
					$('#message_content').html("您还没有登陆");
				}else if(data['status']=='notoken'){
					$('#message_content').html("您还没有有效的授权帐号，点击左边添加吧！");
				}else if(data['status']=="nocontent"){
					$('#message_content').html("您确定要发一条空的微博吗？好吧你确定也没用我就是不给你发");
				}else{
					var num1=data['success_num'];
					var html_success='<div class="success"><div class="success_num">成功数量：'+num1+'</div><div class="success_user">';
					for(var i=0;i<num1;i++){
						html_success+='<li>'+data['success_array'][i]['screen_name']+'</li>';
					}
					html_success+='</div></div>';
					
					var num2=data['error_num'];
					var html_error='<div class="error"><div class="first">错误数量：'+num2+'</div>';
					for(var i=0;i<num2;i++){
						html_error+='<li><div class="second">帐号：'+data['error_array'][i]['screen_name']+'</div><div class="third">reason:'+data['error_array'][i]['error_message']['error_text']+'错误码：'+data['error_array'][i]['error_message']['error_code']+'</div></li>';
					}
					html_error+='</div>';
					var html=html_success+html_error;
					$('#message_content').html(html);
				}
			},'json');
			
		}
		
	});
	//拼图
	$('#pics_link').click(showXiuxiu);
	$('#pics_link').click(function(){
		$("#xiuxiuEditor").show();
		//$("#showXiuxiu").hide();
		$pic.removeClass("chosen");
		$emoji.removeClass('chosen');
	});
    
})
function getStrLength(str) { 
    var len = str.length; 
    var reLen = 0; 
    for (var i = 0; i < len; i++) {        
        if (str.charCodeAt(i) < 27 || str.charCodeAt(i) > 126) { 
            // 全角    
            reLen += 2; 
        } else { 
            reLen++; 
        } 
    } 
	//length=parseInt((reLen/2));
	length=Math.ceil((reLen/2));
    return length;    
}
function count_char(){
	var str=$('#text').val();
	var length=getStrLength(str);
	var slength=140-length;
	$('.count_num').html("(还可以输入"+slength+"个字)");
}
function showXiuxiu(){
/*第1个参数是加载编辑器div容器，第2个参数是编辑器类型，第3个参数是div容器宽，第4个参数是div容器高*/
	xiuxiu.embedSWF("altContent",2,"600px","400px");
	//修改为您自己的图片上传接口
   var uploader='<?php echo addons_url("WeiboEditor://WeiboEditor/xiuxiuupload");?>';
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
		//
		//alert("上传响应" + data);  //可以开启调试
		
		 var show=document.getElementById("showXiuxiu");
		 var url="/weiphp5/Uploads/"+data;
		 show.innerHTML='<img src="'+url+'" />';
		//var img_array=new Array();
		img_array[0]=data;
		// var show_q=$('#showXiuxiu');
		var editor=document.getElementById("xiuxiuEditor");
		// show.style.display="inline";
		editor.style.display="none";
		
	}
}

</script>
<style>
#main_editor{
	margin:0px;
	padding:0px;
	width:360px;
	height:280px;
	float:left;
	border:0px solid red;
}
#main_editor #text{
	resize: none;
	width:358px;
	height:70px;
	font-size:18;
	padding:0px;
	border:2px #999 solid;
	
}
#main_editor .ttl{
	font-size:18px;
	color:#666;
	font-weight:bold;
}
#main_editor .count_num{
	font-size:10px;
	margin-left:-10px;
}
#main_editor #booter{
	width:360px;
	height:30px;
	border-bottom:0px solid red;
	margin-top:3px;
	float：:left;
}
/*表情*/
#emoji{
	position:relative;
	float:left;
	width:40px;
	height:32px;
	margin-left:10px;
}
#emoji_list{
	width:340px;
	height:150px;
	border:1px solid #999;
	margin-top:33px;
	margin-left:0px;
	overflow:hidden;
	display:none;
}
#emoji_link{
	position:absolute;
	left:0px;
	top:0px;
	width:30px;
	height:27px;
	cursor:pointer;
	padding-left:4px;
	padding-top:5px;
}
/*图片*/
#pic{
	position:relative;
	float:left;
	width:40px;
	height:32px;
	margin-left:10px;
}
#pic_list{
	width:300px;
	height:100px;
	border:1px solid #999;
	margin-top:33px;
	margin-left:-50px;
	display:none;
}
#pic_link{
	position:absolute;
	left:0px;
	top:0px;
	width:30px;
	height:27px;
	cursor:pointer;
	padding-left:4px;
	padding-top:5px;
}
/*话题*/
#topic{
	position:relative;
	float:left;
	width:40px;
	height:32px;
	margin-left:10px;
}
#topic{
	padding-left:4px;
	padding-top:5px;
	cursor:pointer;
}

#submit{
	cursor:pointer;
	width:90px;
	height:40px;
	float:left;
	margin-left:30px;
}
/*拼图*/
#pics{
	position:relative;
	float:left;
	height:25px;
	margin-left:5px;
	margin-top:5px;
	border:0px solid red;
}
#pics_link{
	position:relative;
	float:left;
	height:25px;
	margin-left:5px;
	cursor:pointer;
}
/*显示隐藏*/
.chosen  #emoji_list{
	display:block;
}

.chosen #pic_list{
	display:block;	
}
/*图片下拉*/
#img_container{
	float:left;	
}
#img_container li{
	list-style:none;
	float:left;
	margin-left:5px;
	margin-right:3px;
	
}
/*表情*/
#emoji_list .emoji_head{
	height:120px;
	width:330px;
	float:left;
	border:1px solid #999;
	margin-left:4px;
	overflow-y:scroll;
	overflow-x:none;
}
#emoji_list .emoji_head li{
	list-style:none;
	float:left;
	width:23px;
	height:23px;
	border-right:#666 1px dashed;
	border-bottom:#666 1px dashed;
	cursor:pointer;
}
#emoji_list .emoji_bar{
	width:auto;
	height:22px;
	background:#ccc;
	float:left;
	margin-top:4px;
	margin-left:4px;
	padding:1px 1px 1px -1px;
	border:1px solid #999;
	
} 
#emoji_list .emoji_bar .emoji_li{
	list-style:none;
	border-left:1px solid #999;
	width:auto;
	padding:0 3px 0 3px;
	height:20px;
	color:#555;
	font-size:14px;
	cursor:pointer;
	float:left;

}
#emoji_list .emoji_bar .emoji_li:hover{
	color:#F30;
}
#emoji_list .emoji_bar .chosen{
	border-bottom:#F30 2px solid;
}
#show{
	width:360px; 
	height:auto; 
	border-top:0px solid #ccc; 
	margin-top:22px;
	margin-left:100px;
	float:left;
}
#message_content{
	width:auto;
	height:auto;
	color:#666;
	margin:0 auto;	
}
/*结果显示*/
#message_content{
	width:360px;
	height:120px;
	border:0px solid green;
	
}
.success{
	width:340px;
	height:auto;
	border-bottom:1px dashed red;
	float:left;
}
.success_num{
	height:auto;
	width:200px;
	font-size:16px;
	border-bottom:0px solid black;
}
.success_user{
	width:300px;
	height:auto;
	border:1px dashed #ccc;
	float:left;
	margin-left:30px;
}
.success_user li{
	width:auto;
	height:auto;
	float:left;
	margin-left:4px;
	list-style:none;
	background:#CCC;
}
.error{
	width:340px;
	height:auto;
	border-bottom:1px dashed red;
	float:left;
}
.error_num{
	height:auto;
	width:200px;
	font-size:16px;
	border-bottom:0px solid black;
}
.error li{
	width:300px;
	height:auto;
	border:1px dashed #ccc;
	margin-left:30px;
	list-style:none;
}
.error li .second{
	
}
.error li .third{
	width:280px;
	height:auto;
	border:1px solid #CCC;
	margin-left:20px;
	word-wrap:break-word;
	
}
.imgdiv{
	position:relative;
	border:1px dashed #999;
	width:42px;
	height:40px;
	margin:0px;
	padding:0px;
	float:left;
	margin-left:3px;
	margin-top:30px;

}
.result_img{
	position:absolute;
	left:0px;
	width:42px; 
	height:42px; 
	border:0px;
}
.delete_icon{
	position:absolute;
	left:32px;
	width:10px;
	height:10px;
	cursor:pointer;
	margin:0px;
	z-index:200;
}
.waiting_gif{
width:350px;
height:30px;
}
#xiuxiuEditor{
	position:relative;
	left:-360px;
	top:20px;

}
#showXiuxiu{
	position:relative;
	left:0px;
	top:160px;
	border:0px solid red;
	width:360px;
}
#showXiuxiu img{
	position:absolute;
	left:0px;
	top:0px;
	margin:0px;
	padding:0;
	width:360px;
}
</style>
</head>

<body>

<div id="main_editor">
	<div id="editor_title">
    	<span class="ttl">请在这里输入您要说的内容！</span><span class="count_num">(还可以输入140个字)</span>
    </div>
	<form id="editor_form" action="" method="post">
    	<textarea id="text" name="text">
        </textarea>
    
    <div id="booter" >
    	<div id="emoji">
        	<div id="emoji_list">
            	<div class="emoji_head">
                	
                </div>
                <div class="emoji_bar">
                	<li class="emoji_li chosen">默认</li>
                    <li class="emoji_li">浪小花</li>
                    <li class="emoji_li">暴走漫画</li>
                    <li class="emoji_li">小恐龙</li>
                    <li class="emoji_li">冷兔</li>
                </div>
            </div>
            <div id="emoji_link">
            <img src="/weiphp5/Addons/WeiboEditor/img/emoji.png">
            </div>
        </div>
        <div id="pic" >
        	<div id="pic_list">
            	<div id="img_container">	
                	<li id="pic1">
						<input id="file_upload1" name="file_upload" type="file" multiple="true" value="" />
							<!--<div class="imgdiv">
								<img src="/weiphp5/Uploadify/delete.png" class="delete_icon"/>
								<img  src="/weiphp5/Uploadify/wait.png"  class="result_img" />
							</div>-->
                    </li>
    
                </div>
            </div>
            <div id="pic_link">
             <img src="/weiphp5/Addons/WeiboEditor/img/pic.png">
            </div>
        </div>
		<div id="pics">
        	<div id="pics_link">
        		<img src="/weiphp5/Addons/WeiboEditor/img/pics.png">
             </div>
        </div>
        <div id="topic">
        	<div id="topic_link">
        		<img src="/weiphp5/Addons/WeiboEditor/img/topic.png">
             </div>
        </div>
        <div id="submit">
        	<img src="/weiphp5/Addons/WeiboEditor/img/sub.png">
        </div>
    </div>
    
    </form> 
</div>
<div id="show" > 
    <div id="message_content">
        
	</div>
</div>
<div id="altContent" class="span4" style="display:none;">
</div>
<div id="showXiuxiu">
</div>
</body>
</html>