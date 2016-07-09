<?php
class customBackgroundChooser_routeJSController extends XenForo_ControllerPublic_Abstract
{
	public function actionIndex(){
		$visitor = XenForo_Visitor::getInstance();
		if(!$visitor['user_id']){die('');};
		if(!$visitor->hasPermission('backgroundchanginggroup', 'canchangebkg')){
			die('');
		};
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
			$u=substr($r,3);
			$i='rgb(0, 0, 0) url(\''.$u.'\') no-repeat fixed 50% 0 / cover';
			die($i);
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'sug')){
			$u=substr($r,3);
			$i='rgb(0, 0, 0) url(\'styles/kiror/customBackgroundChooser/defaultImages/'.$u.'.jpg\') no-repeat fixed 50% 0 / cover';
			die($i);
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'clr')){
			$u=substr($r,3);
			die($u);
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'def')){
			die('');
		}
		else {
			die('');
		};
	}
}
