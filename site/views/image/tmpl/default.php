<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
echo '<div id="ph-pp-item-box" class="pp-item-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';
if ( $this->t['p']->get( 'show_page_heading' ) ) { 
	echo '<div class="page-header"><h1><h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1></div>';
}

if (isset($this->category[0]->id) && ($this->t['display_back'] == 2 || $this->t['display_back'] == 3)) {
	if ($this->category[0]->id > 0) {
		$linkUp = JRoute::_(PhocaPhotoRoute::getCategoryRoute($this->category[0]->id, $this->category[0]->alias));
		$linkUpText = $this->category[0]->title;
	} else {
		$linkUp 	= false;
		$linkUpText = false; 
	}
	
	if ($linkUp && $linkUpText) {
		echo '<div class="ph-top">'
		.'<a class="btn btn-success" title="'.$linkUpText.'" href="'. $linkUp.'" ><span class="glyphicon glyphicon-arrow-left"></span> '.JText::_($linkUpText).'</a></div>';
	}
}

if ( isset($this->item[0]->description) && $this->item[0]->description != '') {
	echo '<div class="ph-desc">'. $this->item[0]->description. '</div>';
}

$v = $this->item[0];
if (!empty($v)) {
	
	echo '<div class="ph-image-full-box">';
	$image = PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'large');
	if (isset($image->rel) && $image->rel != '') {
		echo '<img src="'.JURI::base(true).'/'.$image->rel.'" alt="" class="img-responsive img-thumbnail ph-image-full"';
		if (isset($this->t['image_width']) && (int)$this->t['image_width'] > 0 && isset($this->t['image_height']) && (int)$this->t['image_height'] > 0) {
			echo ' style="width:'.$this->t['image_width'].'px;height:'.$this->t['image_height'].'px"';
		}
		echo ' />';
	}
	echo '</div>'. "\n";
	
	$socO1 = '';
	$socO2 = '';
	
	if ($this->t['enable_social'] == 1) {
		
		$uri = JUri::getInstance();
		$uri = PhocaPhotoUtils::encodeURIComponent($uri);
		$socO1 = '<div class="pp_social ph-social"><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div></div>';
		
		$socO2 = '<div class="pp_social ph-social"><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='.$uri.'&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:23px;" allowTransparency="true"></iframe></div></div>';
	
	}
	
	if ($this->itemnext[0] || $this->itemprev[0]) {
		echo '<div class="row"><div class="col-sm-4 col-md-4 ph-image-navigation-box">';
		if($this->itemprev[0]) {
			$p = $this->itemprev[0];
			$linkPrev = JRoute::_(PhocaPhotoRoute::getImageRoute($p->id, $p->catid, $p->alias, $p->categoryalias));
			echo '<div class="pull-left"><a href="'.$linkPrev.'" class="btn btn-default ph-image-navigation" role="button"><span class="glyphicon glyphicon-arrow-left"></span> '.JText::_('COM_PHOCAPHOTO_PREVIOUS').'</a></div>';
		}
		echo '</div>';
		
		echo '<div class="col-sm-2 col-md-2 ph-image-navigation-box">';
		echo $socO1;
		echo '</div>';
		
		echo '<div class="col-sm-2 col-md-2 ph-image-navigation-box">';
		echo $socO2;
		echo '</div>';
		
		echo '<div class="col-sm-4 col-md-4 ph-image-navigation-box">';
		if($this->itemnext[0]) {
			$n = $this->itemnext[0];
			$linkNext = JRoute::_(PhocaPhotoRoute::getImageRoute($n->id, $n->catid, $n->alias, $n->categoryalias));
			echo '<div class="pull-right"><a href="'.$linkNext.'" class="btn btn-default ph-image-navigation" role="button">'.JText::_('COM_PHOCAPHOTO_NEXT').' <span class="glyphicon glyphicon-arrow-right"></span></a></div>';
		}
		echo '</div></div>';
	} else {
		echo '<div class="col-sm-4 col-md-4 ph-image-navigation-box"></div>';
		echo '<div class="col-sm-2 col-md-2 ph-image-navigation-box">';
		echo $socO1;
		echo '</div>';
		
		echo '<div class="col-sm-2 col-md-2 ph-image-navigation-box">';
		echo $socO2;
		echo '</div>';
		echo '<div class="col-sm-4 col-md-4 ph-image-navigation-box"></div>';
	}
	
	
	

}
echo '</div>';
echo '<div>&nbsp;</div>';
echo PhocaPhotoHelper::getFooter();
?>