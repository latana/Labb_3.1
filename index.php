<?php

require_once("Controller/controller.php");

setlocale(LC_ALL, "sv_SE", "sv_SE.utf-8", "sv", "swedish");

session_start();

$startEngine = new \controller\Controller();

