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
		var url="{:addons_url('WeiboEditor://WeiboEditor/getEmoji')}";
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
		var url="{:addons_url('WeiboEditor://WeiboEditor/getEmoji')}";
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
	var del_url='{:addons_url("WeiboEditor://WeiboEditor/img_del")}';
	$('#file_upload1').uploadify({
        'swf'      : '__ADDONROOT__/Uploadify/uploadify.swf',
        'uploader' : '{:addons_url("WeiboEditor://WeiboEditor/uploadify")}',
		//'buttonImage' : '__ROOT__/Uploadify/upload.png',
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
				var src='__ROOT__/Uploads/'+ data;//图片路径 需要记录
				//$('#img1').attr('src',src);
				//pic_url[1]=src;
				var html='<div class="imgdiv"><img src="__ADDONROOT__/Uploadify/delete.png" class="delete_icon" title="'+img_count+'"/><img  src="'+src+'"  class="result_img" /></div>';
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
	var EditCode="{$EditCode}";
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
		var url='{:addons_url("WeiboEditor://WeiboEditor/send")}';
		var str=$('#text').val();
		var length=getStrLength(str);
		if(length >=140){
			alert("想要说的太多，我无能为力啊！删点吧！");
		}else if(length==0){
			$('#message_content').html("您确定要发一条空的微博吗？好吧你确定也没用我就是不给你发");
		}else{
			var html='<img src="__ADDONROOT__/img/waiting.gif" class="waiting_gif">';
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
   var uploader='{:addons_url("WeiboEditor://WeiboEditor/xiuxiuupload")}';
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
		 show.innerHTML='<img src="/Uploads/'+data+'" />';
		//var img_array=new Array();
		img_array[0]=data;
		// var show_q=$('#showXiuxiu');
		var editor=document.getElementById("xiuxiuEditor");
		// show.style.display="inline";
		editor.style.display="none";
		
	}
}
