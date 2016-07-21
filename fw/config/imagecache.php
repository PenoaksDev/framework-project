<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Name of route
	|--------------------------------------------------------------------------
	|
	| Enter a route name to enable dynamic imagecache manipulation.
	| This handle will define the first part of the URI:
	|
	| {route}/{template}/{filename}
	|
	| Examples: "images", "img/cache"
	|
	*/

	'route' => 'images',

	/*
	|--------------------------------------------------------------------------
	| Storage paths
	|--------------------------------------------------------------------------
	|
	| The following paths will be searched for the image filename, submitted
	| by URI.
	|
	| Define as many directories as you like.
	|
	*/

	'paths' => [
		$fw->buildPath( null, 'storage' ),
		$fw->buildPath( 'images', 'base' )
	],

	/*
	|--------------------------------------------------------------------------
	| Manipulation templates
	|--------------------------------------------------------------------------
	|
	| Here you may specify your own manipulation filter templates.
	| The keys of this array will define which templates
	| are available in the URI:
	|
	| {route}/{template}/{filename}
	|
	| The values of this array will define which filter class
	| will be applied, by its fully qualified name.
	|
	*/

	'templates' => [
		'small' => 'Intervention\Image\Templates\Small',
		'medium' => 'Intervention\Image\Templates\Medium',
		'large' => 'Intervention\Image\Templates\Large',
	],

	/*
	|--------------------------------------------------------------------------
	| Image Cache Lifetime
	|--------------------------------------------------------------------------
	|
	| Lifetime in minutes of the images handled by the imagecache route.
	|
	*/

	'lifetime' => 43200,

];