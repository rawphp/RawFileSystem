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
 * @package   RawPHP\RawFileSystem\Contract
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawFileSystem\Contract;

/**
 * The file system helper class.
 *
 * @category  PHP
 * @package   RawPHP\RawFileSystem\Contract
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
interface IFileSystem
{
    /**
     * Creates a new file.
     *
     * @param string $path file path
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function createFile( $path );

    /**
     * Copies a file from source to destination.
     *
     * @param string $source      the file current location
     * @param string $destination the file destination
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function copyFile( $source, $destination );

    /**
     * Moves a file from source to destination.
     *
     * @param string $source      the file current location
     * @param string $destination the file destination
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function moveFile( $source, $destination );

    /**
     * Deletes a file at path.
     *
     * @param string $path file location
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function deleteFile( $path );

    /**
     * Creates a new directory.
     *
     * @param string $path      path to new directory
     * @param bool   $recursive create directories recursively
     *                          defaults to TRUE
     *
     * @return bool TRUE on success, FALSE on failure
     */
    public function createDirectory( $path, $recursive = TRUE );

    /**
     * Copies a directory from source to destination.
     *
     * If destination directory doesn't exist, it is created.
     *
     * @param string $source      path to the directory
     * @param string $destination path to the new location
     * @param bool   $recursive   whether to also copy all sub-directories
     *
     * @return mixed TRUE on success, the \Exception on error
     */
    public function copyDirectory( $source, $destination, $recursive = TRUE );

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
    public function moveDirectory( $source, $destination );

    /**
     * Deletes a directory and all its contents.
     *
     * @param string $path   location of the directory
     * @param bool   $strict if set returns FALSE if the directory
     *                       doesn't exist
     *
     * @return mixed TRUE on success, FALSE or \Exception on error
     */
    public function deleteDirectory( $path, $strict = FALSE );

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
    public function parseDirectory( $path = NULL, $levels = NULL );
}