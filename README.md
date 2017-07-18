# SwiftMailerDBBundle

[![Latest Stable Version](https://poser.pugx.org/whiteoctober/swiftmailerdbbundle/v/stable)](https://packagist.org/packages/whiteoctober/swiftmailerdbbundle) 
[![Total Downloads](https://poser.pugx.org/whiteoctober/swiftmailerdbbundle/downloads)](https://packagist.org/packages/whiteoctober/swiftmailerdbbundle) 
[![Monthly Downloads](https://poser.pugx.org/whiteoctober/swiftmailerdbbundle/d/monthly)](https://packagist.org/packages/whiteoctober/swiftmailerdbbundle)
[![License](https://poser.pugx.org/whiteoctober/swiftmailerdbbundle/license)](https://packagist.org/packages/whiteoctober/swiftmailerdbbundle)

![SwiftMailer](http://swiftmailer.org/images/logo.png)

This bundle faciliates using a database to spool messages to with SwiftMailer and Symfony2.

At present, it only works with the Doctrine EntityManager and entities managed with this.

## Installation and configuration


### 1. Install via Composer

``` sh
$ composer require "whiteoctober/swiftmailerdbbundle:^1.0"
```

### 2. Add the bundle to your application's kernel

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new WhiteOctober\SwiftMailerDBBundle\WhiteOctoberSwiftMailerDBBundle(),
        // ...
    );
}
```

### 3. Configure the `white_october_swift_mailer_db` in config.yml


``` yaml
white_october_swift_mailer_db:
    entity_class: AppBundle\Entity\Email
```

Read below about how to construct this entity.

### 4. Tell SwiftMailer to use the database spooler

``` yaml
swiftmailer:
    spool:
        type: db
```

That's it for bundle installation and configuration.

## Mail entity

You will need to create an entity that can be persisted and that extends from the
`EmailInterface` interface in the bundle.  At the moment, the bundle expects a
property to be available on your entity called 'status', since this field is queried.

Once you have your entity all set up, use the full namespaced path in your `config.yml`
configuration as detailed above.



## Optional: keeping sent messages in the database

By default, messages which were succesfully sent will be deleted from the database. It is possible to configure
the bundle to keep those messages in your config.yml:

``` yaml
white_october_swift_mailer_db:
    keep_sent_messages: true
```

## Optional: using separate entity manager for emails

When a message is sent with configured database spool `$em->flush` is called on default entity manager. This may
cause side effects, so in order to flush only Email entity, put it in a separate bundle and configure separate
entity manager for that bundle. For example:


```
white_october_swift_mailer_db:
    entity_class: MailBundle\Entity\Email

doctrine:
    orm:
        entity_managers:
            default:
                connection: default
                auto_mapping: true
            mail:
                connection: default
                mappings:
                    MailBundle: ~
```

## Contributing

We welcome contributions to this project, including pull requests and issues (and discussions on existing issues).

If you'd like to contribute code but aren't sure what, the [issues list](https://github.com/whiteoctober/WhiteOctoberSwiftMailerDBBundle/issues) is a good place to start.
If you're a first-time code contributor, you may find Github's guide to [forking projects](https://guides.github.com/activities/forking/) helpful.

All contributors (whether contributing code, involved in issue discussions, or involved in any other way) must abide by our [code of conduct](https://github.com/whiteoctober/open-source-code-of-conduct/blob/master/code_of_conduct.md).
