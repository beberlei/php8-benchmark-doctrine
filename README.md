# Doctrine ORM Setup for JIT Benchmarking

## Steps:

1. Compile PHP 8.0 alpha1 (or later)

```
./configure'  '--prefix=/opt/php/php-src' '--with-config-file-path=/opt/php/php-8.0a1/etc' '--enable-mbstring' '--enable-zip' '--enable-bcmath' '--enable-pcntl' '--enable-ftp' '--enable-exif' '--enable-calendar' '--enable-sysvmsg' '--enable-sysvsem' '--enable-sysvshm' '--enable-wddx' '--with-curl' '--with-mcrypt' '--with-iconv' '--with-gmp' '--with-pspell' '--with-gd' '--with-jpeg-dir=/usr' '--with-png-dir=/usr' '--with-zlib-dir=/usr' '--with-xpm-dir=/usr' '--with-freetype-dir=/usr' '--with-t1lib=/usr' '--enable-gd-native-ttf' '--enable-gd-jis-conv' '--with-openssl' '--with-pdo-mysql=mysqlnd' '--with-gettext=/usr' '--with-zlib=/usr' '--with-bz2=/usr' '--with-recode=/usr' '--with-mysqli=mysqlnd'
```

2. Install tideways_xhprof 

https://github.com/tideways/php-xhprof-extension

3. Run prepare steps

```
/opt/php/php-src/bin/php schema.php
/opt/php/php-src/bin/php import.php
```

4. Run benchmarks with php-cgi

Using different flags:

- `-dopcache.jit=1205` - jit everything
- `-dopcache.jit=1235` - jit hot functions based on threshold
- `-dopcache.jit=1255` - magical new jit trigger mode not in the RFC

```
/opt/php/php-src/bin/php-cgi -T 10 -dextension=tideways_xhprof.so -dopcache.enable=1 -dopcache.enable_cli=1 -dopcache.jit=1255 -dopcache.jit_buffer_size=64M index.php
/opt/php/php-src/bin/php-cgi -T 10 -dextension=tideways_xhprof.so -dopcache.enable=1 -dopcache.enable_cli=1 -dopcache.jit=1235 -dopcache.jit_buffer_size=64M index.php
/opt/php/php-src/bin/php-cgi -T 10 -dextension=tideways_xhprof.so -dopcache.enable=1 -dopcache.enable_cli=1 -dopcache.jit=1205 -dopcache.jit_buffer_size=64M index.php
/opt/php/php-src/bin/php-cgi -T 10 -dextension=tideways_xhprof.so -dopcache.enable=1 -dopcache.enable_cli=1 -dopcache.jit=1205 -dopcache.jit_buffer_size=0 index.php
```
