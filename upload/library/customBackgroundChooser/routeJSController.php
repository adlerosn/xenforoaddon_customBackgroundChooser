<?php
class customBackgroundChooser_routeJSController extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex(){
		$visitor = XenForo_Visitor::getInstance();
		if(!$visitor['user_id']){die('');};
		$uid=$visitor['user_id'];
		$nid=($this->_input->filterSingle('nid',XenForo_Input::INT));
		$nodemodel=XenForo_Model::create('XenForo_Model_Node');
		$node=array();
		foreach($nodemodel->getAllNodes() as $v){
			if($v['node_id']==$nid){
				$node=$v;
				break;
			}
		}
		if(count($node)==0){$nid=0;};
		$r=customBackgroundChooser_sharedStatic::getFromDB($nid,$uid);
		if(customBackgroundChooser_sharedStatic::startsWith($r,'url')){
			die('');
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'sug')){
			$u=substr($r,3);
			$i='rgb(0, 0, 0) url(\'library/customBackgroundChooser/defaultImages/'.$u.'.jpg\') no-repeat 50% 0 fixed';
			die($i);
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'clr')){
			die('');
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'def')){
			die('');
		}
		else {
			die('');
		};
	}
}
