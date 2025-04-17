<?
if (!$ank['background']) {
    echo '<span></span>';
} else {
    echo '<style> 
:root { --back-image: url(' . $ank['background'] . '); }
</style>';
}

if (!$ank['interface_color']) {
    echo '<span></span>';
} else {
    echo '<style> 
:root { --items-color: ' . $ank['interface_color'] . '; }
</style>';
}
