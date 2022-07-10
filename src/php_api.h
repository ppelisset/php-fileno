/*
 * Some header from php-src
 * Thanks to: https://github.com/chopins/ffi-ext/blob/master/src/php.h
 */

typedef struct _zend_refcounted_h {
    uint32_t         refcount;                      /* reference counter 32-bit */
    union {
            uint32_t type_info;
    } u;
} zend_refcounted_h;

typedef struct _zend_refcounted {
    zend_refcounted_h gc;
} zend_refcounted;

typedef struct _zend_string {
    zend_refcounted_h gc;
    zend_ulong        h;                /* hash value */
    size_t            len;
    char              val[1];
} zend_string;

typedef struct _zval_struct zval;
typedef struct _zend_array zend_array;
typedef struct _zend_object zend_object;
typedef struct _zend_array zend_array;
typedef struct _zend_array HashTable;
typedef struct _php_stream php_stream;
typedef unsigned char zend_uchar;
typedef struct _zend_resource zend_resource;
typedef struct _zend_reference zend_reference;
typedef struct _zend_ast_ref    zend_ast_ref;
typedef struct _zend_class_entry     zend_class_entry;
typedef union  _zend_function        zend_function;
typedef struct _zend_property_info zend_property_info;
typedef struct _zend_object_handlers zend_object_handlers;
typedef void (*dtor_func_t)(zval *pDest);

struct _zend_resource {
    zend_refcounted_h gc;
    int               handle;
    int               type;
    void             *ptr;
};

struct _zval_struct {
    union {
        zend_long         lval;             /* long value */
        double            dval;             /* double value */
        zend_refcounted  *counted;
        zend_string      *str;
        zend_array       *arr;
        zend_object      *obj;
        zend_resource    *res;
        zend_reference   *ref;
        zend_ast_ref     *ast;
        zval             *zv;
        void             *ptr;
        zend_class_entry *ce;
        zend_function    *func;
        struct {
            uint32_t w1;
            uint32_t w2;
        } ww;
    } value;
    union {
        struct {
                zend_uchar    type;         /* active type */
                zend_uchar    type_flags;
                zend_uchar    const_flags;
                zend_uchar    reserved;     /* call info for EX(This) */
        } v;
        uint32_t type_info;
    } u1;
    union {
        uint32_t     var_flags;
        uint32_t     next;                 /* hash collision chain */
        uint32_t     cache_slot;           /* literal cache slot */
        uint32_t     lineno;               /* line number (for ast nodes) */
        uint32_t     num_args;             /* arguments number for EX(This) */
        uint32_t     fe_pos;               /* foreach position */
        uint32_t     fe_iter_idx;          /* foreach iterator index */
    } u2;
};

typedef union {
        zend_property_info *ptr;
        uintptr_t list;
} zend_property_info_source_list;

struct _zend_reference {
    zend_refcounted_h              gc;
    zval                           val;
    zend_property_info_source_list sources;
};

typedef struct _Bucket {
    zval              val;
    zend_ulong        h;                /* hash value (or numeric index)   */
    zend_string      *key;              /* string key or NULL for numerics */
} Bucket;

typedef struct _zend_array {
    zend_refcounted_h gc;
    union {
            struct {
                zend_uchar    flags;
                zend_uchar    _unused;
                zend_uchar    nIteratorsCount;
                zend_uchar    _unused2;
            } v;
            uint32_t flags;
    } u;
    uint32_t          nTableMask;
    Bucket           *arData;
    uint32_t          nNumUsed;
    uint32_t          nNumOfElements;
    uint32_t          nTableSize;
    uint32_t          nInternalPointer;
    zend_long         nNextFreeElement;
    dtor_func_t       pDestructor;
};

struct _zend_object {
    zend_refcounted_h gc;
    uint32_t          handle;
    zend_class_entry *ce;
    const zend_object_handlers *handlers;
    HashTable        *properties;
    zval              properties_table[1];
};

struct _zend_ast_ref {
        zend_refcounted_h gc;
        /*zend_ast        ast; zend_ast follows the zend_ast_ref structure */
};


zend_array *zend_rebuild_symbol_table(void);
HashTable*  zend_array_dup(HashTable *source);
void zend_array_destroy(HashTable *ht);
void *zend_fetch_resource2(zend_resource *res, const char *resource_type_name, int resource_type1, int resource_type2);
int php_file_le_stream(void);
int php_file_le_pstream(void);
int _php_stream_cast(php_stream *stream, int castas, void **ret, int show_err);