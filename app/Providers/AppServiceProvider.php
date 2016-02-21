<?php

namespace App\Providers;

use App\Model\Categories;
use App\Model\Menu;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Menu $menu, Categories $categories)
    {
        //Подключаем навигацию по категориям во все представления
        view()->share('category', $categories->getAllCategory());

        //Подключаем основное меню во все представления
        view()->share('menu', $menu->getMenu());
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}