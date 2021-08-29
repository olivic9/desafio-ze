<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Services\Dtos\PartnerDto;
use Illuminate\Database\Seeder;

class PartnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path() . "/json/pdvs.json";
        $json = json_decode(file_get_contents($path), true);
        foreach ($json['pdvs'] as $pdv){
            $dto = new PartnerDto($pdv);
            Partner::create($dto->mapToCreate());
        }
    }
}
