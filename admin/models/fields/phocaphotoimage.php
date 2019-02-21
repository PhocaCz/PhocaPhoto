<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();



class JFormFieldPhocaPhotoImage extends JFormField
{
	protected $type 		= 'PhocaPhotoImage';

	protected function getInput() {
		
		$db = JFactory::getDBO();

		$wheres		= array();
		
		$wheres[] = ' a.published = 1';
		$wheres[] = ' c.published = 1';
		
		$query = " SELECT CONCAT_WS (' &rarr; ', c.title, a.title) as text, a.id as value"
				.' FROM #__phocagallery AS a'
				.' LEFT JOIN #__phocagallery_categories AS c ON c.id = a.catid'
				. ' WHERE ' . implode( ' AND ', $wheres )
				. ' ORDER BY c.ordering, a.ordering';
				//. ' GROUP BY c.id';	
		
		$db->setQuery( $query );
		$data = $db->loadObjectList();
	

		$required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		
		$tree = array();

		array_unshift($tree, JHTML::_('select.option', '', '- '.JText::_('COM_PHOCAPHOTO_SELECT_CATEGORY').' -', 'value', 'text'));
		
		return JHTML::_('select.genericlist',  $data,  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
	}
}
?>