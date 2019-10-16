<?php

namespace Ezadev\Admin\Scaffold;

use Ezadev\Admin\Admin;
use Ezadev\Admin\Auth\Database\Menu;
use Ezadev\Admin\Extension;

class Scaffold extends Extension
{
  public static function boot(){
    static::registerRoutes();
    Admin::extend('scaffold',__CLASS__);
  }

  public static function registerRoutes(){
    parent::routes(function ($router) {
      $router->get('scaffold','Ezadev\Admin\Scaffold\Controllers\ScaffoldController@index');
      $router->post('scaffold','Ezadev\Admin\Scaffold\Controllers\ScaffoldController@store');
    });
  }

  public static function import(){
    $lastOrder = Menu::max('order');
    $root = [
      'parent_id'=>0,
      'order'=>$lastOrder,
      'title'=>'Scaffolding',
      'icon'=>'fa-gears',
      'uri'=>'scaffold'
    ];

    Menu::create($root);
    parent::createPermission('Admin Scafoolding','ext.scaffold','scaffold/*');
  }
}
