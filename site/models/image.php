<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.model');

class PhocaPhotoModelImage extends JModelLegacy
{
	var $_item 				= null;
	var $_category 			= null;
	var $_itemname			= null;
	var $_itemnext			= null;
	var $_itemprev			= null;

	function __construct() {
		$app	= JFactory::getApplication();
		parent::__construct();
		
		$this->setState('filter.language',$app->getLanguageFilter());
	}

	function getItem( $itemId) {
		if (empty($this->_item)) {			
			$query			= $this->_getitemQuery( $itemId );
			$this->_item	= $this->_getList( $query, 0 , 1 );

			if (empty($this->_item)) {
				return null;
			} 

		}
		return $this->_item;
	}
	
	function getItemNext($ordering, $catid) {
		if (empty($this->_itemnext)) {			
			$query				= $this->_getItemQueryOrdering( $ordering, $catid, 2 );
			$this->_itemnext	= $this->_getList( $query, 0 , 1 );

			if (empty($this->_itemnext)) {
				return null;
			} 

		}
		return $this->_itemnext;
	}
	function getItemPrev($ordering, $catid) {
		if (empty($this->_itemprev)) {			
			$query				= $this->_getItemQueryOrdering( $ordering, $catid, 1 );
			$this->_itemprev	= $this->_getList( $query, 0 , 1 );

			if (empty($this->_itemprev)) {
				return null;
			} 

		}
		return $this->_itemprev;
	}
	
	private function _getItemQueryOrdering($ordering, $catid, $direction) {
		
		$wheres[]	= " c.catid= ".(int) $catid;
		//$wheres[]	= " c.catid= cc.id";
		$wheres[] = " c.published = 1";
		$wheres[] = " cc.published = 1";
		
		if ($direction == 1) {
			$wheres[] = " c.ordering < " . (int) $ordering;
			$order = 'DESC';
		} else {
			$wheres[] = " c.ordering > " . (int) $ordering;
			$order = 'ASC';
		}
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}
		
		$query = ' SELECT c.id, c.title, c.alias, c.catid, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias'
				.' FROM #__phocagallery AS c' 
				.' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = c.catid'
				.' WHERE ' . implode( ' AND ', $wheres )
				.' ORDER BY c.ordering '.$order;		
		return $query;
	
	}
	private function _getItemQuery( $itemId ) {
		
		//$app		= JFactory::getApplication();
		//$params 	= $app->getParams();

		$categoryId	= 0;
		$category	= $this->getCategory($itemId);
		if (isset($category[0]->id)) {
			$categoryId = $category[0]->id;
		}
		
		$wheres[]	= " c.catid= ".(int) $categoryId;
		$wheres[]	= " c.catid= cc.id";
		$wheres[] 	= " c.published = 1";
		$wheres[] 	= " cc.published = 1";
		$wheres[] 	= " c.id = " . (int) $itemId;
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}
		
		$query = ' SELECT c.id, c.title, c.alias, c.catid, c.description, c.ordering, c.metadesc, c.metakey, c.filename, cc.id AS categoryid, cc.title AS categorytitle, cc.alias AS categoryalias'
				.' FROM #__phocagallery AS c' 
				.' LEFT JOIN #__phocagallery_categories AS cc ON cc.id = c.catid'
				.' WHERE ' . implode( ' AND ', $wheres )
				.' ORDER BY c.ordering';		
		return $query;
	}
	
	function getCategory($itemId) {
		if (empty($this->_category)) {			
			$query			= $this->_getCategoryQuery( $itemId );
			$this->_category= $this->_getList( $query, 0, 1 );
		}
		return $this->_category;
	}
	
	function _getCategoryQuery( $itemId ) {
		
		$wheres		= array();
		//$app		= JFactory::getApplication();
		//$params 	= $app->getParams();

		$wheres[]	= " c.id= ".(int)$itemId;
		$wheres[] = " cc.published = 1";
		
		if ($this->getState('filter.language')) {
			$wheres[] =  ' c.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
			$wheres[] =  ' cc.language IN ('.$this->_db->Quote(JFactory::getLanguage()->getTag()).','.$this->_db->Quote('*').')';
		}
		
		$query = " SELECT cc.id, cc.title, cc.alias, cc.description"
				. " FROM #__phocagallery_categories AS cc"
				. " LEFT JOIN #__phocagallery AS c ON c.catid = cc.id"
				. " WHERE " . implode( " AND ", $wheres )
				. " ORDER BY cc.ordering";		
		return $query;
	}
}
?>