<?php

$phone = "0470.04.92.85";

if (!preg_match('#^0[1-68]([-. ]?[0-9]{2}){4}$#', $phone)) echo "echec";