<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LineOperatorService extends Facade
{
   protected static function getFacadeAccessor()
   {
       return 'line_operator_service';
   }
}
?>