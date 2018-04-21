# CreamIO Symfony Base Bundle

This bundle is a base for the CreamIO 
symfony bundles over [Symfony 4.0][3].

Requirements
------------

  * Symfony 4;
  * PHP 7.2 or higher;
  * Composer;
  * MySQL database;
  * PDO PHP extension;
  * and the [usual Symfony application 
requirements][1].
  
Installation
------------

Require the bundle from a symfony 4 
application.

Project tree
------------

```bash
.
└── src
    ├── DependencyInjection
    ├── EventSubscriber     # Exception event subscriber
    ├── Exceptions          # APIError and APIException for error handling in API
    ├── Resources
    │   └── config          # Service injection
    └── Service             # API Service handling JSON responses
```

License
-------
[![Creative Commons License](https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png)](http://creativecommons.org/licenses/by-nc-sa/4.0/)

This software is distributed under the terms of the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International Public License. License is described below, you can find a human-readable summary of (and not a substitute for) the license [here](http://creativecommons.org/licenses/by-nc-sa/4.0/).

[1]: 
https://symfony.com/doc/current/reference/requirements.html
[2]: 
https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[3]: https://symfony.com/
