<?php
include './vendor/autoload.php';

use App\Html\PageCounties;
use App\Html\Request;

PageCounties::head();
//PageCounties::tableHead();
PageCounties::nav();
Request::handle();
PageCounties::footer();