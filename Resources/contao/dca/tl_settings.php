<?php

/**
 * Discourse module for Contao Open Source CMS 2.x
 * Copyright (C) 2015 Florian Bender <fb+git@quantumedia.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Florian Bender 2015
 * @author     Florian Bender <fb+git@quantumedia.de>
 * @author     Daniel Kiesel <https://github.com/iCodr8>
 * @package    Discourse
 * @license    AGPLv3 (GNU Affero GPL v3.0)
 * @filesource
 */

/*
 * dca: tl_settings
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{discourse_legend},discourseSSOHost,discourseSSOSecret';

$GLOBALS['TL_DCA']['tl_settings']['fields']['discourseSSOHost'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['discourseSSOHost'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'tl_class'=>'w50'),
    'save_callback'           => array(
        array('tl_settings_discourse', 'validateURL')
    )
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['discourseSSOSecret'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['discourseSSOSecret'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('decodeEntities'=>false, 'tl_class'=>'w50')
);


class tl_settings_discourse extends tl_settings
{
    public function validateURL($varValue)
    {
        $varValue = $this->idnaEncodeUrl($varValue); // method of System class
        if (filter_var($varValue, FILTER_VALIDATE_URL) === false) {
            throw new Exception('Not a valid URL: ' + $varValue);
        }

        return $varValue;
    }
}
