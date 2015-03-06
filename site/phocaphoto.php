<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

require_once( JPATH_COMPONENT.'/controller.php' );
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/phocaphotoutils.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/phocaphoto.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/html/ordering.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/route.php' );
require_once( JPATH_ADMINISTRATOR.'/components/com_phocaphoto/helpers/pagination.php' );


// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}

$classname    = 'PhocaPhotoController'.ucfirst($controller);
$controller   = new $classname( );
$controller->execute( JFactory::getApplication()->input->get('task') );
$controller->redirect();
?>