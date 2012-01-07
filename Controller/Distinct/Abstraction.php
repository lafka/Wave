<?php
/**
 * Wave PHP 
 *
 * Copyright (c) 2011, 2010 - 2011 Frengstad Web Teknologi and contributors 
 * All rights reserved
 *
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of
 * conditions and the following disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice, this list
 *  of conditions and the following disclaimer in the documentation and/or other materials
 *  provided with the distribution.
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
 * Basic abstraction for controllers 
 *
 * @package     wave
 * @version     0.1 
 * @copyright   Frengstad Web Teknologi 
 * @author      Olav Frengstad <olav@fwt.no>
 * @license     http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace Fwt\Controller\Distinct;

use \UnexpectedValueException, \Fwt\Base, \Fwt\Controller\Simple,
    \Fwt\Controller\Iface;

abstract class Abstraction extends Simple\Abstraction 
{
	/**
	 * The name of the subfolder where views are included
	 *
	 * @var string
	 */
	const SUBFOLDER = "views";

	/**
	 * Fetches all available views
	 *
	 * @return array All available view in {@link self::SUBFOLDER}
	 */
	public function availableViews ()
	{
		$path = buildpath( __ROOT__, $this->_package['path'], 
		                   $this->_package['components'][$this->_request['controller']],
		                   static::SUBFOLDER );

		$dir = dir( $path );

		$views = array();

		while( false !== ($file = $dir->read()) )
		{
			if ( '.' === substr( $file, 0, 1 ) || '.php' !== substr( $file, -4 ) )
			{
				continue;
			}

			$views[] = substr( $file, 0, -4 );
		}

		$dir->close();

		unset( $dir, $file, $path );

		return $views;
	}

	/**
	 * Include a view
	 * 
	 * Loads up the given view if it exists
	 * 
	 * @param string $view The view to use
	 * @return void
	 * @throws UnexpectedValueException
	 */
	public function loadView ( $view = \Fwt\Controller\Iface::USE_CURRENT_VIEW )
	{
		if ( $view === Iface::USE_CURRENT_VIEW )
		{
			$view = $this->currentView();
		} 

		if ( $this->hasView( $view ) )
		{
			include buildpath(__ROOT__, $this->_package['path'], 
			                   $this->_package['components'][$this->_request['controller']],
			                   static::SUBFOLDER, $view . '.php' );
			return;
		}

		throw new UnexpectedValueException( __METHOD__ . " could not load view {$view}, it was not found in the {$this->_package['package']}.{$this->_request['controller']}} package" );
	}
	
	/**
	 * By default initialization is not required
	 * 
	 * @return boolean True
	 */
	public function init ()
	{
		return true;
	}
}

