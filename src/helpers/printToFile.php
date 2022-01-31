<?php

    /*
    * This file is part of the php-openssl-proxy.
    *
    * (c) Adão Pedro <adao.pedro16@gmail.com>
    *
    * For the full copyright and license information, please view the LICENSE
    * file that was distributed with this source code.
    */

    /**
     * Write a string to a file
     */
    function printToFile(mixed $data, string $filename, string $dir = __DIR__ . "/.."): void {
        $dir = trim($dir, " /");

        if(file_exists("/{$dir}/{$filename}")) unlink("/{$dir}/{$filename}");

        file_put_contents(
            "/{$dir}/{$filename}", $data
        );
    }