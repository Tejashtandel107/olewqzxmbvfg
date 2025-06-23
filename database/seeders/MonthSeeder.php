<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Month;

class MonthSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $months = [
            ['name_en' =>'January','name_it'=>'Gennaio'],
            ['name_en' =>'February','name_it'=>'Febbraio'],
            ['name_en' =>'March','name_it'=>'Marzo'],
            ['name_en' =>'April','name_it'=>'Aprile'],
            ['name_en' =>'May','name_it'=>'Maggio'],
            ['name_en' =>'June','name_it'=>'Giugno'],
            ['name_en' =>'July','name_it'=>'Luglio'],
            ['name_en' =>'August','name_it'=>'Agosto'],
            ['name_en' =>'September','name_it'=>'Settembre'],
            ['name_en' =>'October','name_it'=>'Ottobre'],
            ['name_en' =>'November','name_it'=>'Novembre'],
            ['name_en' =>'December','name_it'=>'Dicembre'],
        ];
       
        month::insert($months);
    }
}
