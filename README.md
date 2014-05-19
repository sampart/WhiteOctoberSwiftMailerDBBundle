This bundle faciliates using a database to spool messages to with SwiftMailer and Symfony2.

At present, it only works with the Doctrine EntityManager and entities managed with this.

Installation
============

First of all, get the bundle into your project.

**Via Composer**:

  1. Add the following line to your composer.json require section:

        {
            "require": {
                "whiteoctober/swiftmailerdbbundle": "1.0.1"
            }
        }

  2. Download the bundle via Composer:

        $ php composer.phar update whiteoctober/swiftmailerdbbundle

**Via the deps files**:

  1. Add the following lines in your ``deps`` file:

        [WhiteOctoberSwiftMailerDBBundle]
            git=git://github.com/whiteoctober/WhiteOctoberSwiftMailerDBBundle.git
            target=/bundles/WhiteOctober/SwiftMailerDBBundle

    Run the vendors script:

        ./bin/vendors install

  2. Add the WhiteOctober namespace to your autoloader:

        // app/autoload.php
        $loader->registerNamespaces(array(
            'WhiteOctober' => __DIR__.'/../vendor/bundles',
        ));

Once you've got the bundle downloaded in your Symfony project, you'll need to add it to the kernel,
and add some configuration parameters, so that it knows which entity you want to use.

  1. Add the bundle to your application's kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            return array(
                // ...
                new WhiteOctober\SwiftMailerDBBundle\WhiteOctoberSwiftMailerDBBundle(),
                // ...
            );
        }

  2. Configure the `white_october_swift_mailer_db` service in your config.yml:

        white_october_swift_mailer_db:
            entity_class: Full\Path\To\Mail\Entity

     Read below about how to construct this entity.

  3. Tell SwiftMailer to use the database spooler:

        swiftmailer:
            spool:
                type: db

That's it for bundle installation and configuration.



Mail entity
===========

You will need to create an entity that can be persisted and that extends from the
`EmailInterface` interface in the bundle.  At the moment, the bundle expects a
property to be available on your entity called 'status', since this field is queried.

Once you have your entity all set up, use the full namespaced path in your `config.yml`
configuration as detailed above.



Optional: keeping sent messages in the database
===============================================

By default, messages which were succesfully sent will be deleted from the database. It is possible to configure
the bundle to keep those messages in your config.yml:

    white_october_swift_mailer_db:
        keep_sent_messages: true