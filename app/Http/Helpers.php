<?php

use Carbon\Carbon;

function assetVerion($file)
{
    $version = env('VERSION', '') != ""
        ? env('VERSION', '')
        : Carbon::now()->timestamp;

    return asset($file) . "?ver=". $version;
}


?>
