<?php

namespace EasyPanelTest;

use EasyPanel\EasyPanelServiceProvider;
use EasyPanel\Parsers\StubParser;
use EasyPanelTest\Dependencies\Article;
use EasyPanelTest\Dependencies\User;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\LivewireServiceProvider;
use function Orchestra\Testbench\workbench_path;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * @var Collection|Model
     */
    protected $user;
    /**
     * @var StubParser
     */
    protected $parser;

    public function getAdmin()
    {
        $this->user->panelAdmin()->create([
            'is_superuser' => true
        ]);

        return $this->user->refresh();
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path(__DIR__ . '/Dependencies/database/migrations'));
    }


    protected function setUp(): void
    {
        parent::setUp();


        config()->set('easy_panel.user_model', User::class);
        config()->set('easy_panel.database.panel_admin_table', 'panel_admins');
        config()->set('easy_panel.database.crud_table', 'cruds');
        config()->set('easy_panel.database.roles_table', 'roles');
        config()->set('easy_panel.database.roles_users_table', 'role_user');


        $this->setUser();
        $this->setParser();


    }

    protected function setUser()
    {
        $faker = Factory::create();
        $user = User::create(['name' => $faker->name, 'password' => Hash::make('password')]);
        $this->user = $user;
    }

    private function setParser()
    {
        $this->parser = new StubParser('article', Article::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            EasyPanelServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }
}
