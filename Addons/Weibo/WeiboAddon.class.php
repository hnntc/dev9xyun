<?php

namespace Addons\Weibo;
use Common\Controller\Addon;

/**
 * 微博插件插件
 * @author 无名
 */

    class WeiboAddon extends Addon{

        public $info = array(
            'name'=>'Weibo',
            'title'=>'微博插件',
            'description'=>'这是一个临时描述',
            'status'=>1,
            'author'=>'无名',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>1         
        );

	public function install() {
		$install_sql = './Addons/Weibo/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		//添加菜单项
		/*
		INSERT INTO `wp_addon_category` (`id`, `icon`, `title`, `sort`) VALUES(50, 74, '微博', 0);
		INSERT INTO `wp_addons` (`name`, `title`, `description`, `status`, `status`, `author`, `version`, `create_time`, `has_adminlist`, `type`, `cate_id`) VALUES( 'WeiboEditor', '微博编辑器', '微博编辑器', 1, '{"random":"1"}', 'han', '0.1', 1418191805, 0, 0, 50);
		( 'WeiboEditor', '微博编辑器', '微博编辑器', 1, '{"random":"1"}', 'han', '0.1', 1418191805, 0, 0, 50);
		
		*/
		$cdata=array();
		$cdata['icon']=74;
		$cdata['title']='微博';
		$cdata['sort']=0;
		$find_mother=M('addon_category')->where(array('title'=>'微博'))->find();
		if($find_mother){
			$addon_category_id=$find_mother['id'];
		}else{
			$addon_category_id=M('addon_category')->add($cdata);
		}
		if($addon_category_id){
			return true;
		}else{
			//$this->error("前台列表母项建立失败！请手动添加！");
			return false;
		}
		
	}
	public function uninstall() {
		$d1=M('addons')->where(array('name'=>'Weibo'))->delete();
		$d2=M('hooks')->where(array('name'=>'weiboEditor'))->delete();
		$d3=M('addon_category')->where(array('title'=>'微博'))->delete();
		$uninstall_sql = './Addons/Weibo/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }