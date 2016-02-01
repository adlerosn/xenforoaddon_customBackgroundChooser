<?php
class customBackgroundChooser_routeController extends XenForo_ControllerPublic_Abstract
{	
	public function actionIndex(){
		$visitor = XenForo_Visitor::getInstance();
		if(!$visitor['user_id']){
			throw $this->getNoPermissionResponseException();
		};
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_origRoutePath'],'backgroundchange/default')){
			customBackgroundChooser_sharedStatic::userDefaultAllDB($visitor['user_id']);
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		$uid=$visitor['user_id'];
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_origRoutePath'],'backgroundchange/setall/')){
			$opt=substr($this->_input->getInput()['_origRoutePath'],strlen('backgroundchange/setall/'));
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
		if(customBackgroundChooser_sharedStatic::startsWith($this->_input->getInput()['_origRoutePath'],'backgroundchange/set.')){
			$big=substr($this->_input->getInput()['_origRoutePath'],strlen('backgroundchange/set.'));
			$chc=substr($big,1+strpos($big,'/'));
			if($chc==null){$chc='';};
			$nid=intval(substr($big,0,strpos($big,'/')));
			//die(print_r(array($big,$nid,$chc),true));
			if(substr($chc,0,3)=='sug' || substr($chc,0,3)=='def' || substr($chc,0,3)=='url' || substr($chc,0,3)=='clr'){
				customBackgroundChooser_sharedStatic::putInDB($nid,$uid,$chc);
			}
			return $this->responseView(
				'XenForo_ViewPublic_Base',
				'kiror_background_change_main_page',
				array('html'=>'Resetted!<script>window.history.back();</script>')
			);
		};
		$html='';
		$html.='<script>
function globalSetColor(){
	a = document.getElementById("bkgPicker").value;
	window.location="index.php?/backgroundchange/setall/clr"+a;
}
function globalSetUrlImage(){
	a = document.getElementById("urlPlacer").value;
	window.location="index.php?/backgroundchange/setall/url"+a;
}
function regionalSetColor(eid,nid){
	a = document.getElementById(eid,nid).value;
	window.location="index.php?/backgroundchange/set."+nid+"/clr"+a;
}
function regionalSetUrlImage(eid,nid){
	a = document.getElementById(eid).value;
	window.location="index.php?/backgroundchange/set."+nid+"/url"+a;
}

$(document).ready(function() {
$(".expanded").hide();

$(".expanded, .collapsed").click(function() {
	$(this).parent().children(".expanded, .collapsed").toggle();
});
});
</script>';
		$html.='<ul>';
		$html.='<li>
        <div class="collapsed"><div class="subHeading">&#x25b8; All forums at once</div></div>
        <div class="expanded">';
		$html.='<div class="subHeading">&#x25be; All forums at once</div>';
		$html.='<div class="secondaryContent">';
		$html.='<div class="primaryContent flexcontainer">';
		$html.="\n";
		//
		$html.='<div class="flexitem" style="order: 0;">';
		$html.='<table><tr>';
		$html.='<td>';
		$html.='<a href="index.php?backgroundchange/setall/def';
		$html.='"><img src="library/customBackgroundChooser/genericImgs/defaultBkg.png" width="150" height="100" alt="" /></a>';
		$html.='</td>';
		$html.='<td>';
		$html.='<div style="margin-left: 10px;">';
		$html.='Default background<br />';
		$html.='</div>';
		$html.='</td>';
		$html.='</tr></table>';
		$html.='</div>'."\n";
		//
		$html.='<div class="flexitem" style="order: 1;">';
		$html.='<table><tr>';
		$html.='<td style="width: 1px; height: 100px;">';
		$html.='</td>';
		$html.='<td>';
		$html.='<div style="margin-left: 10px;">';
		$html.='Custom color: ';
		$html.='<input
					style="display: none;"
					name="bkgPicker"
					value="rgb(240,240,240)"
					class="textCtrl ColorPicker DisablePalette"
					id="bkgPicker"
					type="text">
				<button class="button" onclick="globalSetColor();">Set</button>';
		$html.='<div class="pickers colorPalette">';
		$html.='</div>';
		$html.='</div>';
		$html.='</td>';
		$html.='</tr></table>';
		$html.='</div>'."\n";
		//
		$html.='<div class="flexitem" style="order: 2;">';
		$html.='<table><tr>';
		$html.='<td style="width: 1px; height: 100px;">';
		$html.='</td>';
		$html.='<td>';
		$html.='<div style="margin-left: 10px;">';
		$html.='Custom image link:<br />';
			$html.='<table><tr>';
			$html.='<td>';
		$html.='<input
					placeholder="Paste image URL"
					class="textCtrl"
					name="urlPlacer"
					value=""
					id="urlPlacer"
					type="text">';
			$html.='</td>';
			$html.='<td>';
		$html.='<button style="display:table-cell;" class="button" onclick="globalSetUrlImage()">Set</button>';
			$html.='</td>';
			$html.='</tr></table>';
		$html.='</div>';
		$html.='</td>';
		$html.='</tr></table>';
		$html.='</div>'."\n";
		//
		for($i = 1 ; $i <= 30 ; $i++ ){
			$j=str_pad($i, 2, '0', STR_PAD_LEFT);
			$html.='<div class="flexitem" style="order: '.($i+99).';">';
			$html.='<table><tr>';
			$html.='<td>';
			$html.='<a href="index.php?backgroundchange/setall/sug'.$j;
			$html.='"><img src="library/customBackgroundChooser/defaultImages/'.$j.'.png" width="150" height="100" alt="" /></a>';
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
		$html.='</div>';
		$html.='</li>'."\n";
		///
		$nodehandler=XenForo_Model::create('XenForo_NodeHandler_Forum');
		$nodemodel=XenForo_Model::create('XenForo_Model_Node');
		$nodesk=$nodemodel->getAllNodes(false,true);
		$nodes=array();
		$nodes[0]=$nodemodel->getRootNode();
		$nodes[0]['title']='<i>default</i>';
		foreach($nodesk as $k=>$node){
			if (array_key_exists('node_type_id',$node)){
				$nodes[$k]=$node;
			}
		};
		foreach($nodes as $node){
			/*
			 * $node
			 *  [node_id]
			 *  [title]
			 *  [description]
			 *  [node_name]
			 *  [node_type_id]
			 *  [parent_node_id]
			 *  [display_order]
			 *  [display_in_list]
			 *  [lft]
			 *  [rgt]
			 *  [depth]
			 *  [style_id]
			 *  [effective_style_id]
			 *  [breadcrumb_data]
			 */
			if ($node['node_id']!=0){
				if ($node['node_type_id'] != 'Forum'){
					continue;
				};
				$userNodePermissions=$visitor->getNodePermissions($node['node_id']);
				if (! $nodehandler->isNodeViewable($node,$userNodePermissions)){
					continue;
				};
			};
			$html.='<li>
			<div class="collapsed"><div class="subHeading">&#x25b8; Forum node "'.$node['title'].'"</div></div>
			<div class="expanded">';
			$html.='<div class="subHeading">&#x25be; Forum node "'.$node['title'].'"</div>';
			//**//
			$html.='<div class="secondaryContent">';
			$html.='<div class="primaryContent flexcontainer">';
			$html.="\n";
			//
			$html.='<div class="flexitem" style="order: 0;">';
			$html.='<table><tr>';
			$html.='<td>';
			$html.='<a href="index.php?backgroundchange/set.'.$node['node_id'].'/def';
			$html.='"><img src="library/customBackgroundChooser/genericImgs/defaultBkg.png" width="150" height="100" alt="" /></a>';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.='Default background<br />';
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
			//
			$html.='<div class="flexitem" style="order: 1;">';
			$html.='<table><tr>';
			$html.='<td style="width: 1px; height: 100px;">';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.='Custom color: ';
			$html.='<input
						style="display: none;"
						name="bkgPicker'.$node['node_id'].'"
						value="rgb(240,240,240)"
						class="textCtrl ColorPicker DisablePalette"
						id="bkgPicker'.$node['node_id'].'"
						type="text">
					<button class="button" onclick="regionalSetColor(\'bkgPicker'.$node['node_id'].'\','.$node['node_id'].');">
							Set
					</button>';
			$html.='<div class="pickers colorPalette">';
			$html.='</div>';
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
			//
			$html.='<div class="flexitem" style="order: 2;">';
			$html.='<table><tr>';
			$html.='<td style="width: 1px; height: 100px;">';
			$html.='</td>';
			$html.='<td>';
			$html.='<div style="margin-left: 10px;">';
			$html.='Custom image link:<br />';
				$html.='<table><tr>';
				$html.='<td>';
			$html.='<input
						placeholder="Paste image URL"
						class="urlPlacer'.$node['node_id'].'"
						name="urlPlacer"
						value=""
						id="urlPlacer'.$node['node_id'].'"
						type="text">';
				$html.='</td>';
				$html.='<td>';
			$html.='<button style="display:table-cell;" class="button" onclick="regionalSetUrlImage(\'urlPlacer'.$node['node_id'].'\','.$node['node_id'].')">
							Set
					</button>';
				$html.='</td>';
				$html.='</tr></table>';
			$html.='</div>';
			$html.='</td>';
			$html.='</tr></table>';
			$html.='</div>'."\n";
			//
			for($i = 1 ; $i <= 30 ; $i++ ){
				$j=str_pad($i, 2, '0', STR_PAD_LEFT);
				$html.='<div class="flexitem" style="order: '.($i+99).';">';
				$html.='<table><tr>';
				$html.='<td>';
				$html.='<a href="index.php?backgroundchange/set.'.$node['node_id'].'/sug'.$j;
				$html.='"><img src="library/customBackgroundChooser/defaultImages/'.$j.'.png" width="150" height="100" alt="" /></a>';
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
			//**//
			$html.='</div>';
		}
		$html.='</li>'."\n";
		///
		$html.='</ul>'."\n";
		$viewParams=array('html'=>$html);
		return $this->responseView(
            'XenForo_ViewPublic_Base',
            'kiror_background_change_main_page',
            $viewParams
        );
	}
}
