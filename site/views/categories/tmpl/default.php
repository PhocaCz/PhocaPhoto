<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
echo '<div id="ph-pp-categories-box" class="pp-categories-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';
if ( $this->t['p']->get( 'show_page_heading' ) ) { 
	echo '<div class="page-header"><h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1></div>';
}
if ( $this->t['description'] != '') {
	echo '<div class="ph-desc">'. $this->t['description']. '</div>';
}
if (!empty($this->t['categories'])) {
	echo '<div class="ph-categories">';
	$i = 0;
	$c = count($this->t['categories']);
	$nc= (int)$this->t['columns_cats'];
	$nw= 12/$nc;//1,2,3,4,6,12
	echo '<div class="row">';
	foreach ($this->t['categories'] as $v) {
		
		//if ($i%$nc==0) { echo '<div class="row">';}
		
		echo '<div class="col-sm-6 col-md-'.$nw.'">';
		echo '<div class="thumbnail ph-thumbnail">';
		
		$image 	= PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'medium');
		$link	= JRoute::_(PhocaPhotoRoute::getCategoryRoute($v->id, $v->alias));
		
		if (isset($image->rel) && $image->rel != '') {
			echo '<a href="'.$link.'">';
			echo '<img class="img-responsive ph-image" src="'.JURI::base(true).'/'.$image->rel.'" alt=""';
			if (isset($this->t['image_width_cats']) && $this->t['image_width_cats'] != '' && isset($this->t['image_height_cats']) && $this->t['image_height_cats'] != '') {
				echo ' style="width:'.$this->t['image_width_cats'].';height:'.$this->t['image_height_cats'].'"';
			}
			echo ' />';
			echo '</a>';
		}
		echo '<div class="caption">';
		echo '<h3>'.$v->title.'</h3>';
		
		if (!empty($v->subcategories) && (int)$this->t['display_subcat_cats_view'] > 0) {
			echo '<ul>';
			$j = 0;
			foreach($v->subcategories as $v2) {
				if ($j == (int)$this->t['display_subcat_cats_view']) {
					break;
				}
				$link2	= JRoute::_(PhocaPhotoRoute::getCategoryRoute($v2->id, $v2->alias));
				echo '<li><a href="'.$link2.'">'.$v2->title.'</a></li>';
				$j++;
			}
			echo '</ul>';
		}
		
		// Description box will be displayed even no description is set - to set height and have all columns same height
		echo '<div class="ph-cat-desc">';
		if ($v->description != '') {
			echo $v->description;
		}
		echo '</div>';
		
		echo '<p class="pull-right"><a href="'.JRoute::_(PhocaPhotoRoute::getCategoryRoute($v->id, $v->alias)).'" class="btn btn-primary" role="button">'.JText::_('COM_PHOCAPHOTO_VIEW_CATEGORY').'</a></p>';
		echo '<div class="clearfix"></div>';
		echo '</div>';
		echo '</div>';
		echo '</div>'. "\n";
		
		$i++;
		// if ($i%$nc==0 || $c==$i) { echo '</div>';}
	}
	echo '</div></div>'. "\n";
}
echo '</div>';
echo '<div>&nbsp;</div>';
echo PhocaPhotoHelper::getFooter();
?>