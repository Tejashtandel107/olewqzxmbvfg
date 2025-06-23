<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LineClientService extends Facade
{
   protected static function getFacadeAccessor()
   {
       return 'line_client_service';
   }
}
?>