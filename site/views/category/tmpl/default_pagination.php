<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

$this->t['action'] = str_replace('&amp;', '&', $this->t['action']);
$this->t['action'] = htmlspecialchars($this->t['action']);

echo '<div class="clearfix"></div>';
echo '<form action="'.$this->t['action'].'" method="post" name="adminForm">'. "\n";
echo '<div class="pagination ph-ph-pagination">';
if ($this->t['p']->get('show_pagination')) {
	//echo '<div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center;padding:0;margin:0">';

	echo '<div class="page-links">'. $this->t['pagination']->getPagesLinks() . '</div>';

	echo '<div class="limit-box">';
	if ($this->t['p']->get('show_pagination_limit')) {
		echo '<div>' . Text::_('COM_PHOCAPHOTO_DISPLAY_NUM') .'</div><div>' .$this->t['pagination']->getLimitBox() . '</div>';
	}

	echo '<div class="pages-counter">';
	echo ''.$this->t['pagination']->getPagesCounter();
	echo '</div>';

	echo '</div>';
	//echo '</div>';
}
echo '</div>';
echo HTMLHelper::_( 'form.token' );
echo '</form>';
?>
