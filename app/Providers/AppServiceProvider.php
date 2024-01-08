<?php

namespace App\Providers;

use App\Models\Data;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('modalSave', function () {
            return '</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button><button type="button" class="btn btn-primary" id="save">保存</button>';
        });

        Blade::directive('modalClose', function () {
            return '</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">关闭</button>';
        });

        Blade::directive('alert', function ($param) {
            $param = explode(',', $param);
            $text = $param[0];
            if (isset($param[1])) {
                $type = $param[1];
            } else {
                $type = 'success';
            }
            return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">' . $text . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        });

        Blade::directive('badge', function ($param) {
            $param = explode(',', $param);
            $text = $param[0];
            if (isset($param[1])) {
                $type = $param[1];
            } else {
                $type = 'success';
            }
            return '<span class="badge rounded-pill bg-' . $type . '">' . $text . '</span>';

        });
        //
        Schema::defaultStringLength(150);

    }
}
