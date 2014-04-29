This bundle facilitates using a database to spool messages to with SwiftMailer and Symfony2.

You can choose from either Doctrine ORM or ODM (MongoDB) depending on your projects db.

Installation
============

First of all, get the bundle into your project.

Using Composer:
-------------

  1. Add the following line to your composer.json require section:

        {
            "require": {
                "whiteoctober/swiftmailerdbbundle": "1.0.1"
            }
        }

  2. Download the bundle via Composer:

        $ php composer.phar update whiteoctober/swiftmailerdbbundle

Using the deps files:
-------------------

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

Configuration
=============

Once you've got the bundle downloaded in your Symfony project, you'll need to add it to the kernel,
and add some configuration parameters, so that it knows which entity/document you want to use.

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

     a. Using Doctrine ORM:

        white_october_swift_mailer_db:
            driver: orm
            model:  Full\Path\To\Email\Entity

     b. Using Doctrine ODM (MongoDB):

        white_october_swift_mailer_db:
            driver: odm
            model:  Full\Path\To\Email\Document

     Read below about how to construct your entity or document class.

  3. Tell SwiftMailer to use the database spooler:

        swiftmailer:
            spool:
                type: db

That's it for bundle installation and configuration.

Email Entity/Document
========================

You will need to create an entity or document that can be persisted and that implements the
`EmailInterface` interface in the bundle. At the moment, the bundle expects a property to be
available on your entity or document called 'status', since this field is queried.

Once you have your entity or document all set up, use the full namespaced path in your `config.yml`
configuration as detailed above.
