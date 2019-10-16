<?php

namespace Ezadev\Admin\Scaffold;

use Illuminate\Support\ServiceProvider;

class ScaffoldServiceProvider extends ServiceProvider
{
  public function boot()
  {
    $this->loadViewsFrom(__DIR__.'/../resources/views','ezadev-admin-scaffold');
    Scaffold::boot();
  }
}
