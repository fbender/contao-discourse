<?php

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
 * @author     Daniel Kiesel <https://github.com/iCodr8>
 * @package    Discourse
 * @license    LGPL
 * @filesource
 */

/**
 * Extension folder
 */
$GLOBALS['TL_LANG']['MOD']['discourse'] = array('Discourse-Anbindung');

/**
 * Front end modules
 */
$GLOBALS['TL_LANG']['FMD']['discourseSSOProvider'] = array('Discourse SSO Provider', 'Dieses Modul ermöglicht einen Single Sign-On von einer Discourse-Installation. Nach erfolgreicher Authentisierung wir der Nutzer auf den Discourse Host (s. Contao Einstellungen) weitergleitet. Das Modul erzeugt keine Ausgabe (ähnlich dem "Logout"-Modul).');
