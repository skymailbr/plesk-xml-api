<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class CertificateTest extends TestCase
{

    public function testGenerate()
    {
        $certificate = $this->client->certificate()->generate([
            'bits' => 2048,
            'country' => 'RU',
            'state' => 'NSO',
            'location' => 'Novosibirsk',
            'company' => 'Parallels',
            'email' => 'info@example.com',
            'name' => 'example.com'
        ]);
        $this->assertGreaterThan(0, strlen($certificate->request));
        $this->assertStringStartsWith('-----BEGIN CERTIFICATE REQUEST-----', $certificate->request);
        $this->assertGreaterThan(0, strlen($certificate->privateKey));
        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $certificate->privateKey);
    }
}
