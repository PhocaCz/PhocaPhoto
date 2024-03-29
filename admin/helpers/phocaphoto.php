<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
jimport('joomla.application.component.model');

class PhocaPhotoHelper
{
	public static function getPath() {
		$path = new StdClass();
		$path->image_abs	= JPATH_ROOT . '/images/phocagallery/';
		$path->image_rel	= 'images/phocagallery/';
		return $path;
	}

	public static function getTitleFromFile(&$filename, $displayExt = 0) {

		$filename 			= str_replace('//', '/', $filename);
	//	$filename			= str_replace(DS, '/', $filename);
		$folderArray		= explode('/', $filename);
		$countFolderArray	= count($folderArray);
		$lastArrayValue 	= $countFolderArray - 1;

		$title = new stdClass();
		$title->with_extension 		= $folderArray[$lastArrayValue];
		$title->without_extension	= self::removeExtension($folderArray[$lastArrayValue]);

		if ($displayExt == 1) {
			return $title->with_extension;
		} else if ($displayExt == 0) {
			return $title->without_extension;
		} else {
			return $title;
		}
	}

	public static function removeExtension($filename) {
		return substr($filename, 0, strrpos( $filename, '.' ));
	}

	public static function getThumbnailName($path, $filename, $size) {

		$thumbName	= new StdClass();
		$title 		= self::getTitleFromFile($filename , 1);
		switch ($size) {
			case 'large':
			$fileNameThumb 	= 'phoca_thumb_l_'. $title;
			$thumbName->abs	= Path::clean(str_replace($title, 'thumbs/' . $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;

			case 'medium':
			$fileNameThumb 	= 'phoca_thumb_m_'. $title;
			$thumbName->abs	= Path::clean(str_replace($title, 'thumbs/' . $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;

			default:
			case 'small':
			$fileNameThumb 	= 'phoca_thumb_s_'. $title;
			$thumbName->abs	= Path::clean(str_replace($title, 'thumbs/' . $fileNameThumb, $path->image_abs . $filename));
			$thumbName->rel	= str_replace ($title, 'thumbs/' . $fileNameThumb, $path->image_rel . $filename);
			break;
		}
		return $thumbName;
	}

	public static function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId = 0) {

		foreach ($data as $key) {
			$show_text =  $text . $key->text;

			if ($key->parentid == $id && $currentId != $id && $currentId != $key->value) {
				$tree[$key->value] 			= new CMSObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = PhocaPhotoHelper::CategoryTreeOption($data, $tree, $key->value, $show_text . " - ", $currentId );
			}
		}
		return($tree);
	}

	public static function categoryOptions($type = 0)
	{


		$db = Factory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM #__phocaphoto_categories AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$items = $db->loadObjectList();

		$catId	= -1;

		$javascript 	= 'class="form-control" size="1" onchange="submitform( );"';

		$tree = array();
		$text = '';
		$tree = PhocaPhotoHelper::CategoryTreeOption($items, $tree, 0, $text, $catId);

		return $tree;

	}

	public static function getFooter() {

		PluginHelper::importPlugin('phocatools');
		$results = Factory::getApplication()->triggerEvent('onPhocatoolsOnDisplayInfo', array('NjI5NTY4NTcxMTc='));
		if (isset($results[0]) && $results[0] === true) {
			return '';
		}

		echo '<div style="text-align: right;margin:10px auto;">Powered by <a href="https://www.phoca.cz/phocaphoto" target="_blank" title="Phoca Photo">Phoca Photo</a></div>';
	}

	public static function setFileNameByImageId($id = 0) {

		$f = '';
		if ((int)$id > 0) {
			$db 	= Factory::getDBO();
			$query = ' SELECT a.filename, a.extid, a.exts, a.extm, a.extw, a.exth'
					.' FROM #__phocagallery AS a'
					.' WHERE a.id = '.(int)$id
					.' ORDER BY a.id'
					.' LIMIT 1';
			$db->setQuery($query);
			$f = $db->loadObject();
		}

		return $f;
	}
}
?>
