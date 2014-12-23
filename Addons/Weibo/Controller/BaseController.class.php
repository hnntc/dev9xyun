<?php

namespace Addons\Weibo\Controller;

use Home\Controller\AddonsController;

class BaseController extends AddonsController {
	function _initialize() {
		parent::_initialize();
		
		$controller = strtolower ( _CONTROLLER );
		
		$res ['title'] = '授权列表';
		$res ['url'] = addons_url ( 'Weibo://Weibo/lists' );
		$res ['class'] = $controller == 'card' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '授权页面';
		$res ['url'] = addons_url ( 'Weibo://Grant/lists' );
		$res ['class'] = $controller == 'grant' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '微博编辑器';
		$res ['url'] = addons_url ( 'Card://notice/lists' );
		$res ['class'] = $controller == 'notice' ? 'current' : '';
		$nav [] = $res;
		
		$res ['title'] = '历史微博';
		$res ['url'] = addons_url ( 'Coupon://Coupon/lists' );
		$res ['class'] = $controller == 'coupon' ? 'current' : '';
		$nav [] = $res;
		
		$this->assign ( 'nav', $nav );
		
		
	}
}
