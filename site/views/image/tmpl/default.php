<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
echo '<div id="ph-ph-item-box" class="ph-item-view'.$this->t['p']->get( 'pageclass_sfx' ).'">';
if ( $this->t['p']->get( 'show_page_heading' ) ) {
	echo '<div class="page-header"><h1><h1>'. $this->escape($this->t['p']->get('page_heading')) . '</h1></div>';
}

if (isset($this->category[0]->id) && ($this->t['display_back'] == 2 || $this->t['display_back'] == 3)) {
	if ($this->category[0]->id > 0) {
		$linkUp = Route::_(PhocaPhotoRoute::getCategoryRoute($this->category[0]->id, $this->category[0]->alias));
		$linkUpText = $this->category[0]->title;
	} else {
		$linkUp 	= false;
		$linkUpText = false;
	}

	if ($linkUp && $linkUpText) {
		echo '<div class="ph-top">'
		.'<a class="btn btn-success" title="'.$linkUpText.'" href="'. $linkUp.'" ><span class="glyphicon glyphicon-arrow-left fas fa-arrow-left"></span> '.Text::_($linkUpText).'</a></div>';
	}
}

if ($this->t['display_title_image_view'] == 1 && isset($this->item[0]->title) && $this->item[0]->title != '') {
	echo '<h3>' . $this->item[0]->title . '</h3>';
}

if ($this->t['display_desc_image_view'] == 1 && isset($this->item[0]->description) && $this->item[0]->description != '') {
	echo '<div class="ph-desc">'. $this->item[0]->description. '</div>';
}

