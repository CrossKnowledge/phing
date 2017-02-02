<?php

namespace Phing\Test\Task\System;

use Phing\Test\Helper\AbstractBuildFileTest;


/**
 * Tests the Condition Task
 *
 * @author  Michiel Rook <mrook@php.net>
 * @version $Id$
 * @package phing.tasks.system
 */
class ConditionTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE . '/etc/tasks/system/ConditionTest.xml'
        );
    }

    public function testEquals()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertySet('isEquals');
    }

    public function testContains()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertySet('isContains');
    }

    public function testCustomCondition()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertySet('isCustom');
    }

    public function testReferenceExists()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyUnset('ref.exists');
    }

    public function testSocketCondition()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyUnset('socket');
    }

    public function testMatches()
    {
        $this->executeTarget(__FUNCTION__);
        $this->assertPropertyEquals('matches', 'true');
    }
}