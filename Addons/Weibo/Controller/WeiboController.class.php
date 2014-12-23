<?php

namespace Addons\Weibo\Controller;
use Addons\Weibo\Controller\BaseController;
class WeiboController extends BaseController{
function _initialize() {
		parent::_initialize();
}
	public function member_uid(){
		$user=$_SESSION['9xyun_home']['user_auth'];
		$uid=$user['uid'];
		return $uid;
		
	}
	public function lists(){		
		if (! is_login ()) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			$url = U ( 'home/user/login' );
			redirect ( $url );
		}
		$page = I ( 'p', 1, 'intval' );
		// 关键字搜索
		$map ['type'] = 1;
		$key = 'screen_name';
		if (isset ( $_REQUEST [$key] )) {
			$map [$key] = array (
					'like',
					'%' . $_REQUEST [$key] . '%' 
			);
			unset ( $_REQUEST [$key] );
		}
		
		$row = 20;
		$message="";
		// 分页
		
		$map['ownerid']=$this->member_uid();
		$result = M ( 'access_list' )->where ( $map )->order ( 'aid DESC' )->page ( $page, $row )->select ();
		$count = M ( 'access_list' )->where ( $map )->count ();
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		$list=array();
		//数据处理
		while(list($key,$val)= each($result)) {
			$get_time=$val['get_time'];
			$date=$get_time;
			$add=(int)($val['expires_in']/60/60);
			$str='+'.$add.' hour';
			//加上过期时间后与现在相比（现在时间为2014-11-04 10:00:00）
			//以后将下面的strtotime('2014-11-04 10:00:00')替换为strtotime(date('Y-m-d H:i:s'))
			$time=date('Y-m-d H:i:s',strtotime($str,strtotime($get_time)));
			//echo $get_time."<br>";
			//echo $time."<br>"; 
			if(strtotime($time)>strtotime(date('Y-m-d H:i:s'))){
				//echo "可用";
				$available="可用";
				$available_mark="enable";
			}else{
			//echo "不可用";
				$available="过期";
				$available_mark="disabled";	
			}
			//路径

			$temp=array('aid'=>$val['aid'],'ownerid'=>$val['ownerid'],'screen_name'=>$val['screen_name'],'uid'=>$val['uid'],'available_mark'=>$available_mark,'available'=>$available,'date'=>$date);
			array_push($list,$temp);
		}
		//数据处理结束
		$this->assign ( 'list_data', $list);

		$res ['title'] = '插件管理';
		$res ['url'] = U ( 'main' );
		$res ['class'] = 'current';
		$nav [] = $res;
		
		$this->assign ( 'nav', $nav );
		$this->assign('message',$message);
		//$delete_url=addons_url('Weibo://Weibo/access_delete');
		$this->assign('delete_url',$delete_url);
		$this->display();
	}
	public function del(){
		$ids = I ( 'id', 0 );
		if (empty ( $ids )) {
			$ids = array_unique ( ( array ) I ( 'ids', 0 ) );
		}
		if ($ids[0]==0 || !isset($ids)) {
			$this->error ( '请选择要操作的数据!' );
		}else{
			$map = array (
					'aid' => array (
							'in',
							$ids 
					),
					'ownerid'=>$this->member_uid(),
			);
			//现在ids已经完成了数组序列化
			$access_list=M('access_list');
			$result=$access_list->where($map)->delete();
			if($result){
				$this->success ( '删除成功');
			}else{
				$this->error ( '删除失败！' );
			}
			
			//	$this->error ( '删除失败！' );
		}
		

	}
	public function update(){
		$ids = I ( 'id', 0 );
		if (empty ( $ids )) {
			$ids = array_unique ( ( array ) I ( 'ids', 0 ) );	
		}
		if ($ids[0]==0 || !isset($ids)) {
			$this->error ( '请选择要操作的数据!' );
		}else{
			$map = array (
					'id' => array (
							'in',
							$ids 
					) 
			);
			//现在ids已经完成了数组序列化
			$a='';
			$aids=array();
			$aids=$ids;
			$data=array();
			for($i=0;$i<count($aids);$i++){
				$temp=(int)$aids[$i];
				array_push($data,$temp);
			}
			$date=date('Y-m-d H:i:s');
			$json_data=json_encode(array('aids'=>$data,'lasttime'=>$date));
			$ownerid=$this->member_uid();
			$available_token=M('available_token');
			$find=$available_token->where(array('ownerid'=>$ownerid))->find();
			$data=array();
			$data['ownerid']=$ownerid;
			$data['available_list']=$json_data;
			if($find){
				//该用户已存在记录 需要更新
				$ctl=$available_token->where(array('id'=>$find['id']))->save($data);
			}else{
				$ctl=$available_token->add($data);
			}
			if($ctl){
				$this->success ( '帐号设置成功'.$a ,addons_url('Weibo://Editor/lists'));
			}else{
				$this->error ( '删除失败！' );
			}
		}
	}

}
