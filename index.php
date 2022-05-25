<?php

use controllers\AuthController;
use controllers\FaultController;
use controllers\RemarkController;
use core\App;
use core\Request;

require_once('./core/autoload.php');

$app = new App;

$app->router->get('/user', []);

$app->router->get('/faults',[FaultController::class,'getFaults'] );

$app->router->get('/getCount',[FaultController::class,'countFaults'] );

$app->router->get('/getRemark',[RemarkController::class,'getRemark'] );

$app->router->get('/getAllRemarks',[RemarkController::class,'getAllRemarks'] );

$app->router->post('/login', [AuthController::class, 'login']);

$app->router->post('/token', [AuthController::class, 'validate']);

$app->router->post('/register', [AuthController::class, 'register']);

$app->router->post('/forgotPassword', [AuthController::class, 'forgotPassword']);

$app->router->post('/validateToken', [AuthController::class, 'validateToken']);

$app->router->post('/reset', [AuthController::class, 'reset']);

$app->router->post('/verifyCode', [AuthController::class, 'verifyCode']);

$app->router->post('/reportFault', [FaultController::class, 'reportFault']);

$app->router->post('/updateFault', [FaultController::class, 'updateFault']);

$app->router->post('/remark', [RemarkController::class, 'remark']);

$app->router->post('/respondRemark', [RemarkController::class, 'respondRemark']);

$app->run();
