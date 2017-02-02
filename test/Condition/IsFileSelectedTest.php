<?php

namespace Phing\Test\Condition;

use Phing\Test\Helper\AbstractBuildFileTest;

/**
 * Tests the IsFileSelected Condition
 *
 * @author  Siad Ardroumli <siad.ardroumli@gmail.com>
 * @package phing.tasks.system
 */
class IsFileSelectedTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/tasks/system/IsFileSelectedTest.xml'
        );
    }

    public function testIsFileSelected()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertySet('selected');
    }

    public function testNonFileSelected()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyUnset('unset');
    }
}