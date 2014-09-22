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
 * @package   RawPHP/RawFileSystem/Tests
 * @author    Tom Kaczohca <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */

namespace RawPHP\RawFileSystem;

use RawPHP\RawFileSystem\FileSystem;

/**
 * The File System class tests.
 * 
 * @category  PHP
 * @package   RawPHP/RawFileSystem/Tests
 * @author    Tom Kaczocha <tom@rawphp.org>
 * @copyright 2014 Tom Kaczocha
 * @license   http://rawphp.org/license.txt MIT
 * @link      http://rawphp.org/
 */
class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FileSystem
     */
    public $fs;
    
    private $_testFile = 'testFile.txt';
    private $_testDir  = 'testDir';
    
    /**
     * Setup before each test.
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->fs = new FileSystem( );
    }
    
    /**
     * Cleanup after each test.
     */
    public function tearDown( )
    {
        if ( file_exists( OUTPUT_DIR . $this->_testFile ) )
        {
            unlink( OUTPUT_DIR . $this->_testFile );
        }
        
        if ( file_exists( OUTPUT_DIR . $this->_testDir ) )
        {
            if ( file_exists( OUTPUT_DIR . $this->_testDir . DS . $this->_testFile ) )
            {
                unlink( OUTPUT_DIR . $this->_testDir . DS . $this->_testFile );
            }
            
            $this->fs->deleteDirectory( OUTPUT_DIR . $this->_testDir );
        }
        
        $this->fs->deleteDirectory( OUTPUT_DIR . 'one' );
        $this->fs->deleteDirectory( OUTPUT_DIR . 'source' );
        $this->fs->deleteDirectory( OUTPUT_DIR . 'final' );
        $this->fs->deleteDirectory( OUTPUT_DIR . 'end' );
        
        $this->fs = NULL;
    }
    
    /**
     * Test File System instantiated correctly.
     */
    public function testFSInstantiatedCorrectly( )
    {
        $this->assertNotNull( $this->fs );
    }
    
    /**
     * Test creating a file.
     */
    public function testCreateFile( )
    {
        $this->assertTrue( $this->fs->createFile( OUTPUT_DIR . $this->_testFile ) );
        
        $this->assertTrue( file_exists( OUTPUT_DIR . $this->_testFile ) );
    }
    
    /**
     * Test copy a test file to another directory.
     */
    public function testCopyFile( )
    {
        $this->assertTrue( $this->fs->createDirectory( OUTPUT_DIR . $this->_testDir ) );
        
        $this->assertTrue( $this->fs->createFile( OUTPUT_DIR . $this->_testFile ) );
        
        $this->assertTrue( $this->fs->copyFile( 
                OUTPUT_DIR . $this->_testFile, 
                OUTPUT_DIR . $this->_testDir . DS . $this->_testFile )
        );
        
        $this->assertTrue( file_exists( OUTPUT_DIR . $this->_testFile ) );
        $this->assertTrue( file_exists( OUTPUT_DIR . $this->_testDir . DS . $this->_testFile ) );
    }
    
    /**
     * Test move file to another directory.
     */
    public function testMoveFile( )
    {
        $this->assertTrue( $this->fs->createDirectory( OUTPUT_DIR . $this->_testDir ) );
        
        $this->assertTrue( $this->fs->createFile( OUTPUT_DIR . $this->_testFile ) );
        
        $this->assertTrue( $this->fs->moveFile( 
                OUTPUT_DIR . $this->_testFile, 
                OUTPUT_DIR . $this->_testDir . DS . $this->_testFile )
        );
        
        $this->assertFalse( file_exists( OUTPUT_DIR . $this->_testFile ) );
        $this->assertTrue( file_exists( OUTPUT_DIR . $this->_testDir . DS . $this->_testFile ) );
    }
    
    /**
     * Test delete file.
     */
    public function testDeleteFile( )
    {
        $this->assertTrue( $this->fs->createFile( OUTPUT_DIR . $this->_testFile ) );
        
        $this->assertTrue( $this->fs->deleteFile( OUTPUT_DIR . $this->_testFile ) );
        
        $this->assertFalse( file_exists( OUTPUT_DIR . $this->_testFile ) );
    }
    
    /**
     * Test creating a directory.
     */
    public function testCreateDirectory( )
    {
        $this->assertTrue( $this->fs->createDirectory( OUTPUT_DIR . $this->_testDir ) );
        
        $this->assertTrue( file_exists( OUTPUT_DIR . $this->_testDir ) );
    }
    
    /**
     * Test creating directories with three levels.
     */
    public function testCreatingDirectoryWithThreeLevels( )
    {
        $path = OUTPUT_DIR . 'one' . DS . 'two' . DS . 'three';
        
        $this->assertTrue( $this->fs->createDirectory( $path ) );
        
        $this->assertTrue( file_exists( $path ) );
    }
    
    /**
     * Test copying a directory with a file to a directory that doesn't yet
     * exist.
     */
    public function testCopyDirectoryWithFileToNonExistentDirectory( )
    {
        $source = OUTPUT_DIR . 'source';
        $final = OUTPUT_DIR . 'final';
        
        $this->assertTrue( $this->fs->createDirectory( $source ) );
        $this->assertTrue( $this->fs->createFile( $source . DS . $this->_testFile ) );
        
        $this->assertTrue( $this->fs->copyDirectory( $source, $final . DS . 'source' ) );
        
        $this->assertTrue( file_exists( $source ) );
        $this->assertTrue( file_exists( $source . DS . $this->_testFile ) );
        $this->assertTrue( file_exists( $final ) );
        $this->assertTrue( file_exists( $final . DS . 'source' . DS . $this->_testFile ) );
    }
    
    /**
     * Test moving a directory with a file.
     */
    public function testMoveDirectoryWithFile( )
    {
        $source = OUTPUT_DIR . 'start';
        $final = OUTPUT_DIR . 'end';
        
        $this->assertTrue( $this->fs->createDirectory( $source ) );
        $this->assertTrue( $this->fs->createFile( $source . DS . $this->_testFile ) );
        $this->assertTrue( $this->fs->createDirectory( $final ) );
        
        $this->assertTrue( $this->fs->moveDirectory( $source, $final ) );
        
        $this->assertFalse( file_exists( $source ) );
        $this->assertTrue( file_exists( $final ) );
        $this->assertTrue( file_exists( $final . DS . 'start' ) );
        $this->assertTrue( file_exists( $final . DS . 'start' . DS . $this->_testFile ) );
    }
    
    /**
     * Test deleting a directory with a file.
     */
    public function testDeleteDirectoryWithFile( )
    {
        $source = OUTPUT_DIR . 'source';
        
        $this->assertTrue( $this->fs->createDirectory( $source ) );
        $this->assertTrue( $this->fs->createFile( $source . DS . $this->_testFile ) );
        
        $this->assertTrue( $this->fs->deleteDirectory( $source ) );
        
        $this->assertFalse( file_exists( $source ) );
    }
    
    /**
     * Test deleting a directory with a directory and a file.
     */
    public function testDeleteDirectoryWithADirectoryAndWithAFile( )
    {
        $source = OUTPUT_DIR . 'source';
        $inDir  = $source . DS . 'inner';
        
        $this->assertTrue( $this->fs->createDirectory( $inDir ) );
        $this->assertTrue( $this->fs->createFile( $source . DS . $this->_testFile ) );
        $this->assertTrue( $this->fs->createFile( $inDir . DS . $this->_testFile ) );
        
        $this->assertTrue( file_exists( $source ) );
        $this->assertTrue( file_exists( $inDir ) );
        $this->assertTrue( file_exists( $source . DS . $this->_testFile ) );
        $this->assertTrue( file_exists( $inDir . DS . $this->_testFile ) );
        
        $this->assertTrue( $this->fs->deleteDirectory( $source ) );
        
        $this->assertFalse( file_exists( $source ) );
    }
}