<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api;

use SimpleXMLElement;

/**
 * Client for Plesk API-RPC
 */
class Client
{
    const RESPONSE_SHORT = 1;
    const RESPONSE_FULL = 2;

    protected $_host;
    protected $_port;
    protected $_protocol;
    protected $_login;
    protected $_password;
    protected $_secretKey;
    protected $_version = '';

    protected static $_isExecutionsLogEnabled = false;
    protected static $_executionLog = [];

    protected $_operatorsCache = [];

    /**
     * Create client
     *
     * @param string $host
     * @param int $port
     * @param string $protocol
     */
    public function __construct($host, $port = 8443, $protocol = 'https')
    {
        $this->_host = $host;
        $this->_port = $port;
        $this->_protocol = $protocol;
    }

    /**
     * Setup credentials for authentication
     *
     * @param string $login
     * @param string $password
     */
    public function setCredentials($login, $password)
    {
        $this->_login = $login;
        $this->_password = $password;
    }

    /**
     * Define secret key for alternative authentication
     *
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->_secretKey = $secretKey;
    }

    /**
     * Set default version for requests
     *
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * Retrieve XML template for packet
     *
     * @param string|null $version
     * @return SimpleXMLElement
     */
    public function getPacket($version = null)
    {
        $protocolVersion = !is_null($version) ? $version : $this->_version;
        $content = "<?xml version='1.0' encoding='UTF-8' ?>";
        $content .= "<packet" . ("" === $protocolVersion ? "" : " version='$protocolVersion'") . "/>";
        return new SimpleXMLElement($content);
    }


