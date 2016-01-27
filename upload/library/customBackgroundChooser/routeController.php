<?php
class customBackgroundChooser_routeController extends XenForo_ControllerPublic_Abstract
{	
	public function actionIndex(){
		$visitor = XenForo_Visitor::getInstance();
		if(!$visitor['user_id']){
			throw $this->getNoPermissionResponseException();
		};
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_matchedRoutePath'],'backgroundchange/default')){
			customBackgroundChooser_sharedStatic::userDefaultAllDB($visitor['user_id']);
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		$uid=$visitor['user_id'];
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_matchedRoutePath'],'backgroundchange/setall/')){
			$opt=substr($this->_input->getInput()['_matchedRoutePath'],strlen('backgroundchange/setall/'));
			customBackgroundChooser_sharedStatic::userDefaultAllDB($visitor['user_id']);
			$nodemodel=XenForo_Model::create('XenForo_Model_Node');
			$nodes=$nodemodel->getAllNodes();
			if(substr($opt,0,3)=='sug' || substr($opt,0,3)=='def' || substr($opt,0,3)=='url' || substr($opt,0,3)=='clr'){
				foreach($nodes as $node){
					$nid=$node['node_id'];
					customBackgroundChooser_sharedStatic::putInDB($nid,$uid,$opt);
				}
				customBackgroundChooser_sharedStatic::putInDB(0,$uid,$opt);
			}
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		$html='';
		$html.='<div class="subHeading">All forums at once</div>';
		$html.='<div class="secondaryContent">';
		$html.='<div class="primaryContent chooserColumns twoColumns overlayScroll">';
		$html.="\n";
		for($i = 1 ; $i <= 30 ; $i++ ){
			$j=str_pad($i, 2, '0', STR_PAD_LEFT);
			$html.='<div class="primaryContent chooserColumns twoColumns overlayScroll">';
			$html.='<table><tr>';
			$html.='<td>';
			$html.='<a href="index.php?backgroundchange/setall/sug'.$j;
			$html.='"><img src="library/customBackgroundChooser/defaultImages/'.$j.'.png" width="150" height="100" /></a>';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.=customBackgroundChooser_sharedStatic::$imginfo[$j]['nm'].'<br />';
			$html.=customBackgroundChooser_sharedStatic::$imginfo[$j]['by'].'<br />';
			$html.=customBackgroundChooser_sharedStatic::$imginfo[$j]['lc'];
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
		}
		$html.='</div>';
		$html.='</div>';
		$viewParams=array('html'=>$html);
		return $this->responseView(
            'XenForo_ViewPublic_Base',
            'kiror_background_change_main_page',
            $viewParams
        );
	}
}
