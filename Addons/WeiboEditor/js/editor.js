var img_array=["empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty","empty"];
$(function(){
	
	$('#text').val('');	
	$('#text').keyup(count_char);
	$pic=$('#pic');
	$emoji=$('#emoji');
	$topic=$('#topic');
	$('#text').click(function(){
		//�����ʽ	
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
		//���ر���
		var url="{:addons_url('WeiboEditor://WeiboEditor/getEmoji')}";
		$.get(url,function(data){
			$(".emoji_head").html(data);
			//��ӱ���
			$('.emoji_head li').click(function(){
				var emoji_title=$(this).attr('title');
				var emoji_name='['+emoji_title+']';
				$('#text').insertAtCursor(emoji_name);
				//����ͳ���ַ�
				count_char();
			});
			
		});
	});
	//����
	$('#topic_link').click(function(){
		$pic.removeClass("chosen");
		$emoji.removeClass('chosen');
		$("#xiuxiuEditor").hide();
		//$("#showXiuxiu").hide();
		$('#text').insertAtCursor("##");
		var a=$('#text').getCurPos();
		$('#text').setCurPos(a-1);
	});
	//��������
	$('.emoji_li').click(function(){
		index=$(this).index()+1;
		//alert(index);
		$("#xiuxiuEditor").hide();
		$(this).siblings().removeClass('chosen');
		$(this).addClass('chosen');
		var url="{:addons_url('WeiboEditor://WeiboEditor/getEmoji')}";
		$.get(url,{'index':index},function(data){
			$(".emoji_head").html(data);
			//��ӱ���
			$('.emoji_head li').click(function(){
				var emoji_title=$(this).attr('title');
				var emoji_name='['+emoji_title+']';
				$('#text').insertAtCursor(emoji_name);
				//����ͳ���ַ�
				count_char();
			});
		});
		
		
	});
	
	//�ϴ�ͼƬ
	
	var img_count=0;
	var true_num=0;
	var max_img=1;
	var del_url='{:addons_url("WeiboEditor://WeiboEditor/img_del")}';
	$('#file_upload1').uploadify({
        'swf'      : '__ADDONROOT__/Uploadify/uploadify.swf',
        'uploader' : '{:addons_url("WeiboEditor://WeiboEditor/uploadify")}',
		//'buttonImage' : '__ROOT__/Uploadify/upload.png',
        'buttonText' : '�ϴ�',
		'width':'40',
		'height':'20',
		'queueSizeLimit' :max_img,//���������������ļ���Ŀ
		'multi': false,//�Ƿ���ѡ�����ļ�
		'onUploadStart' : function(){
			var e=max_img-true_num;

			if(e==0){
				//$('#file_upload1').hide();
				$('#file_upload1').uploadify('cancel','*');
				alert("���һ���ϴ�"+max_img+"��ͼƬ");
			}
		},
        'onUploadSuccess' : function(file, data, response) {
			//data=eval(data);
			//alert(data)
			var error=data.search(/error/);
			if(error==0){
				alert(data);
			}else{
				var src='__ROOT__/Uploads/'+ data;//ͼƬ·�� ��Ҫ��¼
				//$('#img1').attr('src',src);
				//pic_url[1]=src;
				var html='<div class="imgdiv"><img src="__ADDONROOT__/Uploadify/delete.png" class="delete_icon" title="'+img_count+'"/><img  src="'+src+'"  class="result_img" /></div>';
					img_array[img_count]=data+"";
				//var html='<img  src="'+src+'" class="result_img" />';
				$("#file_upload1").before(html);
				//img_array+=data;
				img_count++;//����
				true_num++;
				//����ɾ����ť
					$(".delete_icon").unbind("click");  
					$('.delete_icon').bind('click',function(){
						var title=$(this).attr('title');
						title=parseInt(title);
						
						var img_name=img_array[title];
						$.get(del_url,{'img_name':img_name},function(data){
							
						});
						//alert("ɾ����"+title+"��ͼƬ");
						$(this).parent().remove();
						true_num--;
						img_array[title]="empty";
					});

			}
        },
    });
	//��������
	var EditCode="{$EditCode}";
	$('#submit').click(function(){
		//���������ʽ
		$pic.removeClass("chosen");
		$emoji.removeClass('chosen');
		//���ݴ���
		//ͼƬ����
		var index=0;
		var images_list=new Array();
		for(var i=0;i<img_array.length;i++){
			if(img_array[i]!="empty"){
				images_list[index]=img_array[i];
				index++;
			}
		}
		$("#showXiuxiu").hide();
		//ͼƬ����
		//alert(images_list[0]);
		var text_info=$('#text').val();
		var info={'EditCode':EditCode,'text_info':text_info,'pic_urls':images_list};
		var url='{:addons_url("WeiboEditor://WeiboEditor/send")}';
		var str=$('#text').val();
		var length=getStrLength(str);
		if(length >=140){
			alert("��Ҫ˵��̫�࣬������Ϊ������ɾ��ɣ�");
		}else if(length==0){
			$('#message_content').html("��ȷ��Ҫ��һ���յ�΢���𣿺ð���ȷ��Ҳû���Ҿ��ǲ����㷢");
		}else{
			var html='<img src="__ADDONROOT__/img/waiting.gif" class="waiting_gif">';
			$('#message_content').html(html);
			$.post(url,info,function(data){
				$("#text").val("");
				$('#text').focus();
				data=eval(data);
				if(data['status'] == "nolgin"){
					$('#message_content').html("����û�е�½");
				}else if(data['status']=='notoken'){
					$('#message_content').html("����û����Ч����Ȩ�ʺţ���������Ӱɣ�");
				}else if(data['status']=="nocontent"){
					$('#message_content').html("��ȷ��Ҫ��һ���յ�΢���𣿺ð���ȷ��Ҳû���Ҿ��ǲ����㷢");
				}else{
					var num1=data['success_num'];
					var html_success='<div class="success"><div class="success_num">�ɹ�������'+num1+'</div><div class="success_user">';
					for(var i=0;i<num1;i++){
						html_success+='<li>'+data['success_array'][i]['screen_name']+'</li>';
					}
					html_success+='</div></div>';
					
					var num2=data['error_num'];
					var html_error='<div class="error"><div class="first">����������'+num2+'</div>';
					for(var i=0;i<num2;i++){
						html_error+='<li><div class="second">�ʺţ�'+data['error_array'][i]['screen_name']+'</div><div class="third">reason:'+data['error_array'][i]['error_message']['error_text']+'�����룺'+data['error_array'][i]['error_message']['error_code']+'</div></li>';
					}
					html_error+='</div>';
					var html=html_success+html_error;
					$('#message_content').html(html);
				}
			},'json');
			
		}
		
	});
	//ƴͼ
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
            // ȫ��    
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
	$('.count_num').html("(����������"+slength+"����)");
}
function showXiuxiu(){
/*��1�������Ǽ��ر༭��div��������2�������Ǳ༭�����ͣ���3��������div��������4��������div������*/
	xiuxiu.embedSWF("altContent",2,"600px","400px");
	//�޸�Ϊ���Լ���ͼƬ�ϴ��ӿ�
   var uploader='{:addons_url("WeiboEditor://WeiboEditor/xiuxiuupload")}';
	xiuxiu.setUploadURL(uploader);
	xiuxiu.setUploadType(2);
	xiuxiu.setUploadDataFieldName("upload_file");
	xiuxiu.onInit = function ()
	{
		xiuxiu.loadPhoto("");
		//xiuxiu.loadPhoto("http://open.web.meitu.com/sources/images/1.jpg");//�г�ʼ����ͼƬ
	}	
	xiuxiu.onUploadResponse = function (data)
	{
		//
		//alert("�ϴ���Ӧ" + data);  //���Կ�������
		
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
