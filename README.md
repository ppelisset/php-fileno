# php-fileno

This package append function `fileno` to retrieve the file descriptor of a stream.

## Installation
php-fileno require PHP8 and php-ffi enabled. To install this package, use composer to require package `ppelisset/fileno`.

## Documentation
fileno - Get a file descriptor from a stream
```
fileno(resource $stream): ?int
```
Return the file descriptor of the **stream** or null if **stream** is not a stream with a file descriptor (not a stream, network stream...)