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

class PhocaPhotoViewCategories extends JViewLegacy
{
	protected $t;

	function display($tpl = null)
	{

		$lang = JFactory::getLanguage();
		$lang->load('com_phocagallery');

		$app								= JFactory::getApplication();
		$model								= $this->getModel();
		$document							= JFactory::getDocument();
		$this->t['p'] 						= $app->getParams();
		$this->t['categories']				= $model->getCategoriesList();
		//$this->t['mostvieweddocs']			= $model->getMostViewedDocsList($this->t['p']);

		$this->t['photo_metakey'] 			= $this->t['p']->get( 'photo_metakey', '' );
		$this->t['photo_metadesc'] 			= $this->t['p']->get( 'photo_metadesc', '' );
		$this->t['description']				= $this->t['p']->get( 'description', '' );
		$this->t['load_bootstrap']			= $this->t['p']->get( 'load_bootstrap', 0 );
		$this->t['equal_height']			= $this->t['p']->get( 'equal_height', 0 );
		$this->t['columns_cats']			= $this->t['p']->get( 'columns_cats', 3 );
		$this->t['image_width_cats']		= $this->t['p']->get( 'image_width_cats', '' );
		$this->t['image_height_cats']		= $this->t['p']->get( 'image_height_cats', '' );
		$this->t['display_subcat_cats_view']= $this->t['p']->get( 'display_subcat_cats_view', 0 );


		JHTML::stylesheet('media/com_phocaphoto/css/style.css' );
		if ($this->t['load_bootstrap'] == 1) {
			JHtml::_('jquery.framework');
			JHTML::stylesheet('media/com_phocaphoto/bootstrap/css/bootstrap.min.css' );
			$document->addScript(JURI::root(true).'/media/com_phocaphoto/bootstrap/js/bootstrap.min.js');
		}

		if ($this->t['equal_height'] == 1) {
			JHtml::_('jquery.framework', false);
			$document->addScript(JURI::root(true).'/media/com_phocaphoto/js/jquery.equalheights.min.js');

			$document->addScriptDeclaration(
			'jQuery(document).ready(function(){
				jQuery(\'.ph-thumbnail\').equalHeights();
			});');
		}

		$this->t['path'] = PhocaPhotoHelper::getPath();

		$this->_prepareDocument();
		parent::display($tpl);

	}

	protected function _prepareDocument() {

		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$menu 		= $menus->getActive();
		$pathway 	= $app->getPathway();
		$title 		= null;

		$this->t['photo_metakey'] 		= $this->t['p']->get( 'photo_metakey', '' );
		$this->t['photo_metadesc'] 		= $this->t['p']->get( 'photo_metadesc', '' );

		if ($menu) {
			$this->t['p']->def('page_heading', $this->t['p']->get('page_title', $menu->title));
		} else {
			$this->t['p']->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}
/*
		$title = $this->t['p']->get('page_heading', '');
		if (empty($title)) {
			$title = htmlspecialchars_decode($app->get('sitename'));
		} else if ($app->get('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', htmlspecialchars_decode($app->get('sitename')), $title);
		}
		//$this->document->setTitle($title);

		if (empty($title) || (isset($title) && $title == '')) {
			$title = $this->item->title;
		}
		$this->document->setTitle($title);*/

		  // get page title
          $title = $this->t['p']->get('page_title', '');
          // if no title is set take the sitename only
          if (empty($title)) {
             $title = $app->get('sitename');
          }
          // else add the title before or after the sitename
          elseif ($app->get('sitename_pagetitles', 0) == 1) {
             $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
          }
          elseif ($app->get('sitename_pagetitles', 0) == 2) {
             $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
          }
          $this->document->setTitle($title);


		if ($this->t['photo_metadesc'] != '') {
			$this->document->setDescription($this->t['photo_metadesc']);
		} else if ($this->t['p']->get('menu-meta_description', '')) {
			$this->document->setDescription($this->t['p']->get('menu-meta_description', ''));
		}

		if ($this->t['photo_metakey']  != '') {
			$this->document->setMetadata('keywords', $this->t['photo_metakey'] );
		} else if ($this->t['p']->get('menu-meta_keywords', '')) {
			$this->document->setMetadata('keywords', $this->t['p']->get('menu-meta_keywords', ''));
		}

		if ($app->get('MetaTitle') == '1' && $this->t['p']->get('menupage_title', '')) {
			$this->document->setMetaData('title', $this->t['p']->get('page_title', ''));
		}
	}
}
?>
