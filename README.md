Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony2
application that you can use as the skeleton for your new applications.

This document contains information on how to download, install, and start
using Symfony. For a more detailed explanation, see the [Installation][1]
chapter of the Symfony Documentation.

1) Installing the Standard Edition
----------------------------------

When it comes to installing the Symfony Standard Edition, you have the
following options.

### Use Composer (*recommended*)

As Symfony uses [Composer][2] to manage its dependencies, the recommended way
to create a new project is to use it.

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the `create-project` command to generate a new Symfony application:

    php composer.phar create-project symfony/framework-standard-edition path/to/install

Composer will install Symfony and all its dependencies under the
`path/to/install` directory.

### Download an Archive File

To quickly test Symfony, you can also download an [archive][3] of the Standard
Edition and unpack it somewhere under your web server root directory.

If you downloaded an archive "without vendors", you also need to install all
the necessary dependencies. Download composer (see above) and run the
following command:

    php composer.phar install

2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

    php app/check.php

The script returns a status code of `0` if all mandatory requirements are met,
`1` otherwise.

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them before moving on.

3) Browsing the Demo Application
--------------------------------

Congratulations! You're now ready to use Symfony.

From the `config.php` page, click the "Bypass configuration and go to the
Welcome page" link to load up your first Symfony page.

You can also use a web-based configurator by clicking on the "Configure your
Symfony Application online" link of the `config.php` page.

To see a real-live Symfony page in action, access the following page:

    web/app_dev.php/demo/hello/Fabien

4) Getting started with Symfony
-------------------------------

This distribution is meant to be the starting point for your Symfony
applications, but it also contains some sample code that you can learn from
and play with.

A great way to start learning Symfony is via the [Quick Tour][4], which will
take you through all the basic features of Symfony2.

Once you're feeling good, you can move onto reading the official
[Symfony2 book][5].

A default bundle, `AcmeDemoBundle`, shows you Symfony2 in action. After
playing with it, you can remove it by following these steps:

  * delete the `src/Acme` directory;

  * remove the routing entry referencing AcmeDemoBundle in `app/config/routing_dev.yml`;

  * remove the AcmeDemoBundle from the registered bundles in `app/AppKernel.php`;

  * remove the `web/bundles/acmedemo` directory;

  * empty the `security.yml` file or tweak the security configuration to fit
    your needs.

What's inside?
---------------

The Symfony Standard Edition is configured with the following defaults:

  * Twig is the only configured template engine;

  * Doctrine ORM/DBAL is configured;

  * Swiftmailer is configured;

  * Annotations for everything are enabled.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * [**AsseticBundle**][12] - Adds support for Assetic, an asset processing
    library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev/test env) - Adds code generation
    capabilities

  * **AcmeDemoBundle** (in dev/test env) - A demo bundle with some example
    code

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  http://symfony.com/doc/2.4/book/installation.html
[2]:  http://getcomposer.org/
[3]:  http://symfony.com/download
[4]:  http://symfony.com/doc/2.4/quick_tour/the_big_picture.html
[5]:  http://symfony.com/doc/2.4/index.html
[6]:  http://symfony.com/doc/2.4/bundles/SensioFrameworkExtraBundle/index.html
[7]:  http://symfony.com/doc/2.4/book/doctrine.html
[8]:  http://symfony.com/doc/2.4/book/templating.html
[9]:  http://symfony.com/doc/2.4/book/security.html
[10]: http://symfony.com/doc/2.4/cookbook/email.html
[11]: http://symfony.com/doc/2.4/cookbook/logging/monolog.html
[12]: http://symfony.com/doc/2.4/cookbook/assetic/asset_management.html
[13]: http://symfony.com/doc/2.4/bundles/SensioGeneratorBundle/index.html


######################################################################################################
#####################################################################################################
Commandline
----------------------------------------------------------------------------------------------------
create bundle	            php app/console generate:bundle --namespace=Acme/HelloBundle --format=yml
		                    -> creates src/… & extends app/appkernel.php & app/config/routing.yml

Cache clear	                php app/console cache:clear —env=prod —no-debug
		                    -> necessary when debug is inactive like in prod environment

Base Controller Services    php app/console container:debug

List Routes                 php app/console router:debug

Route <-> URL               php app/console router:match /givenPage


