<?php

namespace App\Providers;

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\ServiceProvider;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Breadcrumbs::for('admin.home', function ($trail) {
            $trail->push('Trang chủ', route('admin.home'));
        });
        Breadcrumbs::for('admin.studentmanagement', function ($trail) {
            $trail->parent('admin.home');
            $trail->push('Học Viên', route('admin.studentmanagement'));
        });
        Breadcrumbs::for('category', function ($trail, $category) {
            $trail->parent('home');
            $trail->push($category->name, route('category', $category->slug));
        });

        Breadcrumbs::for('product', function ($trail, $product) {
            $trail->parent('category', $product->category);
            $trail->push($product->name, route('product', $product->slug));
        });
    }
}
