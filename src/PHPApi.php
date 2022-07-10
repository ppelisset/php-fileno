<?php

namespace FileNo;

use FFI;
use FFI\CData;

class PHPApi
{
    private const ZTYPE_RESOURCE = 9;

    private static ?FFI $ffi = null;

    private static function ffi(): FFI
    {
        if (!self::$ffi) {
            $bitSize = PHP_INT_SIZE * 8;
            $header = "typedef int{$bitSize}_t zend_long;typedef uint{$bitSize}_t zend_ulong;typedef int{$bitSize}_t zend_off_t;";
            $header .= file_get_contents(__DIR__ . "/php_api.h");
            self::$ffi = FFI::cdef($header);
        }
        return self::$ffi;
    }

    public static function zval($var): CData
    {
        $symbolTable = self::ffi()->zend_rebuild_symbol_table();
        $symbolHashTable = self::ffi()->zend_array_dup($symbolTable);
        $zval = $symbolHashTable->arData->val;
        self::ffi()->zend_array_destroy($symbolHashTable);
        return $zval;
    }

    public static function ztype($zval): int
    {
        return $zval->u1->v->type;
    }

    public static function is_resource($zval): bool
    {
        return self::ztype($zval) === self::ZTYPE_RESOURCE;
    }

    public static function get_resource_from_zval($zval)
    {
        if (!self::is_resource($zval)) {
            return false;
        }
        return $zval->value->res;
    }

    public static function zend_fetch_resource2($zresource, string $type, $stream, $pstream)
    {
        return self::ffi()->zend_fetch_resource2($zresource, $type, $stream, $pstream);
    }

    public static function php_file_le_stream()
    {
        return self::ffi()->php_file_le_stream();
    }

    public static function php_file_le_pstream()
    {
        return self::ffi()->php_file_le_pstream();
    }

    public static function is_null($value): bool
    {
        return is_null($value) || ($value instanceof CData && FFI::isNull($value));
    }

    public static function php_stream_cast(CData $stream, int $castType, CData $target, int $displayError = 0)
    {
        self::ffi()->_php_stream_cast($stream, $castType, $target, $displayError);
    }
}