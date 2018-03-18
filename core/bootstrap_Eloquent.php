<?php

use Illuminate\Database\Capsule\Manager as Capsule;

// start illuminate/database
$conf = require_once __DIR__ . "/../app/database.php";


  // $capsule = new Illuminate\Database\Capsule\Manager;
  $capsule = new Capsule;
  
  if($conf['driver'] == 'mysql')
  {
    $capsule->addConnection([
      'driver'    => 'mysql',
      'host'      => $conf['mysql']['host'],
      'database'  => $conf['mysql']['database'],
      'username'  => $conf['mysql']['user'],
      'password'  => $conf['mysql']['pass'],
      'charset'   => $conf['mysql']['charset'],
      'collation' => $conf['mysql']['collation'],
      'prefix'    => '',
    ]);
  } elseif($conf['driver']['sqlite'])
  {
    $capsule->addConnection([
      'driver'    => 'sqlite',
      'database' => __DIR__ . "/../storage/database" . $conf['sqlite']['database']
    ]);
  }

    $capsule->setAsGlobal();
    $capsule->bootEloquent();