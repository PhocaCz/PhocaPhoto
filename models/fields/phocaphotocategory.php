<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

if (! class_exists('PhocaPhotoCategory')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/phocaphoto.php');
}

class JFormFieldPhocaPhotoCategory extends JFormField
{
	protected $type 		= 'PhocaPhotoCategory';

	protected function getInput() {
	
		// Initialize variables.
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$attr .= $this->multiple ? ' multiple="multiple"' : '';
		
		$db = JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocagallery_categories AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
	
		// TODO - check for other views than category edit
		$view 	= JRequest::getVar( 'view' );
		$catId	= -1;
		if ($view == 'phocaphotocat') {
			$id 	= $this->form->getValue('id'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}
		/*if ($view == 'phocaphotofile') {
			$id 	= $this->form->getValue('catid'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}*/
		
		
		
		//$required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		
		$tree = array();
		$text = '';
		$tree = PhocaPhotoHelper::CategoryTreeOption($data, $tree, 0, $text, $catId);
		
		//if ($required == TRUE) {
		
		//} else {
		
			//array_unshift($tree, JHTML::_('select.option', '', '- '.JText::_('COM_PHOCAPHOTO_SELECT_CATEGORY').' -', 'value', 'text'));
		//}
		//return JHTML::_('select.genericlist',  $tree,  $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id );
		
		return JHtml::_('select.genericlist', $tree, $this->name,
			array(
				'list.attr' => $attr,
				'list.select' => $this->value,
				'id' => $this->id
			)
		);
	}
}
?>