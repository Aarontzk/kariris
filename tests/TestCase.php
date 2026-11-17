<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Pengaman: RefreshDatabase menjalankan migrate:fresh. Jangan sampai itu kena Supabase/Neon.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $default = $app['config']->get('database.default');
        $koneksi = $app['config']->get("database.connections.{$default}");
        if ($default !== 'sqlite' || ($koneksi['database'] ?? null) !== ':memory:' || ! empty($koneksi['url'])) {
            throw new RuntimeException('Test harus memakai SQLite in-memory, bukan database remote.');
        }

        return $app;
    }
}
