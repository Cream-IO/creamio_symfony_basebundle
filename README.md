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
    ├── EventSubscriber     # Exception 
event subscriber
    ├── Exceptions          # APIError and 
APIException for error handling in API
    ├── Resources
    │   └── config          # Service 
injection
    └── Service             # API Service 
handling JSON responses
```

[1]: 
https://symfony.com/doc/current/reference/requirements.html
[2]: 
https://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[3]: https://symfony.com/
