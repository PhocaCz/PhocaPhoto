<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Session\Session;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Factory;
jimport('joomla.application.component.controlleradmin');

class PhocaPhotoCpControllerPhocaPhotoItems extends AdminController
{
	protected	$option 		= 'com_phocaphoto';

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function &getModel($name = 'PhocaPhotoItem', $prefix = 'PhocaPhotoCpModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function saveOrderAjax() {
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));
		$pks = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);
		$model = $this->getModel();
		$return = $model->saveorder($pks, $order);
		if ($return) { echo "1";}
		Factory::getApplication()->close();
	}
}
?>
