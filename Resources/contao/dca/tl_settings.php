<?php

/*
 * This file is part of the Craffft Discourse SSO Bundle.
 *
 * (c) Florian Bender <fb+git@quantumedia.de>
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
