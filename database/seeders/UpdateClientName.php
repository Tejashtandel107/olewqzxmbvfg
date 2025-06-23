<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UpdateClientName extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [17=>  ["name" =>"Studio Associato Antonelli Cecchetti Sas","address"=>"Via dell'Isola Romana, 27","city"=>"Bastia Umbra","province"=> "Umbria","postal_code"=>null,"country_id"=>107,"vat_number"=>"IT02312400548"]],
            [16 => ["name"=>"Dottore Commercialista Luca Mancini","address"=>"Viale Guglielmo Oberdan, 83","city"=>"Cesena (FC)","province"=> "Emilia-Romagna","postal_code"=>47521,"country_id"=>107,"vat_number"=>"IT02465540405"]],
            [37 => ["name"=>"Studio Commerciale Tributario Berretti","address"=>"Corso Vittorio Emanuele n. 27","city"=>"Città Di Castello (PG)","province"=> "Umbria","postal_code"=>6012,"country_id"=>107,"vat_number"=>null]],
            [15 => ["name"=>"Studio Penta SRL","address"=>"Viale Corassori, 62","city"=>"Modena","province"=> "Emilia-Romagna","postal_code"=>null,"country_id"=>107,"vat_number"=>"IT01561240356"]],
            [14 => ["name"=>"Mansi&associati","address"=>"Via Giovanni da Cermenate, 3","city"=>"Cantù (CO)","province"=> "Lombardia","postal_code"=>22063,"country_id"=>107,"vat_number"=> "IT03993220130"]],
            [13 => ["name"=>"Società Dako Srl Servizi per l'Impresa","address"=>"Via Marconi, 45","city"=>"Seriate (Bg)","province"=> null,"postal_code"=>24068,"country_id"=>107,"vat_number"=>"IT02869020160"]],
            [12 => ["name"=>"Studio Nicoletti","address"=>"Via Mallia, 32","city"=>"Gela (CL)","province"=> null,"postal_code"=>null,"country_id"=>107,"vat_number"=>"NCLCML80R09D9609"]],
            [11 => ["name"=>"Carnevale Miino Alberto","address"=>"Via Farini, 4","city"=>"Vigevano(PV)","province"=> "Lombardia","postal_code"=>27029,"country_id"=>107,"vat_number"=>"CRNLRT83M25L872V"]],
            [10 => ["name"=>"La Mura e Associati Studio","address"=>"Via Venti Settembre, 24","city"=>"Milano","province"=> "Lombardia","postal_code"=>20123,"country_id"=>107,"vat_number"=>"11659870155"]],
            [9 =>  ["name"=>"EL.CO. SRL","address"=>"Via Isonzo 1/B","city"=>"Limbiate (MB)","province"=> "Lombardia","postal_code"=>20812,"country_id"=>107,"vat_number"=>"6998760968"]],
            [8 =>  ["name"=>"DEG S.R.L.","address"=>"Via Salandra, 18","city"=>"Roma","province"=> "Lazio","postal_code"=>198,"country_id"=>107,"vat_number"=>"7447600011"]],
            [7 =>  ["name"=>"OPEN SOLUTIONS S.R.L.","address"=>"Via delle Industrie, 43","city"=>"Fossalta di Piave (VE)","province"=> "Veneto","postal_code"=>30020,"country_id"=>107,"vat_number"=>null]],
            [6 =>  ["name"=>"GS & PARTNERS Dottori Commercialisti","address"=>"Piazzale Cadorna, 13","city"=>"Milano","province"=> "Lombardia","postal_code"=>20123,"country_id"=>107,"vat_number"=>"12031200152"]],
            [5 =>  ["name"=>"NEXUS STP","address"=>"Corso Vittorio Emanuele II n. 44","city"=>"Cremona","province"=> "Lombardia","postal_code"=>26100,"country_id"=>107,"vat_number"=>"1611430198"]],
            [4 =>  ["name"=>"Lexxat Consulenza Srls","address"=>"Piazza Santiago del Cile, 8","city"=>"Roma","province"=> "Lazio","postal_code"=>197,"country_id"=>107,"vat_number"=>"16582011009"]],
            [30 => ["name"=>"Maria Antonietta Fasolino Contabilità & Finanza","address"=>"Corso Mario Pagano n. 213","city"=>"Roccapiemonte (SA)","province"=> "Campania","postal_code"=>84086,"country_id"=>107,"vat_number"=>"FSLMNT72C48H431W"]],    
        ];
       
        foreach ($users as $userData) {
            foreach ($userData as $userId => $value) {
                $user = User::where('user_id', '=', $userId)->first();

                if ($user) {
                    $user->name = trim($value['name']);
                    $user->save();
                  
                    if ($user) {
                        $user->profile->address = $value['address'];
                        $user->profile->city = $value['city'];
                        $user->profile->province = $value['province'];
                        $user->profile->postal_code = $value['postal_code'];
                        $user->profile->country_id = $value['country_id'];
                        $user->profile->vat_number = $value['vat_number'];
                        $user->profile->save();
                    }
                }
            }
        }
    }
}