$v = $this->item[0];
if (!empty($v)) {

    $imagePath = '';
    if (isset($v->filename) && ($v->filename != '' && $v->filename != '-')) {
        $image 	= PhocaPhotoHelper::getThumbnailName($this->t['path'], $v->filename, 'large');
        if (isset($image->rel) && $image->rel != '') {
            $imagePath = Uri::base(true).'/'.$image->rel;
        }
    } else if (isset($v->extl) && $v->extl != '') {
        $imagePath = $v->extl;
    }

	echo '<div class="ph-image-full-box">';
	if (isset($imagePath) && $imagePath != '') {
		echo '<img src="'.$imagePath.'" alt="" class="img-responsive img-thumbnail ph-image-full"';
		if (isset($this->t['image_width']) && (int)$this->t['image_width'] > 0 && isset($this->t['image_height']) && (int)$this->t['image_height'] > 0) {
			echo ' style="width:'.$this->t['image_width'].'px;height:'.$this->t['image_height'].'px"';
		}
		echo ' />';
	}
	echo '</div>'. "\n";




	// External Links
	echo '<div class="ph-image-full-box-links">';
	$class = '';
	if ($this->t['extlink_class'] != '') {
		$class = strip_tags($this->t['extlink_class']);
	}
	if ($this->t['extlink_class_image'] != '') {
		$class .= ' '.strip_tags($this->t['extlink_class_image']);
	}

	$class = 'class="'.$class.'"';

	// ICON EXTERNAL LINK 1
		if ($this->t['display_icon_extlink1'] == 2 || $this->t['display_icon_extlink1'] == 3) {

			$extlink1	= explode("|", $v->extlink1, 4);
			if (isset($extlink1[0]) && $extlink1[0] != '' && isset($extlink1[1])) {
				if (!isset($extlink1[2])) {
					$extlink1[2] = '_self';
				}
				if (!isset($extlink1[3]) || $extlink1[3] == 1) {

					$extlink1[4] = '<span class="glyphicon glyphicon glyphicon-share fas-fa-share"></span>';
					$extlink1[5] = '';
				} else {
					$extlink1[4] = $extlink1[1];
					$extlink1[5] = '';
				}

				$pos10 		= strpos($extlink1[0], 'http://');
				$pos20 		= strpos($extlink1[0], 'https://');
				$extLinkUrl2	= 'http://'.$extlink1[0];
				if ($pos10 === 0) {
					$extLinkUrl2 = $extlink1[0];
				} else if ($pos20 === 0) {
					$extLinkUrl2 = $extlink1[0];
				}

				if ($this->t['extlink1_class_icon']	!= '') {
					$extlink1[4] = '<span class="'.$this->t['extlink1_class_icon'].'"></span> ' . $extlink1[4];
				}

				echo ' <a '.$class.' title="'.$extlink1[1] .'"'
					.' href="'. $extLinkUrl2 .'" target="'.$extlink1[2] .'" '.$extlink1[5].'>'
					.$extlink1[4].'</a>';
			}
		}

		// ICON EXTERNAL LINK 2
		if ($this->t['display_icon_extlink2'] == 2 || $this->t['display_icon_extlink2'] == 3) {

			$extlink2	= explode("|", $v->extlink2, 4);
			if (isset($extlink2[0]) && $extlink2[0] != '' && isset($extlink2[1])) {
				if (!isset($extlink2[2])) {
					$extlink2[2] = '_self';
				}
				if (!isset($extlink2[3]) || $extlink2[3] == 1) {

					$extlink2[4] = '<span class="glyphicon glyphicon glyphicon-share fas fa-share"></span>';
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
					$extlink2[4] = '<span class="'.$this->t['extlink2_class_icon'].'"></span> '. $extlink2[4];
				}

				echo ' <a '.$class.' class="" title="'.$extlink2[1] .'"'
					.' href="'. $extLinkUrl2 .'" target="'.$extlink2[2] .'" '.$extlink2[5].'>'
					.$extlink2[4].'</a>';
			}
		}

	echo '</div>';// end full box links
	echo '<div class="clearfix"></div>';

	$socO1 = '';
	$socO2 = '';

	if ($this->t['enable_social'] == 1) {

		$uri = Uri::getInstance();
		$uri = PhocaPhotoUtils::encodeURIComponent($uri);
		$socO1 = '<div class="pp_social ph-social"><div class="twitter"><a href="https://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script></div></div>';

		$socO2 = '<div class="pp_social ph-social"><div class="facebook"><iframe src="https://www.facebook.com/plugins/like.php?locale=en_US&href='.$uri.'&amp;layout=button_count&amp;show_faces=true&amp;width=100&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:23px;" allowTransparency="true"></iframe></div></div>';

	}

	if ((isset($this->itemnext[0]) && $this->itemnext[0]) || (isset($this->itemprev[0]) && $this->itemprev[0])) {
		echo '<div class="row"><div class="col-sm-4 col-md-4 ph-ph-image-navigation-box">';
		if(isset($this->itemprev[0]) && $this->itemprev[0]) {
			$p = $this->itemprev[0];
			$linkPrev = Route::_(PhocaPhotoRoute::getImageRoute($p->id, $p->catid, $p->alias, $p->categoryalias));
			echo '<div class="pull-left"><a href="'.$linkPrev.'" class="btn btn-default ph-image-navigation" role="button"><span class="glyphicon glyphicon-arrow-left fas fa-arrow-left"></span> '.Text::_('COM_PHOCAPHOTO_PREVIOUS').'</a></div>';
		}
		echo '</div>';

		echo '<div class="col-sm-2 col-md-2 ph-ph-image-navigation-box">';
		echo $socO1;
		echo '</div>';

		echo '<div class="col-sm-2 col-md-2 ph-ph-image-navigation-box">';
		echo $socO2;
		echo '</div>';

		echo '<div class="col-sm-4 col-md-4 ph-ph-image-navigation-box">';
		if(isset($this->itemnext[0]) && $this->itemnext[0]) {
			$n = $this->itemnext[0];
			$linkNext = Route::_(PhocaPhotoRoute::getImageRoute($n->id, $n->catid, $n->alias, $n->categoryalias));
			echo '<div class="pull-right"><a href="'.$linkNext.'" class="btn btn-default ph-image-navigation" role="button">'.Text::_('COM_PHOCAPHOTO_NEXT').' <span class="glyphicon glyphicon-arrow-right fas fa-arrow-right"></span></a></div>';
		}
		echo '</div></div>';
	} else {
		echo '<div class="col-sm-4 col-md-4 ph-ph-image-navigation-box"></div>';
		echo '<div class="col-sm-2 col-md-2 ph-ph-image-navigation-box">';
		echo $socO1;
		echo '</div>';

		echo '<div class="col-sm-2 col-md-2 ph-ph-image-navigation-box">';
		echo $socO2;
		echo '</div>';
		echo '<div class="col-sm-4 col-md-4 ph-ph-image-navigation-box"></div>';
	}




}
echo '</div>';
echo '<div>&nbsp;</div>';
echo PhocaPhotoHelper::getFooter();
?>
