<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2010 - 2011 Frengstad Web Teknologi and contributors  
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 *
 * Redistributions in binary form must reproduce the above copyright notice, this list
 * of conditions and the following disclaimer in the documentation and/or other materials
 * provided with the distribution.
 *
 * Neither the name of the Wave PHP Team nor the names of its contributors may be used
 * to endorse or promote products derived from this software without specific prior
 * written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS AS IS AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
 * AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS
 * AND CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * Index file for loading requets 
 *
 * @package	  wave 
 * @version	  0.1 
 * @copyright Frengstad Web Teknologi	
 * @author	  Olav Frengstad <olav@fwt.no>
 * @license	  http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Fwt;
use \Fwt\Controller\Helper, Exception;

define( '__DS__',          DIRECTORY_SEPARATOR );
define( '__ROOT__',        __DIR__ . __DS__ );
define( '__DEBUG_ENABLED', true );

include __ROOT__ . 'Fwt/Base.php';

global $_debug_array;

try {
	$base       = new Base();
	$base->init();
	$base->parsePackages();

	$parts	    = Helper::process( $_SERVER['REQUEST_URI'] );
	$controller = Helper::factory( $parts, $base );

	include 'presentation/header.php';

	if ( ! $controller->hasView( $parts['view'] ) )
	{
		Helper::loadError( 404, $controller );
	} else {
		//	If something wrong happend this means we shouldent load the view
		//	this is a hack to stop unauthorized requests and similar.
		//	@todo This should atleast check if some view has been loaded
		$controller->loadView( $parts['view'] );
	}

	include 'presentation/footer.php';

	// Where done
	define('_RUNTIME_DONE');
} catch ( Exception $e ) {
	header( "Status: 500" );
	echo "<div class=\"error\"><pre>
	{$e->getMessage()}<hr />
	in {$e->getFile()} on line {$e->getLine()}
	</pre><hr /><pre>{$e->getTraceAsString()}</pre></div>";
}

