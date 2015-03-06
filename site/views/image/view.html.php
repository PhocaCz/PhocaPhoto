<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
jimport( 'joomla.itemsystem.folder' ); 
jimport( 'joomla.itemsystem.file' );

class PhocaPhotoViewImage extends JViewLegacy
{
	protected $item;
	protected $itemnext;
	protected $itemprev;
	protected $category;
	protected $t;

	function display($tpl = null){		
		
		$app					= JFactory::getApplication();
		$this->t['p'] 			= $app->getParams();
		$uri 					= JFactory::getURI();
		$model					= $this->getModel();
		$document				= JFactory::getDocument();
		$itemId					= $app->input->get('id', 0, 'int');

		$this->category			= $model->getCategory($itemId);
		$this->item				= $model->getItem($itemId);
		

		$this->t['photo_metakey'] 			= $this->t['p']->get( 'photo_metakey', '' );
		$this->t['photo_metadesc'] 			= $this->t['p']->get( 'photo_metadesc', '' );
		$this->t['load_bootstrap']			= $this->t['p']->get( 'load_bootstrap', 0 );
		$this->t['photo_width']				= $this->t['p']->get( 'photo_width', '100%' );
		$this->t['photo_height']			= $this->t['p']->get( 'photo_height', '500px' );
		$this->t['display_method']			= $this->t['p']->get( 'display_method', 1 );
		$this->t['file_name']				= htmlspecialchars($this->t['p']->get( 'file_name', 'tour' ));
		$this->t['display_back']			= $this->t['p']->get( 'display_back', 3 );
		$this->t['enable_social']			= $this->t['p']->get( 'enable_social', 0 );
		$this->t['enable_image_navigation']	= $this->t['p']->get( 'enable_image_navigation', 0 );
		
		
		$this->itemnext[0]			= false;
		$this->itemprev[0]			= false;
		if ($this->t['enable_image_navigation'] == 1) {
			if (isset($this->item[0]->ordering) && isset($this->item[0]->catid) && isset($this->item[0]->id) && $this->item[0]->catid > 0 && $this->item[0]->id > 0) {
				$this->itemnext			= $model->getItemNext($this->item[0]->ordering, $this->item[0]->catid);
				$this->itemprev			= $model->getItemPrev($this->item[0]->ordering, $this->item[0]->catid);
			}
		}
		
		JHTML::stylesheet('media/com_phocaphoto/css/style.css' );
		
		JHtml::_('jquery.framework', false);
		if ($this->t['load_bootstrap'] == 1) {
			JHTML::stylesheet('media/com_phocaphoto/bootstrap/css/bootstrap.min.css' );
			$document->addScript(JURI::root(true).'/media/com_phocaphoto/bootstrap/js/bootstrap.min.js');
		}
		
		if (isset($this->category[0]) && is_object($this->category[0]) && isset($this->item[0]) && is_object($this->item[0])){
			$this->_prepareDocument($this->category[0], $this->item[0]);
		}
		
		$this->t['path'] = PhocaPhotoHelper::getPath();
		parent::display($tpl);
		
	}
	
	protected function _prepareDocument($category, $item) {
		
		$app			= JFactory::getApplication();
		$menus			= $app->getMenu();
		$menu 			= $menus->getActive();
		$pathway 		= $app->getPathway();
		$title 			= null;
		
		$this->t['photo_metakey'] 		= $this->t['p']->get( 'photo_metakey', '' );
		$this->t['photo_metadesc'] 		= $this->t['p']->get( 'photo_metadesc', '' );
		
		if ($menu) {
			$this->t['p']->def('page_heading', $this->t['p']->get('page_title', $menu->title));
		} else {
			$this->t['p']->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		/*$title = $this->t['p']->get('page_title', '');
		if (empty($title) || (isset($title) && $title == '')) {
			$title = $this->item->title;
		}
		if (empty($title) || (isset($title) && $title == '')) {
			$title = htmlspecialchars_decode($app->getCfg('sitename'));
		} else if ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->getCfg('sitename')), $title);
		}
		//$this->document->setTitle($title);

		$this->document->setTitle($title);*/
		
		  // get page title
          $title = $this->t['p']->get('page_title', '');
          // if the page title is set append the item title (if set!)
          if (!empty($title) && !empty($item->title)) {
             $title .= " - " . $item->title;
          }
          // if still is no title is set take the sitename only
          if (empty($title)) {
             $title = $app->getCfg('sitename');
          }
          // else add the title before or after the sitename
          elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
             $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
          }
          elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
             $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
          }
          $this->document->setTitle($title);

		
		if ($item->metadesc != '') {
			$this->document->setDescription($item->metadesc);
		} else if ($this->t['photo_metadesc'] != '') {
			$this->document->setDescription($this->t['photo_metadesc']);
		} else if ($this->t['p']->get('menu-meta_description', '')) {
			$this->document->setDescription($this->t['p']->get('menu-meta_description', ''));
		} 

		if ($item->metakey != '') {
			$this->document->setMetadata('keywords', $item->metakey);
		} else if ($this->t['photo_metakey'] != '') {
			$this->document->setMetadata('keywords', $this->t['photo_metakey']);
		} else if ($this->t['p']->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->t['p']->get('menu-meta_keywords', ''));
		}

		if ($app->getCfg('MetaTitle') == '1' && $this->t['p']->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->t['p']->get('page_title', ''));
		}
		
		// Breadcrumbs TODO (Add the whole tree)
		$pathway 		= $app->getPathway();
		if (isset($category->id)) {
			if ($category->id > 0) {
				$pathway->addItem($category->title, JRoute::_(PhocaPhotoRoute::getCategoryRoute($category->id, $category->alias)));
			}
		}
		
		if (!empty($item->title)) {
			$pathway->addItem($item->title);
		}
	}
}
?>