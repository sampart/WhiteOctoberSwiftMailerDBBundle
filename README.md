This bundle faciliates using a database to spool messages to with SwiftMailer and Symfony2.

At present, it only works with the Doctrine EntityManager and entities managed with this.

Installation
============

  1. Add this bundle to your vendor/ dir using the vendors script:

    Add the following lines in your ``deps`` file:

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

  3. Add this bundle to your application's kernel:

        // app/AppKernel.php
        public function registerBundles()
        {
            return array(
                // ...
                new WhiteOctober\SwiftMailerDBBundle\WhiteOctoberSwiftMailerDBBundle(),
                // ...
            );
        }

  4. Configure the `wo_swiftmailer_db` service in your config.yml:

        wo_swiftmailer_db:
            entity_class: Full\Path\To\Mail\Entity

  5. Tell SwiftMailer to use the database spooler:

        swiftmailer:
            spool:
                type: db

That's it for bundle configuration.

Mail entity
===========

You will need to create an entity that can be persisted and that extends from the
`EmailInterface` interface in the bundle.  At the moment, the bundle expects a
property to be available on your entity called 'status', since this field is queried.

Once you have your entity all set up, use the full namespaced path in your `config.yml`
configuration as detailed above.


