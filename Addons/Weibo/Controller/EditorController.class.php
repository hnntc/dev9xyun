<?php

namespace Addons\Weibo\Controller;
use Addons\Weibo\Controller\BaseController;
use Home\Controller\AddonsController;
class EditorController extends BaseController{
function _initialize() {
		parent::_initialize();
}
	public function member_uid(){
		$user=$_SESSION['9xyun_home']['user_auth'];
		$uid=$user['uid'];
		return $uid;
		
	}

	public function lists(){		
		$this->display();
	}
	public function getEmoji(){
		
		if(isset($_GET['index'])){
			$index=$_GET['index'];
		}else{
			$index=1;
		}
		$url='Addons://WeiboEditor@WeiboEditor/getEmoji/'.$index;
		$this->display(T($url));
	}
	//图像上传
    public function uploadify()
    {
        if (!empty($_FILES)) {
            //图片上传设置
            $config = array(   
                'maxSize'    =>    3145728, 
                'savePath'   =>    '',  
                'saveName'   =>    array('uniqid',''), 
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
                'autoSub'    =>    true,   
                'subName'    =>    array('date','Ymd'),
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            $images = $upload->upload();
            //判断是否有图
            if($images){
                $info=$images['Filedata']['savepath'].$images['Filedata']['savename'];
                //返回文件地址和名给JS作回调用
               $this->ajaxReturn($info);
            }
            else{
                //$this->error($upload->getError());//获取失败信息
            }
        }
    }
	public function del(){
		$ids = I ( 'id', 0 );
		if (empty ( $ids )) {
			$ids = array_unique ( ( array ) I ( 'ids', 0 ) );
		}
		if (empty ( $ids )) {
			$this->error ( '请选择要操作的数据!' );
		}
		
		//$Model = M ( get_table_name ( $this->model ['id'] ) );
		$map = array (
				'id' => array (
						'in',
						$ids 
				) 
		);
		$a="";
		for($i=0;$i<count($ids);$i++){
			$a.=$ids[$i]."--";
		}
		//$map ['token'] = get_token ();
		//if ($Model->where ( $map )->delete ()) {
		$this->success ( '删除成功'.$a );
		//} else {
		//	$this->error ( '删除失败！' );
		//}
	}
}
