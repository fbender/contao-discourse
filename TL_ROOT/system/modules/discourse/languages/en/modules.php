<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Discourse module for Contao Open Source CMS 2.x
 * Copyright (C) 2015 Florian Bender <fb+git@quantumedia.de>
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Florian Bender 2015
 * @author     Florian Bender <fb+git@quantumedia.de>
 * @package    Discourse
 * @license    LGPL
 * @filesource
 */


/**
 * Extension folder
 */
$GLOBALS['TL_LANG']['MOD']['discourse'] = array('Discourse Connector');


/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['discourseSSOProvider'] = array('Discourse SSO Provider', 'This module enables Single Sign-On of a Discourse installation. Users will be redirected to the Discourse Host (see Contao Settings) after successful authentication. This module does not produce any output (similar to the "Logout" module).');


#EOF
