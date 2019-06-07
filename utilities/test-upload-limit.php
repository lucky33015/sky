<?php

        /**
        * Return ini values in bytes
        *
        * @param string $val value to convert
        *
        * @return value in bytes
        */
        function returnIniBytes($val)
        {
            $val = trim($val);
            $last = strtolower($val[strlen($val)-1]);

            $val = floatval($val);

            switch($last) 
            {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
            }
            return $val;
        }

        /**
        * Server available max upload size
        *
        * @return available max upload size in bytes
        */
        function getMaxUploadSize()
        {
            // select maximum upload size
            $max_upload = (returnIniBytes(ini_get('upload_max_filesize'))/2);
            // select post limit
            $max_post = (returnIniBytes(ini_get('post_max_size'))/2);
            // select memory limit
            $init_memory_limit = ini_get('memory_limit');
            $memory_limit = $init_memory_limit == '-1' ? $max_post : (returnIniBytes($init_memory_limit)/2);
            // get the smallest of them, this defines the real limit
            $available = min($max_upload, $max_post, $memory_limit);
            // return the value in bytes
            return round($available);
        }

        /**
        * Upload chunk size
        * 8M if the server can upload at least 16M.
        *
        * @return chunksize in bytes
        */
        function getChunkSize()
        {
            $serverSize = getMaxUploadSize();
            $idealSize = 32*1048576; // 1048576 == 1MB;
            return min($serverSize, $idealSize);
        }

echo "up max: ".ini_get('upload_max_filesize').'<br>';
echo "post max: ".ini_get('post_max_size').'<br>';
echo "mem max: ".ini_get('memory_limit').'<br>';

        echo getChunkSize();
