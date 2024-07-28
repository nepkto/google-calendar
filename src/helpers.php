<?php

    /**
     * Get the base url
     */
    function baseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $hostName = $_SERVER['HTTP_HOST'];
        $currentPath = $_SERVER['PHP_SELF'];
        $pathInfo = pathinfo($currentPath);
        $directory = $pathInfo['dirname'];
        $baseUrl = $protocol . $hostName . $directory;
        if (substr($baseUrl, -1) !== '/') {
            $baseUrl .= '/';
        }
        return $baseUrl;
    }

    /**
     * Check the request is ajax or not
     */
    function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

?>