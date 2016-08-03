<?php
class customBackgroundChooser_routeJSController extends XenForo_ControllerPublic_Abstract
{
	public static function explodeAndTrim($haystack, $needle, $allowEmpty=false){
		$exploded = explode($needle, $haystack);
		$explodedAndTrimmed = array();
		foreach($exploded as $fragment){
			$ready = trim($fragment);
			if(strlen($ready)<=0){continue;};
			$explodedAndTrimmed[] = trim($fragment);
		}
		return $explodedAndTrimmed;
	}
	public static function debug($var){die(print_r($var,true));}
	public function actionIndex(){
		$final = array('set'=>false);
		$visitor = XenForo_Visitor::getInstance();
		if(!$visitor['user_id']){die(json_encode($final));};
		$styleId = $visitor['style_id'];
		if(!$visitor->hasPermission('backgroundchanginggroup', 'canchangebkg')){
			die(json_encode($final));
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
		$allSpecificRules = customBackgroundChooser_adminArrayPlaces::getBackgroundReplaceRules();
		//self::debug($styleId);
		if(!$styleId || !array_key_exists($styleId, $allSpecificRules)){
			$styleId=customBackgroundChooser_adminArrayPlaces::getDefaultStyleId();
		}
		//self::debug($styleId);
		//self::debug($allSpecificRules);
		$final = array_merge($allSpecificRules[$styleId],$final);
		//self::debug($final);
		if(count($node)==0){$nid=0;};
		$r=customBackgroundChooser_sharedStatic::getFromDB($nid,$uid);
		if(customBackgroundChooser_sharedStatic::startsWith($r,'url')){
			$u=substr($r,3);
			$i='rgb(0, 0, 0) url(\''.$u.'\') no-repeat fixed center / cover';
			$final['set']=true;
			$final['background']=($i);
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'sug')){
			$u=substr($r,3);
			$i='rgb(0, 0, 0) url(\'styles/kiror/customBackgroundChooser/defaultImages/'.$u.'.jpg\') no-repeat center / cover';
			$final['set']=true;
			$final['background']=($i);
		}
		else if (customBackgroundChooser_sharedStatic::startsWith($r,'clr')){
			$u=substr($r,3);
			$final['set']=true;
			$final['background']=($u);
		}
		$final['parallaxWithoutGlass'] = self::explodeAndTrim($final['parallaxHeaderClasses'],',');
		$final['parallaxWithGlass'] = self::explodeAndTrim($final['parallaxHolesClasses'],',');
		$final['box-shadow']  = 'inset '.$final['parallaxHolesTintColor'].' 0px 1px 0px, inset '.$final['parallaxHolesTintColor'].' 0px 0px 0px 1px, inset '.$final['parallaxHolesTintColor'].' 0px 100px 0px';
		$final['text-shadow'] = $final['parallaxHolesTintColor'].' 0px 0px 3px, '.$final['parallaxHolesTintColor'].' 0px 1px 0px';
		die(json_encode($final));
	}
}
