<!DOCTYPE HTML>
<html>
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
    	<?php
            function valid_be_phone_number($number) {
            $ret = false;
            $number = trim($number);
            // Patterns
            $pattern = '/^((\+|00)32\s?|0)(\d\s?\d{3}|\d{2}\s?\d{2})(\s?\d{2}){2}$/';
            $pattern_mobile = '/^((\+|00)32\s?|0)4(60|[789]\d)(\s?\d{2}){3}$/';
            // Matches
            if (preg_match($pattern, $number, $matches)) {
                $ret = "fixe";
            } else if (preg_match($pattern_mobile, $number, $matches)) {
                $ret = "mobile";
            }
            else $ret = "error";
            return $ret;
        }
        echo valid_be_phone_number("+3287128454");
        ?>
    </body>
</html>