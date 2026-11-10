<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Vite;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Vite::useHotFile(storage_path('framework/testing/vite.hot'))
            ->useBuildDirectory('build')
            ->useManifestFilename('manifest.json')
            ->preventServingAssets();

        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->isProduction());
    }

    protected function actingAsGuru(): User
    {
        return $this->actingAsUserWithRole('guru');
    }

    protected function actingAsAdminTu(): User
    {
        return $this->actingAsUserWithRole('admin_tu');
    }

    protected function actingAsPimpinan(): User
    {
        return $this->actingAsUserWithRole('pimpinan');
    }

    protected function actingAsUserWithRole(string $roleName): User
    {
        $role = Role::firstOrCreate(
            ['name' => $roleName],
            ['display_name' => ucwords(str_replace('_', ' ', $roleName))]
        );

        /** @var User $user */
        $user = User::factory()->createOne(['role_id' => $role->id]);

        $this->actingAs($user);

        return $user;
    }

    protected function freezeTimeAt(string $dateTime): void
    {
        Carbon::setTestNow(Carbon::parse($dateTime));
    }

    protected function assertJsonSuccess(\Illuminate\Testing\TestResponse $response): void
    {
        $response->assertOk()->assertJsonPath('status', 'success');
    }
}