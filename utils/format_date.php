<?php 
    function format_date($date, $format = "h:iA, F d, Y") {
        echo date_format(date_create($date),$format);
    }
?>