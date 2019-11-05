<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class LocaleTest extends TestCase
{

    public function testGet()
    {
        $locales = $this->client->locale()->get();
        $this->assertGreaterThan(0, count($locales));

        $locale = $locales['en-US'];
        $this->assertEquals('en-US', $locale->id);
    }

    public function testGetById()
    {
        $locale = $this->client->locale()->get('en-US');
        $this->assertEquals('en-US', $locale->id);
    }
}
