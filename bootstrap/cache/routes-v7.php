<?php

/*
|--------------------------------------------------------------------------
| Load The Cached Routes
|--------------------------------------------------------------------------
|
| Here we will decode and unserialize the RouteCollection instance that
| holds all of the route information for an application. This allows
| us to instantaneously load the entire route map into the router.
|
*/

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::QUtWvctwnuK7OeOm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8vEc1UpMpx1TC6vJ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zM9ODhFQO9uDDkyf',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/me' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::17NDBfab0yZXJA5c',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'user.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/get-all-users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OTBDfkWBDaob1eXI',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user-by-user-id' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uILr11zIw7iiy4oL',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user-profile-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xhEMUelWlwEZ4Tyy',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/menu' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'menu.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'menu.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/get-all-menu' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uYeAUAVr84Irr09R',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/sidebar-get-all-user-menu' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::opm0CZPQRwZtleKc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/save-user-menu-permission' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dDFs2Txt70I9fnLq',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/role' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'role.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlets/active' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YGWu8CUjoND8BlrN',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::E70ep5lgFc6eG7dr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6j6nRwEcbdF9GepH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-session/switch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MDhRxDHqZ2LxaxYn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-session/test' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mgjZPnwUBIU82piT',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-session/current' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DknXm5624ZpSzork',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-session/clear' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9FuiSYdLMudx1XA1',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-session/execute' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::V8H8amp4zIv3nJRj',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-connection/outlets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jVtvW97umuLQdESJ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-connection/test' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DsxMGpGN8vK1RgTw',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/outlet-connection/set-active' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::j9RvNnpmuzcBUvPB',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/mobile-terminals' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::089opX3tjdagELsN',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8BIWS5y6jJC6lKzB',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/mobile-terminals/device-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bF1JpDfN2rZmSNm8',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/mobile-terminals/printer-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jACawimTcZKlwiR4',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-logs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HCvtLOtMJddirlMV',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cB0l635NZk0yXRlA',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-logs/payment-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q7d5dcggbv6wvFEA',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-logs/terminals' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NlVsrR675YyU99bk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-logs/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9KMLdsJ5WlzV7RAm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::89N8ydjqLJrSo7Ri',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0wK9bP1frwMeXLEi',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-success/card-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::e9jxFwUof7xWei4X',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-success/issuers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0gtTp9bIbeN2iym7',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-success/terminals' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IbRafLNfodI7M3Zt',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-success/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qj64SZ14qs41AzEL',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::GgCkbuFaYZps9hLj',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Qkk8PAtSX5HSILGY',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-types/terminals' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zf2EXeDesEDQr6nw',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/payment-types/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jxyTxBjnL4T3CGhr',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/app-versions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cfLmAWmDL5NOlcbe',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Hoa6ZweYovOzxIb5',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/app-versions/packages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xkW7WuKXOBtJoQQy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/app-versions/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rid0EAOHVARBNOLc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/change-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0OJtCIHoOVM74zez',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/api/(?|user/([^/]++)(?|(*:31))|search/(?|user/([^/]++)(*:62)|menu/([^/]++)(*:82)|role/([^/]++)(*:102))|m(?|enu/([^/]++)(?|(*:130))|obile\\-terminals/([^/]++)(?|(*:167)|/toggle\\-sync(*:188)))|get\\-user\\-menu\\-details/([^/]++)(*:231)|role/([^/]++)(?|(*:255))|outlets/(?|([^/]++)(?|(*:286))|search/([^/]++)(*:310)|test\\-connection(*:334))|payment\\-(?|logs/([^/]++)(?|(*:371))|success/([^/]++)(?|(*:399))|types/([^/]++)(?|(*:425)|/toggle(*:440)))|app\\-versions/([^/]++)(?|(*:475)|/toggle\\-status(*:498)))|/(.*)?(*:514))/?$}sDu',
    ),
    3 => 
    array (
      31 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'user.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'user.destroy',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      62 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qeXGXzngfAlAVaqW',
          ),
          1 => 
          array (
            0 => 'query',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      82 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3TBaqJAeCEbIsRz5',
          ),
          1 => 
          array (
            0 => 'query',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      102 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::w180a8iHTJakEEC5',
          ),
          1 => 
          array (
            0 => 'query',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      130 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'menu.show',
          ),
          1 => 
          array (
            0 => 'menu',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'menu.update',
          ),
          1 => 
          array (
            0 => 'menu',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'menu.destroy',
          ),
          1 => 
          array (
            0 => 'menu',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      167 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jnYzwdAQW28gmWMh',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nHt7E24rBBqFdfMA',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Bptu9A4FPqFjW1iG',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      188 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2qDoM2c1msUKVEeV',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      231 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jmwygYt1O8bkLRAy',
          ),
          1 => 
          array (
            0 => 'UserID',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      255 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'role.show',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'role.update',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'role.destroy',
          ),
          1 => 
          array (
            0 => 'role',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      286 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OjlFNgIruCJscOEb',
          ),
          1 => 
          array (
            0 => 'outlet',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::nINTnjyDVW1TBLCM',
          ),
          1 => 
          array (
            0 => 'outlet',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AWW59oXRirzcJ3w1',
          ),
          1 => 
          array (
            0 => 'outlet',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      310 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BTwwAm4viv9lRANM',
          ),
          1 => 
          array (
            0 => 'query',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      334 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FhI2p0qRdNjfjRRE',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      371 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Pg1IrvJEoDYzpPBg',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::DKoQQCxXP2diMaq6',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YtwWbcOuc0kkyqHc',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      399 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::r0HkF1e0JvrJQahs',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::dzFgXBJGXxqmnIAq',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4naNWvVBp984Csx3',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      425 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8W3NC1Upx9kO3X16',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::us4QLSwbfzAQFomP',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::9cbrQEfiXSgEKSlQ',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      440 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Py9CDCZ8LiJG0CyL',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      475 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6EdBZaUzW9d2nFzX',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OFK5V4W8k3MxfaaN',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8yO1A3iviuEZsDhO',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      498 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eKwLLNpBPmlVciGS',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      514 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xG8qQLX737xjntnv',
            'app' => NULL,
          ),
          1 => 
          array (
            0 => 'app',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'generated::QUtWvctwnuK7OeOm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'generated::QUtWvctwnuK7OeOm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8vEc1UpMpx1TC6vJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\AuthController@login',
        'controller' => 'App\\Http\\Controllers\\AuthController@login',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8vEc1UpMpx1TC6vJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zM9ODhFQO9uDDkyf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\AuthController@logout',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::zM9ODhFQO9uDDkyf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::17NDBfab0yZXJA5c' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/me',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\AuthController@me',
        'controller' => 'App\\Http\\Controllers\\AuthController@me',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::17NDBfab0yZXJA5c',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'user.index',
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'user.store',
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'user.show',
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/user/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'user.update',
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/user/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'user.destroy',
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qeXGXzngfAlAVaqW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/search/user/{query}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@search',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@search',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::qeXGXzngfAlAVaqW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OTBDfkWBDaob1eXI' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/get-all-users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@getAllUser',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@getAllUser',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::OTBDfkWBDaob1eXI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uILr11zIw7iiy4oL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user-by-user-id',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@getUserByUserId',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@getUserByUserId',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::uILr11zIw7iiy4oL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xhEMUelWlwEZ4Tyy' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/user-profile-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UserController@updateProfile',
        'controller' => 'App\\Http\\Controllers\\Api\\UserController@updateProfile',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::xhEMUelWlwEZ4Tyy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'menu.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/menu',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'menu.index',
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'menu.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/menu',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'menu.store',
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'menu.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/menu/{menu}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'menu.show',
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'menu.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/menu/{menu}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'menu.update',
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'menu.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/menu/{menu}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'menu.destroy',
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3TBaqJAeCEbIsRz5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/search/menu/{query}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@search',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@search',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::3TBaqJAeCEbIsRz5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uYeAUAVr84Irr09R' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/get-all-menu',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MenuController@getAllMenu',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuController@getAllMenu',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::uYeAUAVr84Irr09R',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jmwygYt1O8bkLRAy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/get-user-menu-details/{UserID}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MenuPermissionController@getUserMenuPermission',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuPermissionController@getUserMenuPermission',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::jmwygYt1O8bkLRAy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::opm0CZPQRwZtleKc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/sidebar-get-all-user-menu',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MenuPermissionController@getSidebarAllUserMenu',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuPermissionController@getSidebarAllUserMenu',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::opm0CZPQRwZtleKc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dDFs2Txt70I9fnLq' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/save-user-menu-permission',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MenuPermissionController@saveUserMenuPermission',
        'controller' => 'App\\Http\\Controllers\\Api\\MenuPermissionController@saveUserMenuPermission',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::dDFs2Txt70I9fnLq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'role.index',
        'uses' => 'App\\Http\\Controllers\\Api\\RoleController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\RoleController@index',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/role',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'role.store',
        'uses' => 'App\\Http\\Controllers\\Api\\RoleController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\RoleController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/role/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'role.show',
        'uses' => 'App\\Http\\Controllers\\Api\\RoleController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\RoleController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'api/role/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'role.update',
        'uses' => 'App\\Http\\Controllers\\Api\\RoleController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\RoleController@update',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'role.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/role/{role}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'as' => 'role.destroy',
        'uses' => 'App\\Http\\Controllers\\Api\\RoleController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\RoleController@destroy',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::w180a8iHTJakEEC5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/search/role/{query}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\RoleController@search',
        'controller' => 'App\\Http\\Controllers\\Api\\RoleController@search',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::w180a8iHTJakEEC5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YGWu8CUjoND8BlrN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/outlets/active',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@getActiveOutlets',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@getActiveOutlets',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::YGWu8CUjoND8BlrN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::E70ep5lgFc6eG7dr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/outlets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@index',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::E70ep5lgFc6eG7dr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6j6nRwEcbdF9GepH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@store',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::6j6nRwEcbdF9GepH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OjlFNgIruCJscOEb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/outlets/{outlet}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@show',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::OjlFNgIruCJscOEb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nINTnjyDVW1TBLCM' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/outlets/{outlet}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@update',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::nINTnjyDVW1TBLCM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AWW59oXRirzcJ3w1' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/outlets/{outlet}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::AWW59oXRirzcJ3w1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BTwwAm4viv9lRANM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/outlets/search/{query}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@search',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@search',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::BTwwAm4viv9lRANM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FhI2p0qRdNjfjRRE' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlets/test-connection',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletController@testConnection',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletController@testConnection',
        'namespace' => NULL,
        'prefix' => 'api/outlets',
        'where' => 
        array (
        ),
        'as' => 'generated::FhI2p0qRdNjfjRRE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MDhRxDHqZ2LxaxYn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlet-session/switch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletSessionController@switch',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletSessionController@switch',
        'namespace' => NULL,
        'prefix' => 'api/outlet-session',
        'where' => 
        array (
        ),
        'as' => 'generated::MDhRxDHqZ2LxaxYn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mgjZPnwUBIU82piT' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlet-session/test',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletSessionController@test',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletSessionController@test',
        'namespace' => NULL,
        'prefix' => 'api/outlet-session',
        'where' => 
        array (
        ),
        'as' => 'generated::mgjZPnwUBIU82piT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DknXm5624ZpSzork' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/outlet-session/current',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletSessionController@current',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletSessionController@current',
        'namespace' => NULL,
        'prefix' => 'api/outlet-session',
        'where' => 
        array (
        ),
        'as' => 'generated::DknXm5624ZpSzork',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9FuiSYdLMudx1XA1' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlet-session/clear',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletSessionController@clear',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletSessionController@clear',
        'namespace' => NULL,
        'prefix' => 'api/outlet-session',
        'where' => 
        array (
        ),
        'as' => 'generated::9FuiSYdLMudx1XA1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::V8H8amp4zIv3nJRj' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlet-session/execute',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletSessionController@execute',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletSessionController@execute',
        'namespace' => NULL,
        'prefix' => 'api/outlet-session',
        'where' => 
        array (
        ),
        'as' => 'generated::V8H8amp4zIv3nJRj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jVtvW97umuLQdESJ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/outlet-connection/outlets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletConnectionController@getActiveOutlets',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletConnectionController@getActiveOutlets',
        'namespace' => NULL,
        'prefix' => 'api/outlet-connection',
        'where' => 
        array (
        ),
        'as' => 'generated::jVtvW97umuLQdESJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DsxMGpGN8vK1RgTw' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlet-connection/test',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletConnectionController@testConnection',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletConnectionController@testConnection',
        'namespace' => NULL,
        'prefix' => 'api/outlet-connection',
        'where' => 
        array (
        ),
        'as' => 'generated::DsxMGpGN8vK1RgTw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::j9RvNnpmuzcBUvPB' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/outlet-connection/set-active',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\OutletConnectionController@setActiveOutlet',
        'controller' => 'App\\Http\\Controllers\\Api\\OutletConnectionController@setActiveOutlet',
        'namespace' => NULL,
        'prefix' => 'api/outlet-connection',
        'where' => 
        array (
        ),
        'as' => 'generated::j9RvNnpmuzcBUvPB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::089opX3tjdagELsN' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/mobile-terminals',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@index',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::089opX3tjdagELsN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8BIWS5y6jJC6lKzB' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/mobile-terminals',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@store',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::8BIWS5y6jJC6lKzB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bF1JpDfN2rZmSNm8' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/mobile-terminals/device-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@getDeviceTypes',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@getDeviceTypes',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::bF1JpDfN2rZmSNm8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jACawimTcZKlwiR4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/mobile-terminals/printer-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@getPrinterTypes',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@getPrinterTypes',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::jACawimTcZKlwiR4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jnYzwdAQW28gmWMh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/mobile-terminals/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@show',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::jnYzwdAQW28gmWMh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::nHt7E24rBBqFdfMA' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/mobile-terminals/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@update',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::nHt7E24rBBqFdfMA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Bptu9A4FPqFjW1iG' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/mobile-terminals/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::Bptu9A4FPqFjW1iG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2qDoM2c1msUKVEeV' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/mobile-terminals/{id}/toggle-sync',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@toggleSync',
        'controller' => 'App\\Http\\Controllers\\Api\\MobileTerminalController@toggleSync',
        'namespace' => NULL,
        'prefix' => 'api/mobile-terminals',
        'where' => 
        array (
        ),
        'as' => 'generated::2qDoM2c1msUKVEeV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HCvtLOtMJddirlMV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@index',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::HCvtLOtMJddirlMV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cB0l635NZk0yXRlA' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payment-logs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@store',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::cB0l635NZk0yXRlA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q7d5dcggbv6wvFEA' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-logs/payment-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@getPaymentTypes',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@getPaymentTypes',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::Q7d5dcggbv6wvFEA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NlVsrR675YyU99bk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-logs/terminals',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@getTerminals',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@getTerminals',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::NlVsrR675YyU99bk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9KMLdsJ5WlzV7RAm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-logs/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@getStats',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@getStats',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::9KMLdsJ5WlzV7RAm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Pg1IrvJEoDYzpPBg' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-logs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@show',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::Pg1IrvJEoDYzpPBg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::DKoQQCxXP2diMaq6' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/payment-logs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@update',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::DKoQQCxXP2diMaq6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YtwWbcOuc0kkyqHc' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/payment-logs/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentREQInfoLogController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/payment-logs',
        'where' => 
        array (
        ),
        'as' => 'generated::YtwWbcOuc0kkyqHc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::89N8ydjqLJrSo7Ri' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@index',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::89N8ydjqLJrSo7Ri',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0wK9bP1frwMeXLEi' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payment-success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@store',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::0wK9bP1frwMeXLEi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::e9jxFwUof7xWei4X' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-success/card-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getCardTypes',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getCardTypes',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::e9jxFwUof7xWei4X',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0gtTp9bIbeN2iym7' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-success/issuers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getIssuers',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getIssuers',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::0gtTp9bIbeN2iym7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IbRafLNfodI7M3Zt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-success/terminals',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getTerminals',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getTerminals',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::IbRafLNfodI7M3Zt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qj64SZ14qs41AzEL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-success/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getStats',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@getStats',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::qj64SZ14qs41AzEL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::r0HkF1e0JvrJQahs' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-success/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@show',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::r0HkF1e0JvrJQahs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dzFgXBJGXxqmnIAq' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/payment-success/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@update',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::dzFgXBJGXxqmnIAq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4naNWvVBp984Csx3' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/payment-success/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentSuccessInfoController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/payment-success',
        'where' => 
        array (
        ),
        'as' => 'generated::4naNWvVBp984Csx3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GgCkbuFaYZps9hLj' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@index',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::GgCkbuFaYZps9hLj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Qkk8PAtSX5HSILGY' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payment-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@store',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::Qkk8PAtSX5HSILGY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zf2EXeDesEDQr6nw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-types/terminals',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@getTerminals',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@getTerminals',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::zf2EXeDesEDQr6nw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jxyTxBjnL4T3CGhr' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-types/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@getStats',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@getStats',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::jxyTxBjnL4T3CGhr',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8W3NC1Upx9kO3X16' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/payment-types/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@show',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::8W3NC1Upx9kO3X16',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::us4QLSwbfzAQFomP' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/payment-types/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@update',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::us4QLSwbfzAQFomP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::9cbrQEfiXSgEKSlQ' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/payment-types/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::9cbrQEfiXSgEKSlQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Py9CDCZ8LiJG0CyL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/payment-types/{id}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@togglePaymentMethod',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentTypeController@togglePaymentMethod',
        'namespace' => NULL,
        'prefix' => 'api/payment-types',
        'where' => 
        array (
        ),
        'as' => 'generated::Py9CDCZ8LiJG0CyL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cfLmAWmDL5NOlcbe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/app-versions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@index',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::cfLmAWmDL5NOlcbe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Hoa6ZweYovOzxIb5' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/app-versions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@store',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::Hoa6ZweYovOzxIb5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xkW7WuKXOBtJoQQy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/app-versions/packages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@getPackageNames',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@getPackageNames',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::xkW7WuKXOBtJoQQy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rid0EAOHVARBNOLc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/app-versions/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@getStats',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@getStats',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::rid0EAOHVARBNOLc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6EdBZaUzW9d2nFzX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/app-versions/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@show',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::6EdBZaUzW9d2nFzX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OFK5V4W8k3MxfaaN' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/app-versions/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@update',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::OFK5V4W8k3MxfaaN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8yO1A3iviuEZsDhO' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/app-versions/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::8yO1A3iviuEZsDhO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eKwLLNpBPmlVciGS' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/app-versions/{id}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@toggleStatus',
        'controller' => 'App\\Http\\Controllers\\Api\\AppVersionInfoController@toggleStatus',
        'namespace' => NULL,
        'prefix' => 'api/app-versions',
        'where' => 
        array (
        ),
        'as' => 'generated::eKwLLNpBPmlVciGS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0OJtCIHoOVM74zez' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/change-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'jwtauth:api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SettingController@changePassword',
        'controller' => 'App\\Http\\Controllers\\Api\\SettingController@changePassword',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::0OJtCIHoOVM74zez',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xG8qQLX737xjntnv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '{app?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::xG8qQLX737xjntnv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'app' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
