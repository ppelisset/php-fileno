<?php

use FileNo\PHPApi;

function fileno(mixed $stream): ?int
{
    $zval = PHPApi::zval($stream);
    if (!PHPApi::is_resource($zval)) {
        return null;
    }
    $resource = PHPApi::get_resource_from_zval($zval);
    $phpStream = PHPApi::zend_fetch_resource2($resource, "stream", PHPApi::php_file_le_stream(), PHPApi::php_file_le_pstream());
    if (PHPApi::is_null($phpStream)) {
        return null;
    }
    $filenoCData = FFI::new("int");
    $filenoCData->cdata = -1;
    $castTypeList = [3, 1]; // PHP_STREAM_AS_FD_FOR_SELECT, PHP_STREAM_AS_FD
    foreach ($castTypeList as $castType) {
        PHPApi::php_stream_cast($phpStream, $castType, FFI::cast("void *", FFI::addr($filenoCData)));
        if ($filenoCData->cdata !== -1) {
            return $filenoCData->cdata;
        }
    }
    return null;
}