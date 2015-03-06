<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
echo '<div id="ph-pp-category-box" class="pp-category-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';


// Heading
$heading = '';
if ($this->t['p']->get( 'page_heading' ) != '') {
	$heading .= $this->t['p']->get( 'page_heading' );
}

// Category Name Title
if ( $this->t['display_cat_name_title']) {
	if (isset($this->category[0]->title) && $this->category[0]->title != '') {
		if ($heading != '') {
			$heading .= ' - ';
		}
		$heading .= $this->category[0]->title;
	}
}
// Pagetitle
if ($this->t['p']->get( 'show_page_heading' ) != '') {
	if ( $heading != '') {
		echo '<div class="page-header"><h1>'. $this->escape($heading) . '</h1></div>';
	} 
}


if (isset($this->category[0]->parentid) && ($this->t['display_back'] == 1 || $this->t['display_back'] == 3)) {
	if ($this->category[0]->parentid == 0) {
		$linkUp = JRoute::_(PhocaPhotoRoute::getCategoriesRoute());
		$linkUpText = JText::_('COM_PHOCAPHOTO_CATEGORIES');
	} else if ($this->category[0]->parentid > 0) {
		$linkUp = JRoute::_(PhocaPhotoRoute::getCategoryRoute($this->category[0]->parentid, $this->category[0]->parentalias));
		$linkUpText = $this->category[0]->parenttitle;
	} else {
		$linkUp 	= false;
		$linkUpText = false; 
	}
	
	if ($linkUp && $linkUpText) {
		echo '<div class="ph-top">'
		.'<a class="btn btn-success" title="'.$linkUpText.'" href="'. $linkUp.'" ><span class="glyphicon glyphicon-arrow-left"></span> '.JText::_($linkUpText).'</a></div>';
	}
}

if ( isset($this->category[0]->description) && $this->category[0]->description != '') {
	echo '<div class="ph-desc">'. $this->category[0]->description. '</div>';
}


if (!empty($this->subcategories) && (int)$this->t['display_subcat_cat_view'] > 0) {
	echo '<div class="ph-subcategories">'.JText::_('COM_PHOCAPHOTO_SUBCATEGORIES') . ':</div>';
	echo '<ul>';
	$j = 0;
	foreach($this->subcategories as $v) {
		if ($j == (int)$this->t['display_subcat_cat_view']) {
			break;
		}
		echo '<li><a href="'.JRoute::_(PhocaPhotoRoute::getCategoryRoute($v->id, $v->alias)).'">'.$v->title.'</a></li>';
		$j++;
	}
	echo '</ul>';
	echo '<hr />';
}



if (!empty($this->items)) {
	echo '<div class="ph-items">';
	$i = 0;
	$c = count($this->items);
	$nc= (int)$this->t['columns_cat'];
	$nw= 12/$nc;//1,2,3,4,6,12
	echo '<div class="row">';
	
	foreach ($this->items as $v) {
		
		//if ($i%3==0) { echo '<div class="row">';}
		
		echo '<div class="col-sm-6 col-md-'.$nw.'">';
		echo '<div class="thumbnail ph-thumbnail">';
		
		$image = PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'medium');
		
		if ($this->t['image_link'] == 1) {
			$imageL = PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'large');
			$link = JURI::base(true).'/'.$imageL->rel;
			echo '<a href="'.$link.'" rel="prettyPhoto[pp_gal1]">';
		} else {
			$link = JRoute::_(PhocaPhotoRoute::getImageRoute($v->id, $v->catid, $v->alias, $v->categoryalias));
			echo '<a href="'.$link.'">';
		}
		
		if (isset($image->rel) && $image->rel != '') {
			echo '<img src="'.JURI::base(true).'/'.$image->rel.'" alt="" class="img-responsive ph-image"';
			if (isset($this->t['image_width']) && $this->t['image_width'] != '' && isset($this->t['image_height']) && $this->t['image_height'] != '') {
				echo ' style="width:'.$this->t['image_width'].';height:'.$this->t['image_height'].'"';
			}
			echo ' />';
		}
		
		echo '</a>';
		
		/*$imageAbs = $this->t['photopathabs'] . htmlspecialchars($v->folder).'/thumb.jpg';
		$imageRel = $this->t['photopathrel'] . htmlspecialchars($v->folder).'/thumb.jpg';
		if (isset($v->image) && $v->image != '') {
			echo '<img src="'. JURI::base(true) . '/' . $v->image.'" alt="" style="width:'.$this->t['image_width'].'px;height:'.$this->t['image_height'].'px" >';
		} else if (JFile::exists($imageAbs)) {
			echo '<img src="'.$imageRel.'" alt="" style="width:'.$this->t['image_width'].'px;height:'.$this->t['image_height'].'px" >';
		}*/
		echo '<div class="caption">';
		echo '<h3>'.$v->title.'</h3>';
		
		// Description box will be displayed even no description is set - to set height and have all columns same height
		echo '<div class="ph-item-desc">';
		if ($v->description != '') {
			echo $v->description;
		}
		echo '</div>';
		
		echo '<p class="pull-right">';
		
		if (isset($imageL->rel) && $imageL->rel != '' && $this->t['image_link'] == 1) {
			echo '<a href="'.$link.'" rel="prettyPhoto[pp_gal2]" class="btn btn-primary" role="button">'.JText::_('COM_PHOCAPHOTO_VIEW_PHOTO').'</a>';
		} else {
			echo '<a href="'.$link.'" class="btn btn-primary" role="button">'.JText::_('COM_PHOCAPHOTO_VIEW_PHOTO').'</a>';
		}
		echo '</p>';
		echo '<div class="clearfix"></div>';
		echo '</div>';
		
		echo '</div>';
		echo '</div>'. "\n";
		
		//$i++; if ($i%3==0 || $c==$i) { echo '</div>';}
		
	}
	echo '</div></div>'. "\n";
	
	
	echo $this->loadTemplate('pagination');
}
echo '</div>';
echo '<div>&nbsp;</div>';
echo PhocaPhotoHelper::getFooter();
?>