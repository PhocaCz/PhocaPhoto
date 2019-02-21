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


    echo '<div id="pg-msnr-container" class="pg-photoswipe pg-msnr-container" itemscope itemtype="http://schema.org/ImageGallery">';
	echo '<div class="ph-items">';
	$i = 0;
	$c = count($this->items);
	$nc= (int)$this->t['columns_cat'];
	$nw= 12/$nc;//1,2,3,4,6,12
	echo '<div class="row">';

	foreach ($this->items as $v) {


		$w = $this->t['large_image_width'];
		$h = $this->t['large_image_height'];

		if (isset($v->extw) && $v->extw != '') {
			$extWA = explode(',', $v->extw);
			if (isset($extWA[0])) { $w = $extWA[0];}
		}

		if (isset($v->exth) && $v->exth != '') {
			$extHA = explode(',', $v->exth);
			if (isset($extHA[0])) { $h = $extHA[0];}
		}

		//if ($i%3==0) { echo '<div class="row">';}

		echo '<div class="col-sm-6 col-md-'.$nw.'">';
		echo '<div class="thumbnail ph-thumbnail">';

		$link = JRoute::_(PhocaPhotoRoute::getImageRoute($v->id, $v->catid, $v->alias, $v->categoryalias));
		$imagePath = '';
		$imageLink = '';
		if (isset($v->filename) && ($v->filename != '' && $v->filename != '-')) {
			$image 	= PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'medium');
			if (isset($image->rel) && $image->rel != '') {
				$imagePath = JURI::base(true).'/'.$image->rel;
			}
			$imageL = PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'large');
			if (isset($imageL->rel) && $imageL->rel != '') {
				$imageLink = JURI::base(true).'/'.$imageL->rel;
			}

		} else if (isset($v->extm) && $v->extm != '' && isset($v->extl) && $v->extl != '') {
			$imagePath = $v->extm;
			$imageLink = $v->extl;
		}

		if ($this->t['image_link'] == 2) {
			echo '<a href="'.$imageLink.'" rel="prettyPhoto[pp_gal1]">';
		} if ($this->t['image_link'] == 1) {

            echo '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">';
            echo '<a class="photoswipe-button" href="'.$imageLink.'" itemprop="contentUrl" data-size="'.$w.'x'.$h.'" >';
        } else {

			echo '<a href="'.$link.'">';
		}

		if (isset($imagePath) && $imagePath != '') {
			echo '<img src="'.$imagePath.'" alt="" class="img-responsive ph-image"';
			if (isset($this->t['image_width']) && $this->t['image_width'] != '' && isset($this->t['image_height']) && $this->t['image_height'] != '') {
				echo ' style="width:'.$this->t['image_width'].';height:'.$this->t['image_height'].'"';
			}
			echo ' />';
		}

		echo '</a>';

        if ($this->t['image_link'] == 1) {
            echo '<figcaption itemprop="caption description">'. $v->title.'</figcaption>';
            echo '</figure>';
        }

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

		// E.g. Photoswipe cannot run two instances at once, so it is better to hide the button for photo swipe
		if ($this->t['display_view_photo_button'] == 1) {
			echo '<p class="pull-right">';

			if ($imageLink != '' && $this->t['image_link'] == 2) {
				echo '<a href="' . $imageLink . '" rel="prettyPhoto[pp_gal2]" class="btn btn-primary" role="button">' . JText::_('COM_PHOCAPHOTO_VIEW_PHOTO') . '</a>';
			} else if ($imageLink != '' && $this->t['image_link'] == 1) {

				/*echo '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">';
				echo '<a href="'.$imageLink.'" itemprop="contentUrl" class="btn btn-primary photoswipe-button" role="button" data-size="'.$w.'x'.$h.'" >'.JText::_('COM_PHOCAPHOTO_VIEW_PHOTO').'</a>';
				echo '</figure>';*/
				// Photoswipe cannot have too instances, so the second link goes to detail view
				echo '<a href="' . $link . '" class="btn btn-primary" role="button">' . JText::_('COM_PHOCAPHOTO_VIEW_PHOTO') . '</a>';
			} else {
				echo '<a href="' . $link . '" class="btn btn-primary" role="button">' . JText::_('COM_PHOCAPHOTO_VIEW_PHOTO') . '</a>';
			}
			echo '</p>';
		}

		echo '<div class="clearfix"></div>';
		echo '</div>';

		echo '</div>';
		echo '</div>'. "\n";

		//$i++; if ($i%3==0 || $c==$i) { echo '</div>';}

	}
	echo '</div></div></div>'. "\n";


	echo $this->loadTemplate('pagination');

    if ($this->t['image_link'] == 1) {
        echo PhocaGalleryRenderDetailWindow::loadPhotoswipeBottom(1,1);
    }
}
echo '</div>';
echo '<div>&nbsp;</div>';
echo PhocaPhotoHelper::getFooter();
?>
