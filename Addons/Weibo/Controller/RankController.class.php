<?php

namespace Addons\Weibo\Controller;
use Addons\Weibo\Controller\BaseController;
use Home\Controller\AddonsController;
class RankController extends BaseController{
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
		$row = 10;
		
		//echo $rank;
		// 分页
		$map ['type'] = 1;
		if(isset($_REQUEST['p'])){
			$page_now=$_REQUEST['p'];
		}else{
			$page_now=1;
		}
		$rank_index=array();
		for($i=0;$i<$row;$i++){
			$rank_index[$i]=($page_now-1)*$row+($i+1);
		}
		$this->assign('rank_index',$rank_index);
		$map['ownerid']=$this->member_uid();
		$result = M ( 'weibo_report' )->where ( $map )->order ( 'comments DESC' )->page ( $page, $row )->select ();
		$count = M ( 'weibo_report' )->where ( $map )->count ();
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		$list=array();
		//列表内容
		while(list($key,$val)= each($result)) {
			$wid=$val['wid'];
			$comments=$val['comments'];
			$reposts=$val['reposts'];
			$attitudes=$val['attitudes'];
			//获取微博内容
			$history=M('weibo_history')->where(array('wid'=>$wid))->find();
			$data=json_decode($history['data'],true);
			$num=$data['success_num'];
			if($num!=0){
				$text=$data['success_array'][0]['text'];
				$temp=array('wid'=>$wid,'comments'=>$comments,'reposts'=>$reposts,'attitudes'=>$attitudes,'text'=>$text);
				array_push($list,$temp);
			}
			
		}
		$this->assign('page_now',$page_now);
		$this->assign('list_data',$list);
		$this->assign('message',$message);
		$this->display();
	}
	public function lists_comments(){		
		if (! is_login ()) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			$url = U ( 'home/user/login' );
			redirect ( $url );
		}
		$page = I ( 'p', 1, 'intval' );
		$row = 10;
		$message="";
		//echo $rank;
		// 分页
		$map ['type'] = 1;
		if(isset($_REQUEST['p'])){
			$page_now=$_REQUEST['p'];
		}else{
			$page_now=1;
		}
		$rank_index=array();
		for($i=0;$i<$row;$i++){
			$rank_index[$i]=($page_now-1)*$row+($i+1);
		}
		$this->assign('rank_index',$rank_index);
		$map['ownerid']=$this->member_uid();
		$result = M ( 'weibo_report' )->where ( $map )->order ( 'comments DESC' )->page ( $page, $row )->select ();
		$count = M ( 'weibo_report' )->where ( $map )->count ();
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		$list=array();
		//列表内容
		while(list($key,$val)= each($result)) {
			$wid=$val['wid'];
			$comments=$val['comments'];
			$reposts=$val['reposts'];
			$attitudes=$val['attitudes'];
			//获取微博内容
			$history=M('weibo_history')->where(array('wid'=>$wid))->find();
			$data=json_decode($history['data'],true);
			$num=$data['success_num'];
			if($num!=0){
				$text=$data['success_array'][0]['text'];
				$temp=array('wid'=>$wid,'comments'=>$comments,'reposts'=>$reposts,'attitudes'=>$attitudes,'text'=>$text);
				array_push($list,$temp);
			}
			
		}
		$this->assign('list_data',$list);
		$this->display();
	}
	public function lists_reposts(){		
		if (! is_login ()) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			$url = U ( 'home/user/login' );
			redirect ( $url );
		}
		$page = I ( 'p', 1, 'intval' );
		$row = 10;
		$message="";
		//echo $rank;
		// 分页
		$map ['type'] = 1;
		if(isset($_REQUEST['p'])){
			$page_now=$_REQUEST['p'];
		}else{
			$page_now=1;
		}
		$rank_index=array();
		for($i=0;$i<$row;$i++){
			$rank_index[$i]=($page_now-1)*$row+($i+1);
		}
		$this->assign('rank_index',$rank_index);
		$map['ownerid']=$this->member_uid();
		$result = M ( 'weibo_report' )->where ( $map )->order ( 'reposts DESC' )->page ( $page, $row )->select ();
		$count = M ( 'weibo_report' )->where ( $map )->count ();
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		$list=array();
		//列表内容
		while(list($key,$val)= each($result)) {
			$wid=$val['wid'];
			$comments=$val['comments'];
			$reposts=$val['reposts'];
			$attitudes=$val['attitudes'];
			//获取微博内容
			$history=M('weibo_history')->where(array('wid'=>$wid))->find();
			$data=json_decode($history['data'],true);
			$num=$data['success_num'];
			if($num!=0){
				$text=$data['success_array'][0]['text'];
				$temp=array('wid'=>$wid,'comments'=>$comments,'reposts'=>$reposts,'attitudes'=>$attitudes,'text'=>$text);
				array_push($list,$temp);
			}
			
		}
		$this->assign('list_data',$list);
		$this->display();
	}
	public function lists_attitudes(){		
		if (! is_login ()) {
			Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
			$url = U ( 'home/user/login' );
			redirect ( $url );
		}
		$page = I ( 'p', 1, 'intval' );
		$row = 10;
		$message="";
		//echo $rank;
		// 分页
		$map ['type'] = 1;
		if(isset($_REQUEST['p'])){
			$page_now=$_REQUEST['p'];
		}else{
			$page_now=1;
		}
		$rank_index=array();
		for($i=0;$i<$row;$i++){
			$rank_index[$i]=($page_now-1)*$row+($i+1);
		}
		$this->assign('rank_index',$rank_index);
		$map['ownerid']=$this->member_uid();
		$result = M ( 'weibo_report' )->where ( $map )->order ( 'attitudes DESC' )->page ( $page, $row )->select ();
		$count = M ( 'weibo_report' )->where ( $map )->count ();
		if ($count > $row) {
			$page = new \Think\Page ( $count, $row );
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%' );
			$this->assign ( '_page', $page->show () );
		}
		$list=array();
		//列表内容
		while(list($key,$val)= each($result)) {
			$wid=$val['wid'];
			$comments=$val['comments'];
			$reposts=$val['reposts'];
			$attitudes=$val['attitudes'];
			//获取微博内容
			$history=M('weibo_history')->where(array('wid'=>$wid))->find();
			$data=json_decode($history['data'],true);
			$num=$data['success_num'];
			if($num!=0){
				$text=$data['success_array'][0]['text'];
				$temp=array('wid'=>$wid,'comments'=>$comments,'reposts'=>$reposts,'attitudes'=>$attitudes,'text'=>$text);
				array_push($list,$temp);
			}
			
		}
		$this->assign('page_num',$p);
		$this->assign('list_data',$list);
		$this->display();
	}
}
