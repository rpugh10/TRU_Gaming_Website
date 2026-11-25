<?php

session_start();

require_once '../core/Database.php';
require_once '../core/Router.php';
require_once '../core/Auth.php';

require_once '../models/User.php';
require_once '../models/Member.php';
require_once '../models/Event.php';

require_once '../controllers/AuthController.php';
require_once '../controllers/DashboardController.php';
require_once '../controllers/MemberController.php';
require_once '../controllers/EventController.php';

$router = new Router();

$router->get('login', AuthController::class, 'showLogin');
$router->post('login', AuthController::class, 'authenticate');

$router->get('dashboard', DashboardController::class, 'index');

$router->post('logout', AuthController::class, 'logout');

$router->get('members', MemberController::class, 'index');
$router->get('members/create', MemberController::class, 'showCreate');
$router->post('members/create', MemberController::class, 'create');
$router->get('members/edit', MemberController::class, 'showEdit');
$router->post('members/edit', MemberController::class, 'update');
$router->post('members/delete', MemberController::class, 'delete');

$router->get('events', EventController::class, 'index');
$router->get('events/create', EventController::class, 'showCreate');
$router->post('events/create', EventController::class, 'create');
$router->get('events/edit', EventController::class, 'showEdit');
$router->post('events/edit', EventController::class, 'update');
$router->post('events/delete', EventController::class, 'delete');

$router->dispatch();
