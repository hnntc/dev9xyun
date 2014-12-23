<?php

namespace Addons\WeiboEditor;
use Common\Controller\Addon;

/**
 * 微博编辑器插件
 * @author han
 */

    class WeiboEditorAddon extends Addon{

        public $info = array(
            'name'=>'WeiboEditor',
            'title'=>'微博编辑器',
            'description'=>'微博编辑器',
            'status'=>1,
            'author'=>'han',
            'version'=>'0.1'
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

        public function install(){
            return true;
        }

        public function uninstall(){
            return true;
        }

        //实现的weiboeditor钩子方法
        public function weiboEditor($param){
			$EditCode=$param['EditCode'];
			$this->assign('EditCode',$EditCode);
			$this->display('weiboEditor');
        }

    }