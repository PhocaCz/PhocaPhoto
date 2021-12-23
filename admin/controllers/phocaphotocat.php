<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Router\Route;
jimport('joomla.application.component.controllerform');

class PhocaPhotoCpControllerPhocaPhotocat extends FormController
{
	protected	$option 		= 'com_phocaphoto';

	function __construct($config=array()) {
		parent::__construct($config);
	}

	protected function allowAdd($data = array()) {
		$user		= Factory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.create', 'com_phocaphoto');
		if ($allow === null) {
			return parent::allowAdd($data);
		} else {
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id') {
		$user		= Factory::getUser();
		$allow		= null;
		$allow	= $user->authorise('core.edit', 'com_phocaphoto');
		if ($allow === null) {
			return parent::allowEdit($data, $key);
		} else {
			return $allow;
		}
	}

	public function batch() {
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		$model	= $this->getModel('phocaphotocat', '', array());
		$this->setRedirect(Route::_('index.php?option=com_phocaphoto&view=phocaphotocats'.$this->getRedirectToListAppend(), false));
		return parent::batch($model);
	}
}
?>
