<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;

class BankSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $banks = [
            ["name" => "Intesa Sanpaolo"],
            ["name" => "Unicredit"],
            ["name" => "BPER Banca"],
            ["name" => "Banco Bpm"],
            ["name" => "Banca Monte dei Paschi di Siena"],
            ["name" => "Crédit Agricole Italia"],
            ["name" => "Banca Nazionale del Lavoro"],
            ["name" => "Banca Popolare di Sondrio"],
            ["name" => "Credito Emiliano"],
            ["name" => "Banco di Sardegna"],
            ["name" => "Banca Sella"],
            ["name" => "Banco di Desio e della Brianza"],
            ["name" => "Deutsche Bank"],
            ["name" => "Bdm Banca"],
            ["name" => "Cassa di Risparmio di Asti"],
            ["name" => "BCC di Roma"],
            ["name" => "Compass Banca"],
            ["name" => "Banca Popolare dell'Alto Adige"],
            ["name" => "Intesa Sanpaolo Private Banking"],
            ["name" => "Banca Popolare di Puglia e Basilicata"],
            ["name" => "Fideuram - Intesa Sanpaolo Private Banking"],
            ["name" => "Cassa di Risparmio di Bolzano - Sudtiroler Sparkasse AG"],
            ["name" => "Chebanca!"],
            ["name" => "Emil Banca - Credito Cooperativo"],
            ["name" => "Banca Popolare Pugliese"],
            ["name" => "La Cassa di Ravenna"],
            ["name" => "Banca Agricola Popolare di Ragusa"],
            ["name" => "Findomestic Banca"],
            ["name" => "Banca Valsabbina"],
            ["name" => "Banca Centro-Credito Cooperativo Toscana-Umbria Soc.Cooperativa"],
            ["name" => "BCC di Alba, Langhe, Roero e del Canavese"],
            ["name" => "Credito Cooperativo Ravennate, Forlivese e Imolese"],
            ["name" => "Banca Prealpi Sanbiagio Credito Cooperativo"],
            ["name" => "Banca del Territorio Lombardo Credito Cooperativo"],
            ["name" => "BCC di Milano"],
            ["name" => "Banca di Credito Popolare"],
            ["name" => "Banca di Cividale o"],
            ["name" => "Credem Euromobiliare Private Banking"],
            ["name" => "BCC di Brescia"],
            ["name" => "Cassa di Risparmio di Volterra"],
            ["name" => "Banca 360 Credito Cooperativo Fvg"],
            ["name" => "Cassa Padana BCC"],
            ["name" => "Banca delle Terre Venete Credito Cooperativo ¿ Società Cooperativa"],
            ["name" => "BCC Pordenonese e Monsile"],
            ["name" => "Cassa di Risparmio di Fermo"],
            ["name" => "Banca di Piacenza"],
            ["name" => "BCC di Verona e Vicenza - Credito Cooperativo"],
            ["name" => "Blu Banca"],
            ["name" => "IBL Istituto Bancario del Lavoro"],
            ["name" => "Banca Aletti"]
        ];

        Bank::insert($banks);
    }
}
