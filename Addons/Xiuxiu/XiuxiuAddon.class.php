<?php

namespace Addons\Xiuxiu;
use Common\Controller\Addon;

/**
 * 秀秀拼图插件
 * @author han
 */

    class XiuxiuAddon extends Addon{

        public $info = array(
            'name'=>'Xiuxiu',
            'title'=>'秀秀拼图',
            'description'=>'秀秀拼图',
            'status'=>1,
            'author'=>'han',
            'version'=>'0.1',
            'has_adminlist'=>1,
            'type'=>0         
        );

        public $admin_list = array(
            'model'=>'Example',		//要查的表
			'fields'=>'*',			//要查的字段
			'map'=>'',				//查询条件, 如果需要可以再插件类的构造方法里动态重置这个属性
			'order'=>'id desc',		//排序,
			'listKey'=>array( 		//这里定义的是除了id序号外的表格里字段显示的表头名
				'字段名'=>'表头显示名'
			),
        );

	public function install() {
		$install_sql = './Addons/Xiuxiu/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/Xiuxiu/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}


    }