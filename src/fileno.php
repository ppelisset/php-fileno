<?php

function fileno(mixed $file): ?int
{
    static $ffiExtend = null;
    if (is_null($ffiExtend)) {
        define('PHP_FFI_EXTEND_APPEND_CDEF', file_get_contents(__DIR__ . "/php_api.h"));
        $ffiExtend = new \Toknot\FFIExtend();
    }
    if (!is_resource($file)) {
        return null;
    }
    $zval = $ffiExtend->zval($file);
    $stream = $ffiExtend->getffi()->zend_fetch_resource2($zval, "stream", $ffiExtend->getffi()->php_file_le_stream(), $ffiExtend->getffi()->php_file_le_pstream());
    if ($ffiExtend->isNull($stream)) {
        return null;
    }
    $filenoCData = FFI::new("int");
    $filenoCData->cdata = -1;
    $castTypeList = [3, 1]; // PHP_STREAM_AS_FD_FOR_SELECT, PHP_STREAM_AS_FD
    foreach ($castTypeList as $castType) {
        $ffiExtend->getffi()->_php_stream_cast($stream, $castType, FFI::cast("void *", FFI::addr($filenoCData)), 0);
        if ($filenoCData->cdata !== -1) {
            return $filenoCData->cdata;
        }
    }
    return null;
}