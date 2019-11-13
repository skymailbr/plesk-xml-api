<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskXTest;

class SiteAliasTest extends TestCase
{
    /** @var \PleskX\Api\Struct\Webspace\Info */
    private static $webspace;

    /** @var string */
    private static $webspaceName;

    /** @var string */
    private $aliasName = 'example-test-alias.dom';

    /** @var string */
    private $aliasNewName = 'example-new-name-alias.dom';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::_createWebspace();
        $webSpaceInfo = static::$_client->webspace()->get('id', static::$webspace->id);
        static::$webspaceName = $webSpaceInfo->name;
    }

    /**
     * @return \PleskX\Api\Struct\SiteAlias\Info
     * @throws \Exception
     */
    private function createSiteAlias()
    {
        return static::$_client->siteAlias()->create([
            'site-id' => static::$webspace->id,
            'name' => $this->aliasName
        ]);
    }

    /**
     * @throws \Exception
     */
    public function testGetSearchBySiteName()
    {
        $this->createSiteAlias();
        $siteAlias = static::$_client->siteAlias()->get('name', $this->aliasName);
        $this->assertEquals($this->aliasName, $siteAlias->name);
        static::$_client->siteAlias()->delete('id', $siteAlias->id);
    }

    /**
     * @throws \Exception
     */
    public function testCreate()
    {
        $siteAlias = $this->createSiteAlias();
        $this->assertIsInt($siteAlias->id);
        $this->assertGreaterThan(0, $siteAlias->id);
        static::$_client->siteAlias()->delete('id', $siteAlias->id);
    }

    /**
     * @throws \Exception
     */
    public function testDelete()
    {
        $siteAlias = $this->createSiteAlias();
        $result = static::$_client->siteAlias()->delete('id', $siteAlias->id);
        $this->assertTrue($result);
    }

    /**
     * @throws \Exception
     */
    public function testRename()
    {
        $siteAlias = $this->createSiteAlias();
        $result = static::$_client->siteAlias()->rename('id', $siteAlias->id, $this->aliasNewName);
        $this->assertTrue($result);
        $siteAlias = static::$_client->siteAlias()->get('id', $siteAlias->id);
        $this->assertEquals($this->aliasNewName, $siteAlias->name);
        static::$_client->siteAlias()->delete('id', $siteAlias->id);
    }
}
