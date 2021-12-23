<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

if (!Factory::getUser()->authorise('core.manage', 'com_phocaphoto')) {
    throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 404);
    return false;
}

require JPATH_ADMINISTRATOR . '/components/com_phocaphoto/libraries/autoloadPhoca.php';
require_once( JPATH_COMPONENT.'/controller.php' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
require_once( JPATH_COMPONENT.'/helpers/phocaphotoutils.php' );
require_once( JPATH_COMPONENT.'/helpers/phocaphoto.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadmin.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadminview.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadminviews.php' );


jimport('joomla.application.component.controller');
$controller	= BaseController::getInstance('PhocaPhotoCp');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
?>
