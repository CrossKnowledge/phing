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

namespace Phing\Test\Type;

use Phing\Exception\BuildException;
use Phing\Type\CommandLine;
use Phing\Type\CommandLine\CommandLineMarker;
use PHPUnit_Framework_TestCase;

/**
 * Unit test for mappers.
 *
 * @author Hans Lellelid <hans@xmpl.org>
 * @author Stefan Bodewig <stefan.bodewig@epost.de> (Ant)
 * @package phing.types
 */
class CommandLineTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CommandLine
     */
    private $cmd;

    //private $project;

    public function setUp()
    {
        $this->cmd = new CommandLine();
    }

    public function testTranslateCommandline()
    {
        // This should work fine; we expect 5 args
        $cmd1 = "cvs -d:pserver:hans@xmpl.org:/cvs commit -m \"added a new test file\" Test.php";
        $c = new CommandLine($cmd1);
        $this->assertEquals(5, count($c->getArguments()));

        // This has some extra space, but we expect same number of args
        $cmd2 = "cvs -d:pserver:hans@xmpl.org:/cvs   commit  -m \"added a new test file\"    Test.php";
        $c2 = new CommandLine($cmd2);
        $this->assertEquals(5, count($c2->getArguments()));

        // nested quotes should not be a problem either
        $cmd3 = "cvs -d:pserver:hans@xmpl.org:/cvs   commit  -m \"added a new test file for 'fun'\"    Test.php";
        $c3 = new CommandLine($cmd3);
        $this->assertEquals(5, count($c3->getArguments()));
        $args = $c3->getArguments();
        $this->assertEquals("added a new test file for 'fun'", $args[3]);

        // now try unbalanced quotes -- this should fail
        $cmd4 = "cvs -d:pserver:hans@xmpl.org:/cvs   commit  -m \"added a new test file for 'fun' Test.php";
        try {
            $c4 = new CommandLine($cmd4);
            $this->fail("Should throw BuildException because 'unbalanced quotes'");
        } catch (BuildException $be) {
            if (false === strpos($be->getMessage(), "unbalanced quotes")) {
                $this->fail("Should throw BuildException because 'unbalanced quotes'");
            }
        }
    }

    public function testCreateMarkerWithArgument()
    {
        $this->cmd->addArguments(['foo']);
        $marker = $this->cmd->createMarker();
        self::assertInstanceOf(CommandLineMarker::class, $marker);
        self::assertEquals(1, $marker->getPosition());
    }

    public function testCreateMarkerWithoutArgument()
    {
        $marker = $this->cmd->createMarker();
        self::assertInstanceOf(CommandLineMarker::class, $marker);
        self::assertEquals(0, $marker->getPosition());
    }
}