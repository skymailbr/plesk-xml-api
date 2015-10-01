<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

class SiteTest extends TestCase
{

    private $n = 'example-test-other.dom';

    /**
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function _createWebspace()
    {
        $ips = $this->_client->ip()->get();
        $ipInfo = reset($ips);
        return $this->_client->webspace()->create([
            'name' => $this->n,
            'ip_address' => $ipInfo->ipAddress,
        ]);
    }

    public function testGet()
    {
        $ws = $this->_createWebspace();

        $siteInfo = $this->_client->site()->get('id', $ws->id);
        $this->assertEquals($this->n, $siteInfo->name);

        $this->_client->webspace()->delete('id', $ws->id);

    }

}
