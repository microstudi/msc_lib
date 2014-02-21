MSC LIB a simple Framework Library
==================================

MSC LIB is a super-lightweight procedural framework providing some useful methods to build php websites.

Is a function-orientated library, allows you to work with views, FTP, S3, SSH automatic file handling, resizing/mixing images with disc cache, SQL abstraction functions, CSS & Javascript compression, language files management & URL routing with regexp expressions.

USAGE:
======

* First include the start file:

```php
<?php

include_once("msc_lib/start.php");

?>
```

* Getting started:

```php
<?php

include('msc_lib/start.php');
echo m_view('html', array('tagtitle' => "This is a test page", 'body' => "Hello world"));

?>
```

Check for examples and the full documentation here:

http://www.microstudi.net/msc_lib/examples/


Author: Ivan Vergés 2011 - 2014

License: LGPLv3 http://www.gnu.org/copyleft/lgpl.html
