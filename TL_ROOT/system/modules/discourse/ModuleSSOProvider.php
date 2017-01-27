<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Discourse SSO Provider module
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
 * @package    Discourse
 * @license    AGPLv3 (GNU Affero GPL v3.0)
 * @filesource
 */


require_once(TL_ROOT . '/plugins/Discourse/SSOProviderPayload.php');

/**
 * Class ModuleSSOProvider
 *
 * Module to provide user authentication for Discourse instances against the
 * user database of a Contao instance.
 * @copyright  Florian Bender 2015
 * @author     Florian Bender <fb+git@quantumedia.de>
 * @package    Discourse
 */
class ModuleSSOProvider extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate;


	/**
	 * Validate the current user and redirect (if permissions allow).
	 * @return string
	 */
	public function generate()
	{
		
		// Show placeholder in Backend
		if (TL_MODE == 'BE')
		{
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
		$strSSOPayload   = urldecode($_GET['sso']);
		$strSSOSignature = $_GET['sig'];
		
		// TODO: Redirect to current URL (without sso/sig parameters) if user is not logged in or no payload was provided
		if (!FE_USER_LOGGED_IN || empty($strSSOPayload) || empty($strSSOSignature)) {
			return '';
		}
		
		$objSSOPayload = new \fbender\Discourse\SSOProviderPayload($GLOBALS['TL_CONFIG']['discourseSSOSecret']);
		$objSSOPayload->parseChallengePayload($strSSOPayload, $strSSOSignature); // TODO: catch exception?
		
		$this->import('FrontendUser', 'User');
		
		// TODO: add moderator group support
		// optional values: 'username', (full) 'name', 'avatar_url',
		//  'require_activation', 'custom.*' (custom fields), etc.
		$arrParameters = array(
			'name'      => $this->User->firstname.' '.$this->User->lastname,
			// 'avatar_url' => $this->User->portrait,
			// 'custom.xyz' => '', // see Discourse Plugins & Discourse, Admin, Customize, User Fields; https://meta.discourse.org/t/custom-user-fields-for-plugins/14956
			// 'admin'     => 0,
			'moderator' => 0
		);
		// TODO: reduce amount of data being logged?
		$this->log('User "' . $this->User->username . '" used SSO ('.json_encode($arrParameters).')', get_class($this) . ' generate()', TL_ACCESS);
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
	 * Generate module
	 */
	protected function compile()
	{
		return;
	}
	
}


#EOF
