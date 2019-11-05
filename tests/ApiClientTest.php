<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

use PleskX\Api\Client;
use PleskX\Api\Client\Exception;

class ApiClientTest extends TestCase
{

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1005
     */
    public function testWrongProtocol()
    {
        $packet = $this->client->getPacket('100.0.0');
        $packet->addChild('server')->addChild('get_protos');
        $this->client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1014
     */
    public function testUnknownOperator()
    {
        $packet = $this->client->getPacket();
        $packet->addChild('unknown');
        $this->client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1014
     */
    public function testInvalidXmlRequest()
    {
        $this->client->request('<packet><wrongly formatted xml</packet>');
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 1001
     */
    public function testInvalidCredentials()
    {
        $host = getenv('REMOTE_HOST');
        $client = new Client($host);
        $client->setCredentials('bad-login', 'bad-password');
        $packet = $this->client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $client->request($packet);
    }

    /**
     * @expectedException \PleskX\Api\Exception
     * @expectedExceptionCode 11003
     */
    public function testInvalidSecretKey()
    {
        $host = getenv('REMOTE_HOST');
        $client = new Client($host);
        $client->setSecretKey('bad-key');
        $packet = $this->client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $client->request($packet);
    }

    public function testLatestMajorProtocol()
    {
        $packet = $this->client->getPacket('1.6');
        $packet->addChild('server')->addChild('get_protos');
        $this->client->request($packet);
    }

    public function testLatestMinorProtocol()
    {
        $packet = $this->client->getPacket('1.6.5');
        $packet->addChild('server')->addChild('get_protos');
        $this->client->request($packet);
    }

    public function testRequestShortSyntax()
    {
        $response = $this->client->request('server.get.gen_info');
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testOperatorPlainRequest()
    {
        $response = $this->client->server()->request('get.gen_info');
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
        $this->assertEquals(36, strlen($response->getValue('server_guid')));
    }

    public function testRequestArraySyntax()
    {
        $response = $this->client->request([
            'server' => [
                'get' => [
                    'gen_info' => '',
                ],
            ],
        ]);
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testOperatorArraySyntax()
    {
        $response = $this->client->server()->request(['get' => ['gen_info' => '']]);
        $this->assertGreaterThan(0, strlen($response->gen_info->server_name));
    }

    public function testMultiRequest()
    {
        $responses = $this->client->multiRequest([
            'server.get_protos',
            'server.get.gen_info',
        ]);

        $this->assertCount(2, $responses);

        $protos = (array)$responses[0]->protos->proto;
        $generalInfo = $responses[1];

        $this->assertContains('1.6.6.0', $protos);
        $this->assertGreaterThan(0, strlen($generalInfo->gen_info->server_name));
    }

    /**
     * @expectedException Exception
     */
    public function testConnectionError()
    {
        $client = new Client('invalid-host.dom');
        $client->server()->getProtos();
    }
}
