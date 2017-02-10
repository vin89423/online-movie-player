<?php

/*
 * Copyright 2017 Vin Wong @ vinexs.com	(MIT License)
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *    This product includes software developed by the <organization>.
 * 4. Neither the name of the <organization> nor the
 *    names of its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY <COPYRIGHT HOLDER> ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

class MainApp extends Index
{
    public $user = null;

    function __construct()
    {
    }

    // ==============  Custom Handler  ==============

    function handler_index()
    {
        $vars = array();
        $this->load_view('player', $vars);
    }

    function handler_poster($url)
    {
        $url = $this->get('url', 'url');
        if (empty($url)) {
            die('File not found');
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $raw = curl_exec($ch);
        curl_close($ch);
        if (!$raw) {
        	header('HTTP/1.0 404 Not Found');
        	die('File not found in '.$url);
        }

        header('Content-Type: image/jpg;');
        echo $raw;
    }

    function handler_default($url)
    {
        $category_name = $url[0];
        if (empty($this->setting['MOVIE'][$category_name])) {
            $this->show_error(404);
        }

        $vars = array(
            'category_name' => $category_name,
            'category' => $this->setting['CATEGORY'][$category_name]
        );

        $this->load_view('player', $vars);
    }

	function handler_movie_list($url)
	{
        $category = isset($url[0]) ? $url[0] : 'default';
        if (empty($this->setting['MOVIE'][$category])) {
            return $this->show_json(false, 'category_not_found');
        }
        $result = array(
            'category' => ($category == 'default') ? $this->setting['CATEGORY'] : null,
            'movies' => $this->setting['MOVIE'][$category],
        );

        $this->show_json(true, $result);
	}

    /*
    function handler_movie($url)
    {
        if (empty($url[0])) {
            $this->show_error(404);
        }
        $category = empty($url[1]) ? 'default' : $url[0];
        $filename = empty($url[1]) ? $url[0] : $url[1];

        $path = ROOT_FOLDER.'movies/'. ($category != 'default' ? $category.'/' : '') . $filename;

        $this->load_file($path);
    }
    */

    // Add handler here ...

    /** Allow developer to custom error response. */
    function show_error($error, $line = null)
    {
        parent::show_error($error, $line);
    }

    /** For spider to read robots.txt. */
    function handler_robots_txt()
    {
        return $this->load_file(ASSETS_FOLDER . 'robots.txt');
    }

    //  ==============  Handle Error  ==============

    /** For browser to read favicon.ico unless layout do not contain one. */
    function handler_favicon_ico()
    {
        return $this->load_file(ASSETS_FOLDER . 'favicon.ico');
    }

    //  ==============  Layout variable  ==============

    /** Add activity base variable to view. */
    function load_default_vars()
    {
        parent::load_default_vars();
        $this->vars['URL_REPOS'] = '//www.vinexs.com/repos/';
        $this->vars['URL_ASSETS'] = $this->vars['URL_ASSETS'];
        $this->vars['URL_RSC'] = $this->vars['URL_ASSETS'] . $this->manifest['activity_current'] . '/';
    }

}
