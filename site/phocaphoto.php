<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Factory;

require_once( JPATH_COMPONENT.'/controller.php' );
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/phocaphotoutils.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/phocaphoto.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/html/ordering.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/route.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/pagination.php' );



// Require specific controller if requested

if($controller = Factory::getApplication()->input->get( 'controller')) {
    $path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
// Create the controller
$app	= Factory::getApplication();
$classname    = 'PhocaPhotoController'.ucfirst((string)$controller);
$controller   = new $classname( );

// Perform the Request task
$controller->execute(Factory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
?>
