void *zend_fetch_resource2(zend_resource *res, const char *resource_type_name, int resource_type1, int resource_type2);
int php_file_le_stream(void);
int php_file_le_pstream(void);
int _php_stream_cast(php_stream *stream, int castas, void **ret, int show_err);