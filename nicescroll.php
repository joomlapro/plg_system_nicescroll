<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Nicescroll
 * @copyright   Copyright (C) 2013 AtomTech, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Joomla Nicescroll plugin.
 *
 * @package     Joomla.Plugin
 * @subpackage  System.Nicescroll
 * @since       3.1
 */
class PlgSystemNicescroll extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @param   object  &$subject  The object to observe.
	 * @param   array   $config    An array that holds the plugin configuration.
	 *
	 * @access  protected
	 * @since   3.1
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->loadLanguage();

		// Get the application.
		$app = JFactory::getApplication();

		// Save the syntax for later use.
		if ($app->isAdmin())
		{
			$app->setUserState('editor.source.syntax', 'css');
		}
	}

	/**
	 * Method to catch the onAfterDispatch event.
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.1
	 */
	public function onAfterDispatch()
	{
		// Check that we are in the site application.
		if (JFactory::getApplication()->isAdmin())
		{
			return true;
		}

		// Get the document object.
		$doc = JFactory::getDocument();

		// Add Stylesheet.
		if ($custom_css = trim($this->params->get('custom_css')))
		{
			$doc->addStyleDeclaration($custom_css);
		}
		else
		{
			JHtml::stylesheet('plg_system_nicescroll/nicescroll.css', false, true, false);
		}

		// Add JavaScript Frameworks.
		JHtml::_('jquery.framework');

		if ($this->params->get('minified'))
		{
			JHtml::script('plg_system_nicescroll/jquery.nicescroll.min.js', false, true);
		}
		else
		{
			JHtml::script('plg_system_nicescroll/jquery.nicescroll.js', false, true);
		}

		// Initialiase variables.
		$cursorColor        = $this->params->get('cursor_color', '#999999');
		$cursorOpacityMax   = $this->params->get('cursor_opacity_max', '1');
		$cursorWidth        = $this->params->get('cursor_width', '6');
		$cursorBorder       = $this->params->get('cursor_border', 'none');
		$cursorBorderRadius = $this->params->get('cursor_border_radius', '7px');
		$touchBehavior      = $this->params->get('touch_behavior', 0);
		$touchBehavior      = $touchBehavior ? 'true' : 'false';

		// Build the script.
		$script = array();
		$script[] = 'jQuery(document).ready(function() {';
		$script[] = '	jQuery("html").niceScroll({';
		$script[] = '		cursorcolor: "' . $cursorColor . '",';
		$script[] = '		cursoropacitymax: "' . $cursorOpacityMax . '",';
		$script[] = '		cursorwidth: "' . $cursorWidth . '",';
		$script[] = '		cursorborder: "' . $cursorBorder . '",';
		$script[] = '		cursorborderradius: "' . $cursorBorderRadius . '",';
		$script[] = '		touchbehavior: ' . $touchBehavior;
		$script[] = '	})';
		$script[] = '});';

		// Add the script to the document head.
		$doc->addScriptDeclaration(implode("\n", $script));

		return true;
	}
}
