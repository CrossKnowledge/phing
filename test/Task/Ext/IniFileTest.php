<?php

namespace Phing\Test\Task\Ext;

use Phing\Test\Helper\AbstractBuildFileTest;

class IniFileTest extends AbstractBuildFileTest
{
    private $inifiletestdir;

    public function setUp()
    {
        $this->configureProject(PHING_TEST_BASE . "/etc/tasks/ext/inifile/inifile.xml");
        $this->inifiletestdir = PHING_TEST_BASE . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'inifile';
        $this->executeTarget("setup");
    }

    public function tearDown()
    {
        $this->executeTarget("clean");
    }

    /**
     * @expectedException \Phing\Exception\BuildException
     * @expectedExceptionMessage Neither source nor dest is set
     */
    public function testNoSourceOrDestSet()
    {
        $this->executeTarget('noSourceOrDestSet');
    }

    /**
     * @expectedException \Phing\Exception\BuildException
     * @expectedExceptionMessage doesnotexist.ini does not exist
     */
    public function testNonexistingSourceOnly()
    {
        $this->executeTarget('nonexistingSourceOnly');
    }

    /**
     * @expectedException \Phing\Exception\BuildException
     * @expectedExceptionMessage doesnotexist.ini does not exist
     */
    public function testNonexistingDestOnly()
    {
        $this->executeTarget('nonexistingDestOnly');
    }

    /**
     * @expectedException \Phing\Exception\BuildException
     * @expectedExceptionMessage sourcedoesnotexist.ini does not exist
     */
    public function testNonexistingDestAndSource()
    {
        $this->executeTarget('nonexistingDestAndSource');
    }

    public function testExistingSource()
    {
        $fill = ["[test]\n", "; a comment\n", "foo=bar\n"];
        file_put_contents($this->inifiletestdir . "/source.ini", $fill);
        $this->executeTarget("existingSource");

        $this->assertInLogs('Read from ./../../../../tmp/inifile/source.ini');
        $this->assertInLogs('[test] foo set to qux');
        $this->assertInLogs('Wrote to ./../../../../tmp/inifile/destination.ini');
    }

    public function testRemoveKeyFromSectionInSourceFile()
    {
        $fill = ["[test]\n", "; a comment\n", "foo=bar\n"];
        file_put_contents($this->inifiletestdir . "/source.ini", $fill);
        $this->executeTarget("removeKeyFromSectionInSourceFile");

        $this->assertInLogs('Read from ./../../../../tmp/inifile/source.ini');
        $this->assertInLogs('foo in section [test] has been removed.');
        $this->assertInLogs('Wrote to ./../../../../tmp/inifile/destination.ini');
        $result = file_get_contents($this->inifiletestdir . "/destination.ini");
        $this->assertEquals($result, "[test]\n; a comment\n");
    }

    public function testRemoveSectionFromSourceFile()
    {
        $fill = ["[test]\n", "; a comment\n", "foo=bar\n"];
        file_put_contents($this->inifiletestdir . "/source.ini", $fill);
        $this->executeTarget("removeSectionFromSourceFile");

        $this->assertInLogs('Read from ./../../../../tmp/inifile/source.ini');
        $this->assertInLogs('[test] has been removed.');
        $this->assertInLogs('Wrote to ./../../../../tmp/inifile/destination.ini');
        $result = file_get_contents($this->inifiletestdir . "/destination.ini");
        $this->assertEquals($result, "");
    }
}