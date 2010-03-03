<?php
!function_exists('readover') && exit('Forbidden');
include_once PrintEot ( 'left' );
print <<<EOT
-->
EOT;
require_once $filepath;
include_once PrintEot ( 'adminbottom' );
?>