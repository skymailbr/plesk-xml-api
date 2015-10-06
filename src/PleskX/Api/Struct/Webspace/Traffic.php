<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Traffic extends \PleskX\Api\Struct
{
    /** @var string **/
    public $sinceDate;

    /** @var string **/
    public $toDate;

    /** @var integer **/
    public $httpIn = 0;

    /** @var integer **/
    public $httpOut = 0;

    /** @var integer **/
    public $ftpIn = 0;

    /** @var integer **/
    public $ftpOut = 0;

    public function __construct($apiResponse)
    {
        $this->_trafficScalarProperties($apiResponse);
    }

    /**
     * Initialize list of scalar properties of traffic response
     *
     * @param \SimpleXMLElement $apiResponse
     * @throws \Exception
     */
    protected function _trafficScalarProperties( $apiResponse ) {

        $first = $apiResponse->xpath('//traffic');
        if ( ! $first ) return;

        $this->sinceDate = $first[0]->date;
        $this->toDate = $apiResponse->xpath('//traffic[last()]')[0]->date;
        foreach( $apiResponse as $i => $traffic ) {
            $this->httpIn += $traffic->http_in;
            $this->httpOut += $traffic->http_out;
            $this->ftpIn += $traffic->ftp_in;
            $this->ftpOUt += $traffic->ftp_out;
        }
    }

}