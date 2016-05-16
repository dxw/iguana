<?php

// This file is intentionally not namespaced

// Multiple themes/plugins may attempt to load Iguana simultaneously
// As such, we must use this dirty hack
if (!function_exists('h')) {
    function h()
    {
        return \Dxw\Iguana\Registrar::getSingleton()->getInstance('Dxw\\Iguana\\Helpers');
    }
}
