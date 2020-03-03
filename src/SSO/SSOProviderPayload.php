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

// based on github.com/cviebrock/discourse-php
// @see     https://raw.githubusercontent.com/cviebrock/discourse-php/master/src/SSOHelper.php
// @license (TBD)

namespace Craffft\ContaoDiscourseSSOBundle\SSO;

class SSOProviderPayload
{
    /**
     * Endpoint which receives SSO response; add host and query like this:
     *      http://discourse_site.tld{API_ENDPOINT}?sso={PAYLOAD}&sig={SIG}
     * @var string
     */
    const API_ENDPOINT = '/session/sso_login';

    /**
     * Secret used for signing payload data
     * @var string
     */
    private $strSignatureSecret = '';

    /**
     * Nonce retrieved from challenge payload
     * @var string
     */
    protected $strPayloadNonce = '';

    /**
     * Set signature secret
     * @param string $strSecret Shared secret used for the payload signature
     */
    public function setSignatureSecret(string $strSecret)
    {
        $this->strSignatureSecret = $strSecret;
    }

    /**
     * Check signature (and thus integrity) of payload
     * @return boolean
     */
    public function isPayloadValid($strPayload, $strSignature)
    {
        return ($this->getPayloadSignature($strPayload) === $strSignature);
    }

    /**
     * Validate and parse payload as well as retrieve and store nonce
     * @param  string $strPayload Challenge payload (must be urldecode()d!)
     * @param  string $strSignature The payload's signature
     * @return true
     * @throws \Exception
     */
    public function parseChallengePayload($strPayload, $strSignature)
    {
        if (!$this->isPayloadValid($strPayload, $strSignature)) {
            throw new \Exception('Payload could not be validated against signature (Payload: "' . $strPayload . '", Signature: "' . $strSignature . '")');
        }
        // parse payload
        $arrPayloadData = array();
        parse_str(base64_decode($strPayload), $arrPayloadData);
        // retrieve nonce
        if (!array_key_exists('nonce', $arrPayloadData)) {
            throw new \Exception('Invalid payload: Nonce not found');
        }
        $this->strPayloadNonce = $arrPayloadData['nonce'];

        return true;
    }

    /**
     * Generate and return response payload with signature ready for http_build_query()
     * @see   self::generateResponsePayload
     * @param string $strUserId (External) user ID
     * @param string $strUserEmail E-mail address of user
     * @param array $arrOptionalParameters More parameters to include in payload
     * @todo  Use func_get_args resp. http://php.net/manual/functions.arguments.html#functions.variable-arg-list
     */
    public function getResponseDataForUser($strUserId, $strUserEmail, $arrOptionalParameters = array())
    {
        $arrPayloadData = array(
            // 'nonce'       => $this->strPayloadNonce,
            'external_id' => $strUserId,
            'email'       => $strUserEmail
        );
        $arrPayloadData = array_merge($arrPayloadData, $arrOptionalParameters);
        $strPayload = $this->generateResponsePayload($arrPayloadData);

        return array(
            'sso' => $strPayload,
            'sig' => $this->getPayloadSignature($strPayload)
        );
    }

    /**
     * Generate and return response payload using nonce from challenge payload
     * @param  array $arrPayloadParameters Parameters to include in payload
     * @return string
     * @todo   Check input array for required / valid values?
     * @todo   Consider making this protected
     */
    public function generateResponsePayload($arrPayloadParameters)
    {
        // $arrPayloadParameters required values: nonce, email, external_id
        // … optional values: 'username', (full) 'name', 'avatar_url',
        //  'require_activation', 'custom.*' (custom fields), etc.
        // augment payload data with nonce
        $arrPayloadParameters['nonce'] = $this->strPayloadNonce;

        // create & return payload string
        return base64_encode(http_build_query($arrPayloadParameters));
    }

    /**
     * Return signature of payload using secret
     * @param  string $strPayload
     * @return string
     * @todo   Consider making this protected
     */
    public function getPayloadSignature($strPayload)
    {
        return hash_hmac('sha256', $strPayload, $this->strSignatureSecret);
    }

}
