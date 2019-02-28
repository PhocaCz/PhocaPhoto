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
			if ($this->t['display_title_category_view'] == 1) {
            	echo '<figcaption itemprop="caption description">'. $v->title.'</figcaption>';
			}
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
		if ($this->t['display_title_category_view'] == 1 && $v->title != '') {
			echo '<h3>' . $v->title . '</h3>';
		}

		// Description box will be displayed even no description is set - to set height and have all columns same height
		echo '<div class="ph-item-desc">';
		if ($this->t['display_desc_category_view'] == 1 && $v->description != '') {
			echo $v->description;
		}
		echo '</div>';


		// External Links
		$class = '';
		if ($this->t['extlink_class'] != '') {
			$class = strip_tags($this->t['extlink_class']);
		}
		if ($this->t['extlink_class_category'] != '') {
			$class .= ' '.strip_tags($this->t['extlink_class_category']);
		}

		$class = 'class="'.$class.'"';

		// ICON EXTERNAL LINK 1
		if ($this->t['display_icon_extlink1'] == 1 || $this->t['display_icon_extlink1'] == 3) {

			$extlink1	= explode("|", $v->extlink1, 4);

			if (isset($extlink1[0]) && $extlink1[0] != '' && isset($extlink1[1])) {
				if (!isset($extlink1[2])) {
					$extlink1[2] = '_self';
				}
				if (!isset($extlink1[3]) || $extlink1[3] == 1) {

					$extlink1[4] = '<span class="glyphicon glyphicon glyphicon-share"></span>';
					$extlink1[5] = '';
				} else {
					$extlink1[4] = $extlink1[1];
					$extlink1[5] = '';
				}

				$pos10 		= strpos($extlink1[0], 'http://');
				$pos20 		= strpos($extlink1[0], 'https://');
				$extLinkUrl1	= 'http://'.$extlink1[0];
				if ($pos10 === 0) {
					$extLinkUrl1 = $extlink1[0];
				} else if ($pos20 === 0) {
					$extLinkUrl1 = $extlink1[0];
				}

				if ($this->t['extlink1_class_icon']	!= '') {
					$extlink1[4] = '<span class="'.$this->t['extlink1_class_icon'].'"></span>';
				}


				echo ' <a '.$class.' title="'.$extlink1[1] .'"'
					.' href="'. $extLinkUrl1 .'" target="'.$extlink1[2] .'" '.$extlink1[5].'>'
					.$extlink1[4].'</a>';
			}

		}

		// ICON EXTERNAL LINK 2
		if ($this->t['display_icon_extlink2'] == 1 || $this->t['display_icon_extlink2'] == 3) {

			$extlink2	= explode("|", $v->extlink2, 4);

			if (isset($extlink2[0]) && $extlink2[0] != '' && isset($extlink2[1])) {
				if (!isset($extlink2[2])) {
					$extlink2[2] = '_self';
				}
				if (!isset($extlink2[3]) || $extlink2[3] == 1) {

					$extlink2[4] = '<span class="glyphicon glyphicon glyphicon-share"></span>';
					$extlink2[5] = '';
				} else {
					$extlink2[4] = $extlink2[1];
					$extlink2[5] = '';
				}

				$pos11 		= strpos($extlink2[0], 'http://');
				$pos21 		= strpos($extlink2[0], 'https://');
				$extLinkUrl2	= 'http://'.$extlink2[0];
				if ($pos11 === 0) {
					$extLinkUrl2 = $extlink2[0];
				} else if ($pos21 === 0) {
					$extLinkUrl2 = $extlink2[0];
				}

				if ($this->t['extlink2_class_icon']	!= '') {
					$extlink2[4] = '<span class="'.$this->t['extlink2_class_icon'].'"></span>';
				}


				echo ' <a '.$class.' title="'.$extlink2[1] .'"'
					.' href="'. $extLinkUrl2 .'" target="'.$extlink2[2] .'" '.$extlink2[5].'>'
					.$extlink2[4].'</a>';
			}
		}



		// E.g. Photoswipe cannot run two instances at once, so it is better to hide the button for photo swipe
		if ($this->t['display_view_photo_button'] == 1) {


			if ($this->t['view_photo_class_icon']	!= '') {
				$viewPhoto = '<span class="'.$this->t['view_photo_class_icon'].'"></span>';
			} else {
				$viewPhoto = JText::_('COM_PHOCAPHOTO_VIEW_PHOTO');
			}

			if ($this->t['view_photo_class'] != '') {
				$class = $this->t['view_photo_class'];
			} else {
				$class = 'btn btn-primary';
			}

			echo '<p class="pull-right">';

			if ($imageLink != '' && $this->t['image_link'] == 2) {
				echo '<a href="' . $imageLink . '" rel="prettyPhoto[pp_gal2]" class="'.$class.'" role="button">'.$viewPhoto.'</a>';
			} else if ($imageLink != '' && $this->t['image_link'] == 1) {

				/*echo '<figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">';
				echo '<a href="'.$imageLink.'" itemprop="contentUrl" class="btn btn-primary photoswipe-button" role="button" data-size="'.$w.'x'.$h.'" >'.JText::_('COM_PHOCAPHOTO_VIEW_PHOTO').'</a>';
				echo '</figure>';*/
				// Photoswipe cannot have too instances, so the second link goes to detail view
				echo '<a href="' . $link . '" class="'.$class.'" role="button">'.$viewPhoto.'</a>';
			} else {
				echo '<a href="' . $link . '" class="'.$class.'" role="button">'.$viewPhoto.'</a>';
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
