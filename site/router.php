<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_phocaphoto
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Factory;
use Joomla\Database\ParameterType;


if (!class_exists('PhocaGalleryLoader')) {
    require_once( JPATH_ADMINISTRATOR.'/components/com_phocagallery/libraries/loader.php');
}
phocagalleryimport('phocagallery.path.routerrules');
phocagalleryimport('phocagallery.category.category');

/**
 * Routing class of com_phocaphoto
 *
 * @since  3.3
 */


class PhocaphotoRouter extends RouterView
{
	protected $noIDs = false;

	/**
	 * Content Component router constructor
	 *
	 * @param   JApplicationCms  $app   The application object
	 * @param   JMenu            $menu  The menu object to work with
	 */
	public function __construct($app = null, $menu = null)
	{


		$params = ComponentHelper::getParams('com_phocaphoto');
		$this->noIDs = (bool) $params->get('remove_sef_ids');

		$categories = new RouterViewConfiguration('categories');
		$categories->setKey('id');
		$this->registerView($categories);


		$category = new RouterViewConfiguration('category');


		$category->setKey('id')->setParent($categories, 'parent_id')->setNestable();


		$this->registerView($category);



		$image = new RouterViewConfiguration('image');
		$image->setKey('id')->setParent($category, 'catid');//->setNestable();
		$this->registerView($image);




		parent::__construct($app, $menu);


		$this->attachRule(new MenuRules($this));


		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));



	}

	/**
	 * Method to get the segment(s) for a category
	 *
	 * @param   string  $id     ID of the category to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCategorySegment($id, $query)
	{



	    $category = PhocaGalleryCategory::getCategoryById($id);


		if (isset($category->id)) {





		    $path = PhocaGalleryCategory::getPath(array(), (int)$category->id, $category->parent_id, $category->title, $category->alias);

		    //$path = array_reverse($path, true);
		    //$path = array_reverse($category->getPath(), true);
			$path[0] = '1:root';// we don't use root but it is needed when building urls with joomla methods
			if ($this->noIDs)
			{
				foreach ($path as &$segment)
				{
					list($id, $segment) = explode(':', $segment, 2);
				}
			}

			return $path;
		}

		return array();
	}

	/**
	 * Method to get the segment(s) for a category
	 *
	 * @param   string  $id     ID of the category to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getCategoriesSegment($id, $query)
	{

		return $this->getCategorySegment($id, $query);
	}

	/**
	 * Method to get the segment(s) for an article
	 *
	 * @param   string  $id     ID of the article to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 */
	public function getImageSegment($id, $query)
	{

		if (!strpos($id, ':'))
		{
			$db = Factory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('alias'))
				->from($dbquery->qn('#__phocagallery'))
				->where('id = ' . $dbquery->q($id));
			$db->setQuery($dbquery);

			$id .= ':' . $db->loadResult();
		}

		if ($this->noIDs)
		{
			list($void, $segment) = explode(':', $id, 2);

			return array($void => $segment);
		}


		return array((int) $id => $id);
	}

	public function getInfoSegment($id, $query) {

		if (!strpos($id, ':')) {
			$db = Factory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('alias'))
				->from($dbquery->qn('#__phocagallery'))
				->where('id = ' . $dbquery->q($id));
			$db->setQuery($dbquery);

			$id .= ':' . $db->loadResult();
		}

		if ($this->noIDs) {
			list($void, $segment) = explode(':', $id, 2);
			return array($void => $segment);
		}

		return array((int) $id => $id);
	}

	/**
	 * Method to get the segment(s) for a form
	 *
	 * @param   string  $id     ID of the article form to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 *
	 * @since   3.7.3
	 */
	public function getFormSegment($id, $query)
	{

		return $this->getArticleSegment($id, $query);
	}

	/**
	 * Method to get the id for a category
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getCategoryId($segment, $query)
	{

        if (!isset($query['id']) && isset($query['view']) && $query['view'] == 'categories') {
            $query['id'] = 0;
        }

	    if ($this->noIDs)  {
	        $db = Factory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($db->quoteName('id'))
				->from($db->quoteName('#__phocagallery_categories'))
				->where(
					[
						$db->quoteName('alias') . ' = :alias',
						$db->quoteName('parent_id') . ' = :parent_id',
					]
				)
				->bind(':alias', $segment)
				->bind(':parent_id', $query['id'], ParameterType::INTEGER);
			$db->setQuery($dbquery);

			return (int) $db->loadResult();
		}

		    $category = false;
	    if (isset($query['id'])) {
		    if ((int)$query['id'] > 0) {
                $category = PhocaGalleryCategory::getCategoryById($query['id']);
            } else if ((int)$segment > 0) {

		        $category = PhocaGalleryCategory::getCategoryById((int)$segment);
                if (isset($category->id) && (int)$category->id > 0 && $category->parent_id == 0) {
                    // We don't have root category with 0 so we need to start with segment one
                    return (int)$category->id;
                }
            }




			if ($category) {
                if (!empty($category->subcategories)){

                    foreach ($category->subcategories as $child) {
                        if ($this->noIDs) {
                            if ($child->alias == $segment) {
                                return $child->id;
                            }
                        } else {
                            // We need to check full alias because ID can be same for Category and Item
                            $fullAlias = (int)$child->id . '-'.$child->alias;
                            if ($fullAlias == $segment) {
                                return $child->id;
                            }
                        }
                    }
                }
			}
		} else {
            // --- under test
            // We don't have query ID because of e.g. language
            // Should not happen because of modifications in build function here: administrator/components/com_phocacart/libraries/phocacart/path/routerrules.php
            /*if ((int)$segment > 0) {
		        $category = PhocaCartCategory::getCategoryById((int)$segment);
                if (isset($category->id) && (int)$category->id > 0 && $category->parent_id == 0) {
                    // We don't have root category with 0 so we need to start with segment one
                    return (int)$category->id;
                }
            }*/
            // under test
        }

		return false;
	}

	/**
	 * Method to get the segment(s) for a category
	 *
	 * @param   string  $segment  Segment to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getCategoriesId($segment, $query)
	{

		return $this->getCategoryId($segment, $query);
	}

	/**
	 * Method to get the segment(s) for an article
	 *
	 * @param   string  $segment  Segment of the article to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 */
	public function getImageId($segment, $query)
	{

		if ($this->noIDs)
		{
			$db = Factory::getDbo();
			$dbquery = $db->getQuery(true);
			$dbquery->select($dbquery->qn('id'))
				->from($dbquery->qn('#__phocagallery'))
				->where('alias = ' . $dbquery->q($segment))
				->where('catid = ' . $dbquery->q($query['id']));
			$db->setQuery($dbquery);

			return (int) $db->loadResult();
		}

		return (int) $segment;
	}

    public function parse(&$segments){
		return parent::parse($segments);
	}

    public function build(&$query) {
		return parent::build($query);
	}
}


function PhocaPhotoBuildRoute(&$query)
{

	$app = Factory::getApplication();
	$router = new PhocaPhotoRouter($app, $app->getMenu());

	return $router->build($query);
}


function PhocaPhotoParseRoute($segments)
{


	$app = Factory::getApplication();
	$router = new PhocaPhotoRouter($app, $app->getMenu());

	return $router->parse($segments);
}

