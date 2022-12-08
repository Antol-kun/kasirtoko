<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'     => CSRF::class,
        'toolbar'  => DebugToolbar::class,
        'honeypot' => Honeypot::class,
        'filterAdmin' => \App\Filters\FilterAdmin::class,
        'filterKasir' => \App\Filters\FilterKasir::class,
        'filterGudang' => \App\Filters\FilterGudang::class,
        'filterPimpinan' => \App\Filters\FilterPimpinan::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            'filterAdmin' => [
                'except' => ['login/*', 'login', '/']
            ],
            'filterKasir' => [
                'except' => ['login/*', 'login', '/']
            ],
            'filterGudang' => [
                'except' => ['login/*', 'login', '/']
            ],
            'filterPimpinan' => [
                'except' => ['/', 'login/*', 'login']
            ]
        ],
        'after' => [
            'filterAdmin' => ['except' => ['main/*', 'satuan/*', 'kategori/*', 'barang/*', 'barangmasuk/*', 'barangkeluar/*', 'pelanggan/*', 'hutang/*', 'laporan/*', 'utility/*', 'payment/*', 'users/*', 'pinjaman/*', 'nasabah/*']],
            'filterKasir' => ['except' => ['barangkeluar/*', 'main/*', 'pelanggan/*', 'pinjaman/*']],
            'filterGudang' => ['except' => ['barangmasuk/*', 'main/*']],
            'filterPimpinan' => ['except' => ['main/*', 'laporan/*']],
            'toolbar',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [];
}
