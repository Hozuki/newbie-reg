# newbie-reg

A simple newbie registration, viewing and controlling website. (in Simplified Chinese)

The project is mainly written in PHP and JavaScript. Don't mind the statistics bar telling you that CSS occupied most
of the lines, they are [Bootstrap](http://getbootstrap.com/).

## For Users

Coming soon.

## For Developers

**Step 1**

Download the source (use the Download ZIP button on the right), or use `git clone` command:

```
git clone https://github.com/hozuki/newbie-reg.git {destination-directory}
```

**Step 2**

Copy or rename `config.sample.php` to `config.php`. Create or select a configuration preset, and then
modify the value of `NR_CONFIG_PRESET_INDEX` to the preset's index.
  
**Step 3**

Create the file(s)

- `/logic/data/sql.conn.debug.php` (will be used when `$_CONFIG['flags']['debug']` is set to true) and/or
- `/logic/data/sql.conn.release.php` (will be used when `$_CONFIG['flags']['debug']` is set to false).

Note that the `/logic/data/` directory is ignored in `.gitignore`. The template of the SQL settings file(s) is as follows.

```php
<?php
define('SQL_USERNAME', 'USERNAME');
define('SQL_PASSWORD', 'PASSWORD');
define('SQL_HOST', 'HOST_NAME_OR_IP_ADDRESS');
define('SQL_DATABASE', 'DATABASE_NAME');
?>
```

**Step 4**

Create the file

- `/logic/data/cp.login.php`.

The template of the control panel settings file is as follows.

```php
<?php
define('NR_CP_USERNAME', 'USERNAME');
define('NR_CP_PASSWORD', 'PASSWORD');
?>
```

**Step 5**

Start web server and run the project in browsers. Enjoy.

## Remarks

1. The text in the pages is in **Simplified Chinese**.
2. In the code, it is assume that the local machine uses **UTF-8** encoding for file contents (e.g. in PHP sources),
and the database uses **GBK** encoding for input/output. Thus there are tons of code converting UTF-8 to GBK, or the inverse.
Also, the CSV files are considered as using **ANSI** code page, **which means it uses GBK on Simplified Chinese Windows OSes**.
Those assumptions are somehow like hard-coded in the code.

What a mess.

## Status

Still in beta state.

## License

[MIT License](http://mit-license.org/)
