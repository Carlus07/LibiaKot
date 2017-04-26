<?php

require_once "src/bootstrap.php";
require_once "src/Models/Entities/Language.php";
require_once "src/Models/Entities/User.php";
require_once "src/Models/Entities/Role.php";
require_once "src/Models/Entities/Translation.php";
require_once "src/Models/Entities/Label.php";
require_once "src/Models/Entities/Activity.php";
require_once "src/Models/Entities/SubType.php";
require_once "src/Models/Entities/Type.php";
require_once "src/Models/Entities/Locality.php";
require_once "src/Models/Entities/Address.php";
require_once "src/Models/Entities/Housing.php";
require_once "src/Models/Entities/Property.php";

use Controllers\Tools\Router;
use Controllers\Tools\Language;
use Models\Session;

if(session_id() == "") session_start();

Language::getLanguageDefault();
Session::set("ip", $_SERVER['SERVER_ADDR']);

$type = "";
if (!empty($_GET['p'])) $type = "p";
else if (!empty($_GET['r'])) $type = "r";
else if (!empty($_GET['w'])) $type = "w";
else if (!empty($_GET['o'])) $type = "o";

if ($type == "") Router::switchView();
else Router::switchView($_GET[$type], $type);

//php vendor/doctrine/orm/bin/doctrine.php orm:schema-tool:update --force