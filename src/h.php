<?php

// This file is intentionally not namespaced

function h()
{
    return \Dxw\Iguana\Registrar::getSingleton()->getInstance('Dxw\\Iguana\\Helpers');
}
