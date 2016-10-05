<?php
/*
 *  $Id$
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://phing.info>.
 */

namespace Phing\Test\Task\System;

use Phing\Test\Helper\AbstractBuildFileTest;


/**
 * Tests the Manifest Task
 *
 * @author  Michiel Rook <mrook@php.net>
 * @version $Id$
 * @package phing.tasks.system
 */
class ManifestTest extends AbstractBuildFileTest
{
    public function setUp()
    {
        $this->configureProject(
            PHING_TEST_BASE
            . "/etc/tasks/system/ManifestTest.xml"
        );
        $this->executeTarget("setup");
    }

    public function tearDown()
    {
        $this->executeTarget("clean");
    }

    public function testGenerateManifest()
    {
        $this->executeTarget(__FUNCTION__);
        $hash = md5("salty" . "File1");
        $manifestFile = realpath(PHING_TEST_BASE . "/etc/tasks/system/tmp/manifest");
        $this->assertInLogs("Writing to " . $manifestFile);
        $this->assertEquals("file1\t" . $hash . "\n", file_get_contents($manifestFile));
    }
}