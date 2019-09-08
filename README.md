# fritz_temp

A munin plugin for monitoring the temperatures of Smart Home devices

## Passwords

Create a file called `passwords.inc.php` with the following content:

```php
<?php

$fritz_host = 'FRITZ_BOX_IP_OR_fritz.box';
$fritz_url = 'https://' . $fritz_host . '/login_sid.lua';
$auto_url = 'https://' . $fritz_host . '/webservices/homeautoswitch.lua';
$fritz_user = 'USERNAME';
$fritz_pwd = 'PASSWORD';

?>
```

The user must have Smart Home privileges. Other user rights are not needed.
