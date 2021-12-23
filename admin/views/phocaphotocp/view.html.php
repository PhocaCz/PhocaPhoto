<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
jimport( 'joomla.application.component.view' );

class PhocaPhotoCpViewPhocaPhotoCp extends HtmlView
{
	protected $t;
	protected $views;

	function display($tpl = null) {

		$this->t	= PhocaPhotoUtils::setVars('cp');
		$this->r	= new PhocaPhotoRenderAdminview();

		$i = ' icon-';
		$d = 'duotone ';
		$this->views= array(
		'phocagallery'		=> array($this->t['l'] . '_PHOCA_GALLERY', $d.$i.'pictures', '#95313e'),
		'info'				=> array($this->t['l'] . '_INFO', $d.$i.'info-circle', '#3378cc')
		);

		$this->t['version'] = PhocaPhotoUtils::getExtensionVersion();
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		require_once JPATH_COMPONENT.'/helpers/'.$this->t['c'].'cp.php';
		$class	= $this->t['n'] . 'CpHelper';
		$canDo	= $class::getActions($this->t['c']);
		ToolbarHelper::title( Text::_( $this->t['l'].'_PH_CONTROL_PANEL' ), 'home-2 cpanel' );

		// This button is unnecessary but it is displayed because Joomla! design bug
		$bar = Toolbar::getInstance( 'toolbar' );
		$dhtml = '<a href="index.php?option=com_phocaphoto" class="btn btn-small"><i class="icon-home-2" title="'.Text::_($this->t['l'].'_CONTROL_PANEL').'"></i> '.Text::_($this->t['l'].'_CONTROL_PANEL').'</a>';
		$bar->appendButton('Custom', $dhtml);

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences($this->t['o']);
			ToolbarHelper::divider();
		}
		ToolbarHelper::help( 'screen.'.$this->t['c'], true );
	}
}
?>
