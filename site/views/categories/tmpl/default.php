<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
echo '<div id="ph-ph-categories-box" class="ph-categories-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';
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

		$imagePath = '';
		// First try to user image set by category
		if (isset($v->image_id) && (int)$v->image_id > 0) {
			$sI = PhocaPhotoHelper::setFileNameByImageId((int)$v->image_id);

			if (isset($sI->filename) && ($sI->filename != '' && $sI->filename != '-')) {
				$v->filename = $sI->filename;

			} else if (isset($sI->extm) && $sI->extm != '') {
				$imagePath = $sI->extm;

			}
		}

		// If not found, try to find internal image, if not found try to find external image
		if ($imagePath == '') {
			if (isset($v->filename) && ($v->filename != '' && $v->filename != '-')) {
				$image 	= PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'medium');
				if (isset($image->rel) && $image->rel != '') {
					$imagePath = Uri::base(true).'/'.$image->rel;
				}
			} else if (isset($v->extm) && $v->extm != '') {
				$imagePath = $v->extm;
			}
		}

		$link	= Route::_(PhocaPhotoRoute::getCategoryRoute($v->id, $v->alias));

		echo '<div class="col-sm-6 col-md-'.$nw.'">';
		echo '<div class="card ph-card">';

		if (isset($imagePath) && $imagePath != '') {
			echo '<a class="ph-image-box" href="'.$link.'">';
			echo '<img class="card-img-top img-responsive ph-image" src="'.$imagePath.'" alt="'.htmlspecialchars($v->title).'"';
			if (isset($this->t['image_width_cats']) && $this->t['image_width_cats'] != '' && isset($this->t['image_height_cats']) && $this->t['image_height_cats'] != '') {
				echo ' style="width:'.$this->t['image_width_cats'].';height:'.$this->t['image_height_cats'].'"';
			}
			echo ' />';
			echo '</a>';
		}

		echo '<div class="card-body">';

		echo '<h5 class="card-title">'.$v->title.'</h5>';

		echo '<div class="card-text">';
		if (!empty($v->subcategories) && (int)$this->t['display_subcat_cats_view'] > 0) {
			echo '<ul>';
			$j = 0;
			foreach($v->subcategories as $v2) {
				if ($j == (int)$this->t['display_subcat_cats_view']) {
					break;
				}
				$link2	= Route::_(PhocaPhotoRoute::getCategoryRoute($v2->id, $v2->alias));
				echo '<li><a href="'.$link2.'">'.$v2->title.'</a></li>';
				$j++;
			}
			echo '</ul>';
		}

		// Description box will be displayed even no description is set - to set height and have all columns same height
		if ($v->description != '') {
			echo $v->description;
		}
		echo '</div>'; // end card text

		echo '<p class="pull-right"><a href="'.Route::_(PhocaPhotoRoute::getCategoryRoute($v->id, $v->alias)).'" class="btn btn-primary" role="button">'.Text::_('COM_PHOCAPHOTO_VIEW_CATEGORY').'</a></p>';
		//echo '<div class="clearfix"></div>';

		echo '</div>'; // end card body

		echo '</div>'. "\n"; // end card
		echo '</div>'; // end cols

		$i++;
		// if ($i%$nc==0 || $c==$i) { echo '</div>';}
	}

	echo '</div>'; // end row
	echo '</div>'. "\n"; // end categories
}
echo '</div>';
echo '<div>&nbsp;</div>';
echo PhocaPhotoHelper::getFooter();
?>
