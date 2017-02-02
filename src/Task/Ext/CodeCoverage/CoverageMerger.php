<?php
namespace Phing\Task\Ext\CodeCoverage;

use Phing\Io\File;
use Phing\Task;
use Phing\Task\Ext\CodeCoverage\CoverageMergeHelper;
use Phing\Type\FileSet;

/**
 * $Id$
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


/**
 * Merges code coverage snippets into a code coverage database
 *
 * @author Michiel Rook <mrook@php.net>
 * @version $Id$
 * @package phing.tasks.ext.coverage
 * @since 2.1.0
 */
class CoverageMerger extends Task
{
    /**
     * the list of filesets containing the .php filename rules
     * @var Fileset[]
     */
    private $filesets = [];

    /**
     * Add a new fileset containing the .php files to process
     *
     * @param FileSet $fileset the new fileset containing .php files
     */
    public function addFileSet(FileSet $fileset)
    {
        $this->filesets[] = $fileset;
    }

    /**
     * Iterate over all filesets and return all the filenames.
     *
     * @return array an array of filenames
     */
    private function getFilenames()
    {
        $files = [];

        foreach ($this->filesets as $fileset) {
            $ds = $fileset->getDirectoryScanner($this->project);
            $ds->scan();

            $includedFiles = $ds->getIncludedFiles();

            foreach ($includedFiles as $file) {
                $fs = new File(basename($ds->getBaseDir()), $file);

                $files[] = $fs->getAbsolutePath();
            }
        }

        return $files;
    }

    public function main()
    {
        $files = $this->getFilenames();

        $this->log("Merging " . count($files) . " coverage files");

        foreach ($files as $file) {
            $coverageInformation = unserialize(file_get_contents($file));

            CoverageMergeHelper::merge($this->project, [$coverageInformation]);
        }
    }
}