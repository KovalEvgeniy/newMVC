<?php

if(!function_exists('require_multi')) {
    function require_multi($files) {
        $files = func_get_args();
        foreach($files as $file)
            require_once($file);
    }
}
if(!function_exists('dd')) {
    function dd(...$data)
    {
        var_dump(...$data); die;
    }
}

?>