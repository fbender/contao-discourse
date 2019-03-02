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

namespace Craffft\ContaoDiscourseSSOBundle\FrontendModule;

use Contao\BackendTemplate;
use Contao\Module;

/**
 * Class ModuleSSOProvider
 *
 * Module to provide user authentication for Discourse instances against the
 * user database of a Contao instance.
 * @copyright  Florian Bender 2015
 * @author     Florian Bender <fb+git@quantumedia.de>
 * @author     Daniel Kiesel <https://github.com/iCodr8>
 * @package    Discourse
 */
class ModuleSSOProvider extends Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = '';

    /**
     * Validate the current user and redirect (if permissions allow).
     * @return string
     */
    public function generate()
    {
        // Show placeholder in Backend
        if (TL_MODE == 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### DISCOURSE SSO PROVIDER MODULE ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        // Return nothing if necessary parameters were not provided
        if (!isset($_GET['sso']) || !isset($_GET['sig'])) {
            return ''; // TODO: return error? log??
        }

        // FIX: use raw data instead of sanitized data from Contao Input class
        $strSSOPayload = urldecode($_GET['sso']);
        $strSSOSignature = $_GET['sig'];

        // TODO: Redirect to current URL (without sso/sig parameters) if user is not logged in or no payload was provided
        if (!FE_USER_LOGGED_IN || empty($strSSOPayload) || empty($strSSOSignature)) {
            return '';
        }

        $container = \System::getContainer();
        /** @var SSOProviderPayload $objSSOPayload */
        $objSSOPayload = $container->get('craffft.sso.sso_provider_payload');
        $objSSOPayload->setSignatureSecret($GLOBALS['TL_CONFIG']['discourseSSOSecret']);
        $objSSOPayload->parseChallengePayload($strSSOPayload, $strSSOSignature); // TODO: catch exception?

        $this->import('FrontendUser', 'User');

        // TODO: add moderator group support
        // optional values: 'username', (full) 'name', 'avatar_url',
        //  'require_activation', 'custom.*' (custom fields), etc.
        $arrParameters = array(
            'name'      => $this->User->firstname . ' ' . $this->User->lastname,
            // 'avatar_url' => $this->User->portrait,
            // 'custom.xyz' => '', // see Discourse Plugins & Discourse, Admin, Customize, User Fields; https://meta.discourse.org/t/custom-user-fields-for-plugins/14956
            // 'admin'     => 0,
            'moderator' => 0
        );
        // TODO: reduce amount of data being logged?
        $this->log('User "' . $this->User->username . '" used SSO (' . json_encode($arrParameters) . ')',
            get_class($this) . ' generate()', TL_ACCESS);
        $arrResponseData = $objSSOPayload->getResponseDataForUser($this->User->id, $this->User->email, $arrParameters);

        // create redirect URL
        $arrDiscourseHostParts = parse_url($GLOBALS['TL_CONFIG']['discourseSSOHost']);

        if ($arrDiscourseHostParts === false || !isset($arrDiscourseHostParts['scheme']) || !isset($arrDiscourseHostParts['host'])) {
            throw new Exception("Invalid setting: 'discourseSSOHost' (must be a valid URL including protocol)");
        }

        $strDiscourseSSOEndpoint = $arrDiscourseHostParts['scheme'] . '://' . $arrDiscourseHostParts['host'];
        $strDiscourseSSOEndpoint .= $objSSOPayload::API_ENDPOINT;
        $strDiscourseSSOEndpoint .= '?' . http_build_query($arrResponseData);

        $this->redirect($strDiscourseSSOEndpoint);

        return '';
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        return;
    }
}
