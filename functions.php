<?php
require_once 'class/_sTheme.php';

class nameOfNewTheme extends _sTheme {

}

if(! isset($theme)) {
    $theme = new nameOfNewTheme();
}