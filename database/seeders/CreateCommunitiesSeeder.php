<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Human;
use App\Models\Community;

class CreateCommunitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        $community1 = new Community();
        $community1->name = "San Juan Monte Flor";
        $community1->human_id = $administrativeUser->id;
        $community1->save();

        $community2 = new Community();
        $community2->name = "Santa Maria Peñoles";
        $community2->human_id = $administrativeUser->id;
        $community2->save();

        $community3 = new Community();
        $community3->name = "San Pedro Cholula";
        $community3->human_id = $administrativeUser->id;
        $community3->save();

        $community4 = new Community();
        $community4->name = "Santa Catarina Estetla";
        $community4->human_id = $administrativeUser->id;
        $community4->save();

        $community5 = new Community();
        $community5->name = "Corral de Piedra";
        $community5->human_id = $administrativeUser->id;
        $community5->save();

        $community6 = new Community();
        $community6->name = "Cañada de Hielo";
        $community6->human_id = $administrativeUser->id;
        $community6->save();

        $community7 = new Community();
        $community7->name = "El Duraznal";
        $community7->human_id = $administrativeUser->id;
        $community7->save();

        $community8 = new Community();
        $community8->name = "Los Sabinos";
        $community8->human_id = $administrativeUser->id;
        $community8->save();

        $community9 = new Community();
        $community9->name = "Rio Hondo";
        $community9->human_id = $administrativeUser->id;
        $community9->save();

        $community10 = new Community();
        $community10->name = "Recibimiento";
        $community10->human_id = $administrativeUser->id;
        $community10->save();

        $community11 = new Community();
        $community11->name = "Rio Manzanita";
        $community11->human_id = $administrativeUser->id;
        $community11->save();

        $community12 = new Community();
        $community12->name = "Rio Cacho";
        $community12->human_id = $administrativeUser->id;
        $community12->save();
    }
}
