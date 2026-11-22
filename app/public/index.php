<?php

session_start();

require_once '../core/Database.php';
require_once '../core/Router.php';

require_once '../models/User.php';

require_once '../controllers/AuthController.php';
require_once '../controllers/DashboardController.php';

$router = new Router();

$router->get('login', AuthController::class, 'showLogin');
$router->post('login', AuthController::class, 'authenticate');

$router->get('dashboard', DashboardController::class, 'index');

$router->post('logout', AuthController::class, 'logout');

$router->dispatch();