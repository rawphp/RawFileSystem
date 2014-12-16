<?php

/**
 * This file is part of RawPHP - a PHP Framework.
 *
 * Copyright (c) 2014 RawPHP.org
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * PHP version 5.3
 *
 * @category  PHP
 * @package   RawPHP\RawFileSystem
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawFileSystem;

use Exception;
use RawPHP\RawFileSystem\Contract\IFileSystem;

/**
 * The file system helper class.
 *
 * @category  PHP
 * @package   RawPHP/RawFileSystem
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class FileSystem implements IFileSystem
{
    protected $results = [ ];
    protected $dirHandles = [ ];
    protected $fileHandles = [ ];
    protected $linkHandles = [ ];

    protected $levels = 1;

    private $_dirs = [ ];
    private $_rootPath;
    private $_followLinks = TRUE;

    /**
     * Creates a new file.
     *
     * @param string $path file path
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function createFile( $path )
    {
        return touch( $path );
    }

    /**
     * Copies a file from source to destination.
     *
     * @param string $source      the file current location
     * @param string $destination the file destination
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function copyFile( $source, $destination )
    {
        return copy( $source, $destination );
    }

    /**
     * Moves a file from source to destination.
     *
     * @param string $source      the file current location
     * @param string $destination the file destination
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function moveFile( $source, $destination )
    {
        $result = copy( $source, $destination );

        if ( TRUE === $result )
        {
            $this->deleteFile( $source );
        }

        return $result;
    }

    /**
     * Deletes a file at path.
     *
     * @param string $path file location
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function deleteFile( $path )
    {
        return unlink( $path );
    }

    /**
     * Creates a new directory.
     *
     * @param string $path      path to new directory
     * @param bool   $recursive create directories recrusively
     *                          defaults to TRUE
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function createDirectory( $path, $recursive = TRUE )
    {
        return mkdir( $path, 0777, TRUE );
    }

    /**
     * Copies a directory from source to destination.
     *
     * If destination directory doesn't exist, it is created.
     *
     * @param string $source      path to the directory to be copied
     * @param string $destination path to the new location
     * @param bool   $recursive   whether to also copy all sub-directories
     *
     * @return mixed TRUE on success, the \Exception on error
     */
    public function copyDirectory( $source, $destination, $recursive = TRUE )
    {
        $result = FALSE;

        if ( !file_exists( $destination ) )
        {
            $this->createDirectory( $destination );
        }

        try
        {
            foreach ( scandir( $source ) as $file )
            {
                if ( '.' === $file || '..' === $file )
                {
                    continue;
                }

                if ( is_dir( $source . DS . $file ) )
                {
                    $this->createDirectory( $destination . DS . $file );

                    if ( $recursive )
                    {
                        $this->copyDirectory( $source . DS . $file, $destination . DS . $file );
                    }
                }
                elseif ( is_file( $source . DS . $file ) )
                {
                    $this->copyFile( $source . DS . $file, $destination . DS . $file );
                }
            }

            $result = TRUE;
        }
        catch ( Exception $e )
        {
            $result = $e;
        }

        return $result;
    }

    /**
     * Moves a directory from source to destination.
     *
     * If destination directory doesn't exist, it is created.
     *
     * @param string $source      path to the directory
     * @param string $destination path to the new location
     *
     * @return mixed TRUE on success, \Exception on error
     */
    public function moveDirectory( $source, $destination )
    {
        $path = explode( DS, $source );

        $destination = $destination . DS . $path[ count( $path ) - 1 ];

        $result = $this->copyDirectory( $source, $destination, TRUE );

        if ( TRUE === $result )
        {
            $result = $this->deleteDirectory( $source );
        }

        return $result;
    }

    /**
     * Deletes a directory and all its contents.
     *
     * @param string $path   location of the directory
     * @param bool   $strict if set returns FALSE if the directory
     *                       doesn't exist
     *
     * @return mixed TRUE on success, FALSE or \Exception on error
     */
    public function deleteDirectory( $path, $strict = FALSE )
    {
        if ( !file_exists( $path ) )
        {
            if ( $strict )
            {
                $result = FALSE;
            }

            $result = TRUE;
        }
        else
        {
            try
            {
                foreach ( scandir( $path ) as $file )
                {
                    if ( '.' === $file || '..' === $file )
                    {
                        continue;
                    }

                    if ( is_dir( $path . DS . $file ) )
                    {
                        $this->deleteDirectory( $path . DS . $file );
                    }
                    else
                    {
                        unlink( $path . DS . $file );
                    }
                }

                $result = rmdir( $path );
            }
            catch ( Exception $e )
            {
                $result = $e;
            }
        }

        return $result;
    }

    /**
     * Parses the directory and returns lists of directories, files
     * and links.
     *
     * @param string $path   directory path
     * @param int    $levels optional max number of levels to go down
     *                       default is 'unlimited'
     *
     * @return array list of directories, files and links
     */
    public function parseDirectory( $path = NULL, $levels = NULL )
    {
        if ( NULL !== $levels )
        {
            $this->levels = $levels;
        }

        $this->_runParse( $path );
        $this->_setResults();

        return $this->results;
    }

    /**
     * Runs the directory parsing process.
     *
     * @param string $path optional path
     */
    private function _runParse( $path = NULL )
    {
        $startLevels  = $this->_getRealLevel( $this->_rootPath );
        $endLevels    = $startLevels + $this->levels;
        $currentLevel = 0;

        if ( NULL !== $path )
        {
            $this->_dirs[ ] = $path;
        }

        do
        {
            $dir          = array_shift( $this->_dirs );
            $currentLevel = $this->_getRealLevel( $dir );

            if ( $currentLevel < $endLevels )
            {
                $this->_parse( $dir );
            }
        } while ( count( $this->_dirs ) > 0 );
    }

    /**
     * Recursively parses the directory and adds directories, files
     * and links to their corresponding handles.
     *
     * @param string $dir the directory path
     */
    private function _parse( $dir )
    {
        foreach ( scandir( $dir ) as $file )
        {
            if ( '.' === $file || '..' === $file )
            {
                continue;
            }

            if ( is_dir( $dir . DS . $file ) )
            {
                $this->_dirs[ ] = $dir . DS . $file;

                $this->dirHandles[ ] = $dir . DS . $file;
            }
            elseif ( 'file' === filetype( $dir . DS . $file ) )
            {
                $this->fileHandles[ ] = $dir . DS . $file;
            }
            elseif ( 'link' === filetype( $dir . DS . $file ) )
            {
                $this->linkHandles[ ] = $dir . DS . $file;
            }
        }
    }

    /**
     * Returns the real directory level.
     *
     * This is calculated by counting the back/forward slashes in
     * the path.
     *
     * @param string $dir directory path
     *
     * @return int the level number
     */
    private function _getRealLevel( $dir )
    {
        $e = explode( DS, $dir );

        return count( $e );
    }

    /**
     * Sets the parse results ready for returning.
     *
     * @param bool $extended whether to include the actual files - default is TRUE
     */
    private function _setResults( $extended = TRUE )
    {
        $this->results                 = [ ];
        $this->results[ 'file_count' ] = count( $this->fileHandles );
        $this->results[ 'dir_count' ]  = count( $this->dirHandles );
        $this->results[ 'link_count' ] = count( $this->linkHandles );

        if ( $extended )
        {
            $this->results[ 'files' ] = $this->fileHandles;
            $this->results[ 'dirs' ]  = $this->dirHandles;
            $this->results[ 'links' ] = $this->linkHandles;
        }
    }
}