include assets in bundle    php app/console assets:install web --symlink


check twig syntax           php app/console twig:lint path_of_bundle|folder|twig-file


Tests
-----------------------------------------------------------------------------------------------
run phpunit tests           php phpunit -c app src/Barra/BackBundle



DB
---------------------------------------------------------------------------------------------------

create & fields             php app/console doctrine:generate:entity --entity="BarraDefaultBundle:Product"
updates get/set/repo        php app/console doctrine:generate:entities Barra


create DB                   php app/console doctrine:database:create
cr/update t                 php app/console doctrine:schema:update --force
delete DB                   php app/console doctrine:database:drop --force







######################################################################################################
######################################################################################################


Routing
------------------------------------------------------------------------------------------------------
User-Agent                  Condition:"request.headers.get('User-Agent') matches'/firefox/i'
                            -> not taken into account when generating url



#####################################################################################################
#####################################################################################################
Controller
----------------------------------------------------------------------------------------------------
Response Obj	            use Symfony\Component\HttpFoundation\Response;

// without URL change:      return $this->forward('BarraDefaultBundle:Default:recipe', array('id' => 1));
// with URL change:         return $this->redirect($this->generateUrl('barra_default_recipe', array('id' => 1))); // = return new RedirectResponse(...);
// for absolute links "true" as 3th param

public function checkRequest(Request $request) {
        $request->isXmlHttpRequest(); // Ajax
        $request->getPreferredLanguage(array('de', 'en'));
        $request->query->get('page'); // $_GET
        $request->request->get('page'); // $_POST
    }


template in different formats       $format = $this->getRequest()->getRequestFormat(); && render...



DB
############################################################################################################
###########################################################################################################

Controller - Einfache selects
    $repository = $this->getDoctrine()->getRepository('BarraDefaultBundle:Recipe')
    $repository
    ->find('foo') 1 with PK
    ->findOneByColumnName('c1') 1 with c1
    ->findOneBy(array('c1'=>'foo', 'c2'=>'bar')) 1 with c1&c2
    ->findByPrice(12.32) more with c1
    ->findBy(array('c1'=>'foo'), array('c2'=>'ASC')) more ordered by c2
    ->findAll();


DB - Repository
 public function findMaxId()
    {
        $query = $this->createQueryBuilder('ri')
            ->select('MAX(ri.id)')
            ->getQuery();

        $recipes = $query->getSingleResult();

        if ($recipes[1] == null)
            return 0;

        return $recipes[1];
    }

######################################################################################################
######################################################################################################
Template
--------------------------------------------------------------------------------------------------------
{% for i in 0..10 %}
    <div class="{{ cycle(['odd', 'even'], i) }}"> ....

{{ include('BarraDefaultBundle:References:article.html.twig', {'id': 3}) }}
include instead of inherit

{{ render(controller('BarraDefaultBundle:References:show', {'id': 3} )) }}
include a complete controller with template for db queries


Asynchronus include via hinclude.js
    {{ render_include(url(...)) }}
    {{ render_include(controller(...)) }}
        -> for controller add "fragments: { path: /_fragment } to framework: at app/config/config.yml
        -> default content when js is deactive also there
            framework:
                templating:
                    hinclude_default_template: BarraDefaultBundle::hinclude.html.twig
        -> ovveride this default via:
            {{ render_hinclude(controller('...'), { 'default': '.....twig'}) }}


bundle template override: app/Resources/myDemoBundle/views/[SomeController/]newPage.html.twig
    -> cache clear may be necessary

######################################################################################################
######################################################################################################
Functional & Unit Tests



---------------------------------------------------------------------------------------------------------------------

newRecipeIngredient->setIngredient($ingredient)
    => aktuallisiert autom.  Recipe->RecipeIngredient
    => Recipe->getRecipeIngredients() funktioniert automatisch


Ingredient->removeRecipeIngredient($recipeIngredient)
    => entfernt nur den Attributswert im Datensatz
    => unpraktisch, da Datensatz (relation) erhalten bliebe
    => wirft sowieso fehlermeldung, da Attribut auf not nullable gesetzt


Ingredient->addRecipeIngredient($recipeIngredient)
    => unnütz. das recipeIngredient müsste bereits bestehen
    => verlinkung zu Ingredient wird autom. gesetzt