    /**
     * Recursive function that transforms array to XML
     *
     * @param array $attributes
     * @param SimpleXMLElement $xmlData
     * @return SimpleXMLElement
     */
    protected function createXml($data, &$xmlData)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {
                    $key = array_keys($data[$key])[0];
                    $subnode = $xmlData->addChild($key);
                    foreach ($value[$key] as $k => $v) {
                        if (is_array($v)) {
                            $this->createXml($v, $subnode);
                        } else {
                            $subnode->addChild("$k", htmlspecialchars("$v"));
                        }
                    }
                } else {
                    $subnode = $xmlData->addChild($key);
                    $this->createXml($value, $subnode);
                }
            } else {
                $xmlData->addChild("$key", htmlspecialchars("$value"));
            }
        }

        return $xmlData;
    }

    /**
     * Gen XML Request by Array
     *
     * @param array $attributes
     * @param string|null $version
     * @return SimpleXMLElement
     */
    public function genRequestXml($attributes, $version = null)
    {
        $res = $this->getPacket($version);
        $this->createXml($attributes, $res);
        return $res;
    }

    /**
     * Perform API request
     *
     * @param string|array|SimpleXMLElement $request
     * @param int $mode
     * @return XmlResponse
     * @throws \Exception
     */
    public function request($request, $mode = self::RESPONSE_SHORT)
    {
        if ($request instanceof SimpleXMLElement) {
            $request = $request->asXml();
        } else {
            $xml = $this->getPacket();

            if (is_array($request)) {
                $request = $this->arrayToXml($request, $xml)->asXML();
            } else {
                if (preg_match('/^[a-z]/', $request)) {
                    $request = $this->expandRequestShortSyntax($request, $xml);
                }
            }
        }

        $xml = $this->performHttpRequest($request);

        return (self::RESPONSE_FULL == $mode) ? $xml : $xml->xpath('//result')[0];
    }

    /**
     * Perform HTTP request to end-point
     *
     * @param string $request
     * @return XmlResponse
     * @throws \Exception
     */
    protected function performHttpRequest($request)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, "$this->_protocol://$this->_host:$this->_port/enterprise/control/agent.php");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($curl);

        if (false === $result) {
            throw new Client\Exception(curl_error($curl), curl_errno($curl));
        }

        if (self::$_isExecutionsLogEnabled) {
            self::$_executionLog[] = [
                'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
                'request' => $request,
                'response' => $result,
            ];
        }

        curl_close($curl);

        $xml = new XmlResponse($result);
        $this->verifyResponse($xml);

        return $xml;
    }

    /**
     * Perform multiple API requests using single HTTP request
     *
     * @param $requests
     * @param int $mode
     * @return array
     */
    public function multiRequest($requests, $mode = self::RESPONSE_SHORT)
    {

        $requestXml = $this->getPacket();

        foreach ($requests as $request) {
            if ($request instanceof SimpleXMLElement) {
                // TODO: implement
            } else {
                if (is_array($request)) {
                    $request = $this->arrayToXml($request, $requestXml)->asXML();
                } else {
                    if (preg_match('/^[a-z]/', $request)) {
                        $this->expandRequestShortSyntax($request, $requestXml);
                    }
                }
            }
            $responses[] = $this->request($request);
        }

        $responseXml = $this->performHttpRequest($requestXml->asXML());

        $responses = [];
        foreach ($responseXml->children() as $childNode) {
            $xml = $this->getPacket();
            $dom = dom_import_simplexml($xml)->ownerDocument;

            $childDomNode = dom_import_simplexml($childNode);
            $childDomNode = $dom->importNode($childDomNode, true);
            $dom->documentElement->appendChild($childDomNode);

            $response = simplexml_load_string($dom->saveXML());
            $responses[] = (self::RESPONSE_FULL == $mode) ? $response : $response->xpath('//result')[0];
        }

        return $responses;
    }

    /**
     * Retrieve list of headers needed for request
     *
     * @return array
     */
    protected function getHeaders()
    {
        $headers = array(
            "Content-Type: text/xml",
            "HTTP_PRETTY_PRINT: TRUE",
        );

        if ($this->_secretKey) {
            $headers[] = "KEY: $this->_secretKey";
        } else {
            $headers[] = "HTTP_AUTH_LOGIN: $this->_login";
            $headers[] = "HTTP_AUTH_PASSWD: $this->_password";
        }

        return $headers;
    }

    /**
     * Enable or disable execution log
     *
     * @param bool $enable
     */
    public static function enableExecutionLog($enable = true)
    {
        self::$_isExecutionsLogEnabled = $enable;
    }

    /**
     * Retrieve execution log
     *
     * @return array
     */
    public static function getExecutionLog()
    {
        return self::$_executionLog;
    }

    /**
     * Verify that response does not contain errors
     *
     * @param XmlResponse $xml
     * @throws \Exception
     */
    protected function verifyResponse($xml)
    {
        if ($xml->system && $xml->system->status && 'error' == (string)$xml->system->status) {
            throw new Exception((string)$xml->system->errtext, (int)$xml->system->errcode);
        }

        if ($xml->xpath('//status[text()="error"]') && $xml->xpath('//errcode') && $xml->xpath('//errtext')) {
            $errorCode = (int)$xml->xpath('//errcode')[0];
            $errorMessage = (string)$xml->xpath('//errtext')[0];
            throw new Exception($errorMessage, $errorCode);
        }
    }

    /**
     * Expand short syntax (some.method.call) into full XML representation
     *
     * @param string $request
     * @param SimpleXMLElement $xml
     * @return string
     */
    protected function expandRequestShortSyntax($request, SimpleXMLElement $xml)
    {
        $parts = explode('.', $request);
        $node = $xml;

        foreach ($parts as $part) {
            @list($name, $value) = explode('=', $part);
            $node = $node->addChild($name, $value);
        }

        return $xml->asXML();
    }

    /**
     * Convert array to XML representation
     *
     * @param array $array
     * @param SimpleXMLElement $xml
     * @return SimpleXMLElement
     */
    protected function arrayToXml(array $array, SimpleXMLElement $xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayToXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml;
    }

    /**
     * @param string $name
     * @return \PleskX\Api\Operator
     */
    protected function getOperator($name)
    {
        if (!isset($this->_operatorsCache[$name])) {
            $className = '\\PleskX\\Api\\Operator\\' . $name;
            $this->_operatorsCache[$name] = new $className($this);
        }

        return $this->_operatorsCache[$name];
    }

    /**
     * @return Operator\Server
     */
    public function server()
    {
        return $this->getOperator('Server');
    }

    /**
     * @return Operator\Customer
     */
    public function customer()
    {
        return $this->getOperator('Customer');
    }

    /**
     * @return Operator\Webspace
     */
    public function webspace()
    {
        return $this->getOperator('Webspace');
    }

    /**
     * @return Operator\Subdomain
     */
    public function subdomain()
    {
        return $this->getOperator('Subdomain');
    }

    /**
     * @return Operator\Dns
     */
    public function dns()
    {
        return $this->getOperator('Dns');
    }

    /**
     * @return Operator\DatabaseServer
     */
    public function databaseServer()
    {
        return $this->getOperator('DatabaseServer');
    }

    /**
     * @return Operator\Mail
     */
    public function mail()
    {
        return $this->getOperator('Mail');
    }

    /**
     * @return Operator\Migration
     */
    public function migration()
    {
        return $this->getOperator('Migration');
    }

    /**
     * @return Operator\Certificate
     */
    public function certificate()
    {
        return $this->getOperator('Certificate');
    }

    /**
     * @return Operator\SiteAlias
     */
    public function siteAlias()
    {
        return $this->getOperator('SiteAlias');
    }

    /**
     * @return Operator\Ip
     */
    public function ip()
    {
        return $this->getOperator('Ip');
    }

    /**
     * @return Operator\EventLog
     */
    public function eventLog()
    {
        return $this->getOperator('EventLog');
    }

    /**
     * @return Operator\SpamFilter
     */
    public function spamFilter()
    {
        return $this->getOperator('SpamFilter');
    }

    /**
     * @return Operator\SecretKey
     */
    public function secretKey()
    {
        return $this->getOperator('SecretKey');
    }

    /**
     * @return Operator\Ui
     */
    public function ui()
    {
        return $this->getOperator('Ui');
    }

    /**
     * @return Operator\ServicePlan
     */
    public function servicePlan()
    {
        return $this->getOperator('ServicePlan');
    }

    /**
     * @return Operator\WebUser
     */
    public function webUser()
    {
        return $this->getOperator('WebUser');
    }

    /**
     * @return Operator\MailList
     */
    public function mailList()
    {
        return $this->getOperator('MailList');
    }

    /**
     * @return Operator\VirtualDirectory
     */
    public function virtualDirectory()
    {
        return $this->getOperator('VirtualDirectory');
    }

    /**
     * @return Operator\Database
     */
    public function database()
    {
        return $this->getOperator('Database');
    }

    /**
     * @return Operator\FtpUser
     */
    public function ftpUser()
    {
        return $this->getOperator('FtpUser');
    }

    /**
     * @return Operator\Session
     */
    public function session()
    {
        return $this->getOperator('Session');
    }

    /**
     * @return Operator\Updater
     */
    public function updater()
    {
        return $this->getOperator('Updater');
    }

    /**
     * @return Operator\Locale
     */
    public function locale()
    {
        return $this->getOperator('Locale');
    }

    /**
     * @return Operator\LogRotation
     */
    public function logRotation()
    {
        return $this->getOperator('LogRotation');
    }

    /**
     * @return Operator\BackupManager
     */
    public function backupManager()
    {
        return $this->getOperator('BackupManager');
    }

    /**
     * @return Operator\Sso
     */
    public function sso()
    {
        return $this->getOperator('Sso');
    }

    /**
     * @return Operator\ProtectedDirectory
     */
    public function protectedDirectory()
    {
        return $this->getOperator('ProtectedDirectory');
    }

    /**
     * @return Operator\Reseller
     */
    public function reseller()
    {
        return $this->getOperator('Reseller');
    }

    /**
     * @return Operator\ResellerPlan
     */
    public function resellerPlan()
    {
        return $this->getOperator('ResellerPlan');
    }

    /**
     * @return Operator\Aps
     */
    public function aps()
    {
        return $this->getOperator('Aps');
    }

    /**
     * @return Operator\ServicePlanAddon
     */
    public function servicePlanAddon()
    {
        return $this->getOperator('ServicePlanAddon');
    }

    /**
     * @return Operator\Site
     */
    public function site()
    {
        return $this->getOperator('Site');
    }

    /**
     * @return Operator\User
     */
    public function user()
    {
        return $this->getOperator('User');
    }

    /**
     * @return Operator\Role
     */
    public function role()
    {
        return $this->getOperator('Role');
    }

    /**
     * @return Operator\BusinessLogicUpgrade
     */
    public function businessLogicUpgrade()
    {
        return $this->getOperator('BusinessLogicUpgrade');
    }

    /**
     * @return Operator\Webmail
     */
    public function webmail()
    {
        return $this->getOperator('Webmail');
    }

    /**
     * @return Operator\PlanItem
     */
    public function planItem()
    {
        return $this->getOperator('PlanItem');
    }

    /**
     * @return Operator\Sitebuilder
     */
    public function sitebuilder()
    {
        return $this->getOperator('Sitebuilder');
    }

    /**
     * @return Operator\ServiceNode
     */
    public function serviceNode()
    {
        return $this->getOperator('ServiceNode');
    }

    /**
     * @return Operator\IpBan
     */
    public function ipBan()
    {
        return $this->getOperator('IpBan');
    }

    /**
     * @return Operator\WpInstance
     */
    public function wpInstance()
    {
        return $this->getOperator('WpInstance');
    }

    /**
     * @return Operator\PHPHandler
     */
    public function phpHandler()
    {
        return $this->getOperator('PHPHandler');
    }
}
