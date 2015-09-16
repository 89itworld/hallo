<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 
 */
Router::connect('/dealers/viewalldeal', array('controller' => 'tests', 'action' => 'redirs'));
Router::connect('/mobiltelefon/mobilpriser/*', array('controller' => 'homes', 'action' => 'mobilpriser'));
Router::connect('/mobiltelefon/*', array('controller' => 'homes', 'action' => 'mobilvariant'));
 Router::connect('/nyheder.rss', array('controller' => 'products', 'action' => 'newsfeed'));
 //Router::parseExtensions('rss','xml');
 
	Router::connect('/', array('controller' => 'Homes', 'action' => 'index'));
       // Router::connect('homes/search/:slug', array('controller' => 'Homes', 'action' => 'search'));
      
	Router::connect('/search/:slug', array('controller' => 'Homes', 'action' => 'search'),array('pass' => array('slug'))); 

	Router::connect('/admin', array('controller' => 'admins', 'action' => 'alert', 'admin' => true));
        
Router::connect('/unauthorized', array('controller' => 'admins', 'action' => 'unauthorized', 'admin' => true));
//Set Route for Static Pages by SPN on 20th Dec 2011
Router::connect('/:slug.html', array('controller' => 'staticpages', 'action' => 'index' ));

Router::connect('/nyheder',array('controller' => 'nyheders'));
//Router::connect('/nyheder/',array('controller' => 'nyheders'));


Router::connect('/nyheder/search',array('controller' => 'nyheders', 'action' => 'search_data'));

Router::connect('/nyheder/mobiltelefoner/*',array('controller' => 'nyheders', 'action' => 'categorydata'));
Router::connect('/mobil/mobilpriser/*',array('controller' => 'products', 'action' => 'dprice'));
Router::connect('/mobil/mobilspecifikationer/*',array('controller' => 'products', 'action' => 'specifikationer'));
Router::connect('/mobil/sammenlign/*',array('controller' => 'products', 'action' => 'sammenlign'));
Router::connect('/mobil/brugeranmeldelser/*',array('controller' => 'products', 'action' => 'brugeranmeldelser'));
Router::connect('/mobil/kort/*',array('controller' => 'products', 'action' => 'kort'));

Router::connect('/nyheder/categorydata/*',array('controller' => 'nyheders', 'action' => 'categorydata'));

Router::connect('/nyheders/submit_comments', array('controller' => 'nyheders','action' => 'submit_comments'));
Router::connect('/nyheder/submit_comments', array('controller' => 'nyheders','action' => 'submit_comments'));

/*Router::connect('/nyheders/new_index', array('controller' => 'nyheders', 'action' => 'new_index'));*/

Router::connect('/nyheder/:slug', array('controller' => 'nyheders', 'action' => 'new_details'),array('pass' => array('slug')));

Router::connect('/nyheders/:slug', array('controller' => 'nyheders', 'action' => 'new_details'),array('pass' => array('slug')));

//Router::connect('/brands/:slug', array('controller' => 'brands', 'action' => 'manufacturer'),array('pass' => array('slug')));
 
Router::connect('/brands/:slug', array('controller' => 'brands', 'action' => 'manufacturernew'),array('pass' => array('slug')));
//Router::connect('/:slug', array('controller' => 'brands', 'action' => 'manufacturernew'),array('pass' => array('slug'))); 
 
 //sept/07/2011
/*
Router::connect('/products/dprice/:slug/:id',array('controller' => 'products', 'action' => 'dprice'),
array('pass' => array('slug' , 'id'),'id' => '[0-9]+'));
*/

/*
 Router::connect('/:slug/:mtlf',array('controller' => 'brands', 'action' => 'manufacturer'),
array('pass' => array('slug','mtlf')));
*/
//ef

	
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
