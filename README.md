# RawFileSystem - A Set of Methods for Working with the File System [![Build Status](https://travis-ci.org/rawphp/RawFileSystem.svg?branch=master)](https://travis-ci.org/rawphp/RawFileSystem)

[![Latest Stable Version](https://poser.pugx.org/rawphp/raw-file-system/v/stable.svg)](https://packagist.org/packages/rawphp/raw-file-system) [![Total Downloads](https://poser.pugx.org/rawphp/raw-file-system/downloads.svg)](https://packagist.org/packages/rawphp/raw-file-system) [![Latest Unstable Version](https://poser.pugx.org/rawphp/raw-file-system/v/unstable.svg)](https://packagist.org/packages/rawphp/raw-file-system) [![License](https://poser.pugx.org/rawphp/raw-file-system/license.svg)](https://packagist.org/packages/rawphp/raw-file-system)

## Package Features
- Create, copy, move and delete files
- Create, copy, move and delete directories

## Installation

### Composer
RawFileSystem is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-file-system).

Add `"rawphp/raw-file-system": "0.*@dev"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-file-system": "0.*@dev"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-file-system "0.*@dev"
```

### Tarball
Alternatively, just copy the contents of the RawFileSystem folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

use RawPHP\RawFileSystem\FileSystem;

$fs = new FileSystem( );

$fs->createFile( '/path/to/file.txt' );
$fs->copyFile( '/path/to/source/file.txt', '/path/to/destination/file.txt' );
$fs->moveFile( '/path/to/source/file.txt', '/path/to/destination/file.txt' );
$fs->deleteFile( '/path/to/source/file.txt' );

$fs->createDirectory( '/path/to/dirname' );
$fs->copyDirectory( '/path/to/dir', '/path/to/destination' );
$fs->moveDirectory( '/path/to/dir', '/path/to/destination' );
$fs->deleteDirectory( '/path/to/dir', TRUE ); // deletes recursively if TRUE
```

## License
This package is licensed under the [MIT](https://github.com/rawphp/RawFileSystem/blob/master/LICENSE). Read LICENSE for information on the software availability and distribution.

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/rawphp/RawFileSystem/issues).

## Changelog

#### 16-09-2014
- Initial Code Commit
