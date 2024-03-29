<?php
/**
 * @version		$Id: route.php 11190 2008-10-20 00:49:55Z ian $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant to the
 * GNU General Public License, and as distributed it includes or is derivative
 * of works licensed under the GNU General Public License or other free or open
 * source software licenses. See COPYRIGHT.php for copyright notices and
 * details.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
jimport('joomla.application.component.helper');

class PhocaPhotoRoute
{
	public static function getImageRoute($id, $catid = 0, $idAlias = '', $catidAlias = '', $type = 'image', $suffix = '')
	{

		$app 		= Factory::getApplication();
		$menu 		= $app->getMenu();
		$active 	= $menu->getActive();
		$option		= $app->input->get( 'option', '', 'string' );

		$activeId 	= 0;
		$notCheckId	= 0;
		if (isset($active->id)){
			$activeId    = $active->id;
		}

		if ((int)$activeId > 0 && $option == 'com_phocaphoto') {

			$needles = array(
				'image'  => (int) $id,
				'category' => (int) $catid,
				'categories' => (int)$activeId
			);
			$notCheckId	= 1;
		} else {
			$needles = array(
				'image'  => (int) $id,
				'category' => (int) $catid,
				'categories' => ''
			);
			$notCheckId	= 0;
		}


		if ($idAlias != '') {
			$id = $id . ':' . $idAlias;
		}
		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		//Create the link

		switch ($type)
		{
			case 'image':
				$link = 'index.php?option=com_phocaphoto&view=image&catid='. $catid .'&id='. $id;
				break;

			default:
				$link = '';
			break;
		}

		if ($item = PhocaPhotoRoute::_findItem($needles, $notCheckId)) {
			if (isset($item->id) && ((int)$item->id > 0)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		if ($suffix != '') {
			$link .= '&'.$suffix;
		}

		return $link;
	}

	/*
	public static function getImageRoute($id, $catid = 0, $idAlias = '', $catidAlias = '')
	{
		$needles = array(
			'image'  => (int) $id,
			'category' => (int) $catid,
			'categories' => ''
		);


		if ($idAlias != '') {
			$id = $id . ':' . $idAlias;
		}
		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		$link = 'index.php?option=com_phocaphoto&view=image&id='. $id;


		if($item = self::_findItem($needles)) {
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		return $link;
	}
	*/

	public static function getCategoryRoute($catid, $catidAlias = '')
	{
		$needles = array(
			'category' => (int) $catid,
			//'section'  => (int) $sectionid,
			'categories' => ''
		);

		if ($catidAlias != '') {
			$catid = $catid . ':' . $catidAlias;
		}

		//Create the link
		$link = 'index.php?option=com_phocaphoto&view=category&id='.$catid;

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if(isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		};

		return $link;
	}


	public static function getCategoriesRoute()
	{
		$needles = array(
			'categories' => ''
		);

		//Create the link
		$link = 'index.php?option=com_phocaphoto&view=categories';

		if($item = self::_findItem($needles)) {
			if(isset($item->query['layout'])) {
				$link .= '&layout='.$item->query['layout'];
			}
			if (isset($item->id)) {
				$link .= '&Itemid='.$item->id;
			}
		}

		return $link;
	}



	/*protected static function _findItem($needles, $notCheckId = 0)
	{
		$app = Factory::getApplication();
		$menus	= $app->getMenu('site', array());
		$items	= $menus->getItems('component', 'com_phocaphoto');

		if(!$items) {
			return $app->input->get('Itemid', 0, '', 'int');
			//return null;
		}

		$match = null;


		foreach($needles as $needle => $id)
		{

			if ($notCheckId == 0) {
				foreach($items as $item) {
					if ((@$item->query['view'] == $needle) && (@$item->query['id'] == $id)) {
						$match = $item;
						break;
					}
				}
			} else {
				foreach($items as $item) {
					if (@$item->query['view'] == $needle) {
						$match = $item;
						break;
					}
				}
			}

			if(isset($match)) {
				break;
			}
		}

		return $match;
	}*/

	protected static function _findItem($needles, $notCheckId = 0) {
		//$component =& JComponentHelper::getComponent('com_phocagallery');


		// Don't check ID for specific views
		$notCheckIdArray =  array('categories');

		$app	= Factory::getApplication();
		$menus	= $app->getMenu('site', array());
		$items	= $menus->getItems('component', 'com_phocaphoto');



		if(!$items) {
			return Factory::getApplication()->input->get('Itemid', 0, '', 'int');
			//return null;
		}

		$match = null;

		foreach($needles as $needle => $id) {

			if ($notCheckId == 0) {
				foreach($items as $item) {

					// The view must match
					// In case the view does not have any ID like categories view
					// there is no need to compare to ID
					if ((@$item->query['view'] == $needle) && (in_array($needle, $notCheckIdArray) || @$item->query['id'] == $id)) {


						$match = $item;
						break;
					}
				}
			} else {
				foreach($items as $item) {

					if (@$item->query['view'] == $needle) {
						$match = $item;
						break;
					}
				}
			}

			if(isset($match)) {
				break;
			}
		}

		return $match;
	}
}
?>
