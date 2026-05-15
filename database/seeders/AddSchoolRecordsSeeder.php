<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Human;
use App\Models\School;
use App\Models\Community;

class AddSchoolRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        /*
            Insertamos Preescolar
        */
        $community1 = Community::where('name', 'San Juan Monte Flor')->first();

        $school1 = new School();
        $school1->name = "Redención";
        $school1->key = "20DPB0239T";
        $school1->type_of_school = "Primaria";
        $school1->community_id = $community1->id;
        $school1->secondary_number = 1;
        $school1->human_id = $administrativeUser->id;
        $school1->save();

        $community2 = Community::where('name', 'Santa Maria Peñoles')->first();

        $school2 = new School();
        $school2->name = "Redención";
        $school2->key = "20DPB0450N";
        $school2->type_of_school = "Primaria";
        $school2->community_id = $community2->id;
        $school2->secondary_number = 2;
        $school2->human_id = $administrativeUser->id;
        $school2->save();

        $community3 = Community::where('name', 'San Pedro Cholula')->first();

        $school3 = new School();
        $school3->name = "Francisco I. Madero";
        $school3->key = "20DPB0453k";
        $school3->type_of_school = "Primaria";
        $school3->community_id = $community3->id;
        $school3->human_id = $administrativeUser->id;
        $school3->save();

        $community4 = Community::where('name', 'Santa Catarina Estetla')->first();

        $school4 = new School();
        $school4->name = "La Luz";
        $school4->key = "20DPB0454J";
        $school4->type_of_school = "Primaria";
        $school4->community_id = $community4->id;
        $school4->secondary_number = 3;
        $school4->human_id = $administrativeUser->id;
        $school4->save();

        $community5 = Community::where('name', 'Corral de Piedra')->first();

        $school5 = new School();
        $school5->name = "Zahuindanda";
        $school5->key = "20DPB1014T";
        $school5->type_of_school = "Primaria";
        $school5->community_id = $community5->id;
        $school5->human_id = $administrativeUser->id;
        $school5->save();

        $community6 = Community::where('name', 'Cañada de Hielo')->first();

        $school6 = new School();
        $school6->name = "Flechador del Sol";
        $school6->key = "20DPB1471G";
        $school6->type_of_school = "Primaria";
        $school6->community_id = $community6->id;
        $school6->secondary_number = 4;
        $school6->human_id = $administrativeUser->id;
        $school6->save();

        $community7 = Community::where('name', 'El Duraznal')->first();

        $school7 = new School();
        $school7->name = "Cuauhtemoc";
        $school7->key = "20DPB1645G";
        $school7->type_of_school = "Primaria";
        $school7->community_id = $community7->id;
        $school7->secondary_number = 7;
        $school7->human_id = $administrativeUser->id;
        $school7->save();

        $community8 = Community::where('name', 'Los Sabinos')->first();

        $school8 = new School();
        $school8->name = "Lazaro Cardenaz del Rio";
        $school8->key = "20DPB2138I";
        $school8->type_of_school = "Primaria";
        $school8->community_id = $community8->id;
        $school8->human_id = $administrativeUser->id;
        $school8->save();

        $community9 = Community::where('name', 'Recibimiento')->first();

        $school9 = new School();
        $school9->name = "Niño Heroes";
        $school9->key = "20DPB2209M";
        $school9->type_of_school = "Primaria";
        $school9->community_id = $community9->id;
        $school9->secondary_number = 5;
        $school9->human_id = $administrativeUser->id;
        $school9->save();

        $community10 = Community::where('name', 'Rio Manzanita')->first();

        $school10 = new School();
        $school10->name = "Yute Kuñee";
        $school10->key = "20DPB2347O";
        $school10->type_of_school = "Primaria";
        $school10->community_id = $community10->id;
        $school10->secondary_number = 8;
        $school10->human_id = $administrativeUser->id;
        $school10->save();

        $community11 = Community::where('name', 'Rio Cacho')->first();

        $school11 = new School();
        $school11->name = "Union y Progreso";
        $school11->key = "20DPB2426A";
        $school11->type_of_school = "Primaria";
        $school11->community_id = $community11->id;
        $school11->secondary_number = 9;
        $school11->human_id = $administrativeUser->id;
        $school11->save();

        /*
            Insertamos Preescolar
        */
        // $community1 = Community::where('name', 'San Juan Monte Flor')->first();

        $school12 = new School();
        $school12->name = "Miguel Hidalgo";
        $school12->key = "20DCC0460P";
        $school12->type_of_school = "Preescolar";
        $school12->community_id = $community1->id;
        $school12->human_id = $administrativeUser->id;
        $school12->save();

        // $community2 = Community::where('name', 'Santa Maria Peñoles')->first();

        $school13 = new School();
        $school13->name = "Juan Amos Comenio";
        $school13->key = "20DCC0461O";
        $school13->type_of_school = "Preescolar";
        $school13->community_id = $community2->id;
        $school13->human_id = $administrativeUser->id;
        $school13->save();

        // $community4 = Community::where('name', 'Santa Catarina Estetla')->first();

        $school15 = new School();
        $school15->name = "Juan Escutia";
        $school15->key = "20DCC0464L";
        $school15->type_of_school = "Preescolar";
        $school15->community_id = $community4->id;
        $school15->secondary_number = 5;
        $school15->human_id = $administrativeUser->id;
        $school15->save();

        // $community6 = Community::where('name', 'Cañada de Hielo')->first();

        $school17 = new School();
        $school17->name = "Jose Maria Morelos y Pavon";
        $school17->key = "20DCC2082S";
        $school17->type_of_school = "Preescolar";
        $school17->community_id = $community6->id;
        $school17->secondary_number = 3;
        $school17->human_id = $administrativeUser->id;
        $school17->save();

        // $community7 = Community::where('name', 'El Duraznal')->first();

        $school18 = new School();
        $school18->name = "Leona Vicario";
        $school18->key = "20DCC2324Z";
        $school18->type_of_school = "Preescolar";
        $school18->community_id = $community7->id;
        $school18->secondary_number = 4;
        $school18->human_id = $administrativeUser->id;
        $school18->save();

        $community19 = Community::where('name', 'Rio Hondo')->first();

        $school19 = new School();
        $school19->name = "Ricardo Flores Magon";
        $school19->key = "20DCC2238C";
        $school19->type_of_school = "Preescolar";
        $school19->community_id = $community19->id;
        $school19->human_id = $administrativeUser->id;
        $school19->save();

        // $community9 = Community::where('name', 'Recibimiento')->first();

        $school20 = new School();
        $school20->name = "Adolfo Lopez Mateos";
        $school20->key = "20DCC2142Q";
        $school20->type_of_school = "Preescolar";
        $school20->community_id = $community9->id;
        $school20->human_id = $administrativeUser->id;
        $school20->save();

        // $community10 = Community::where('name', 'Rio Manzanita')->first();

        $school21 = new School();
        $school21->name = "Yuku Ñee";
        $school21->key = "20DCC2418N";
        $school21->type_of_school = "Preescolar";
        $school21->community_id = $community10->id;
        $school21->human_id = $administrativeUser->id;
        $school21->save();

        // $community11 = Community::where('name', 'Rio Cacho')->first();

        $school22 = new School();
        $school22->name = "20 de Noviembre";
        $school22->key = "20DCC2499O";
        $school22->type_of_school = "Preescolar";
        $school22->community_id = $community11->id;
        $school22->secondary_number = 10;
        $school22->human_id = $administrativeUser->id;
        $school22->save();

        /*
            Insertamos Inicial
        */

        // $community1 = Community::where('name', 'San Juan Monte Flor')->first();

        $school23 = new School();
        $school23->name = "";
        $school23->key = "20DIN";
        $school23->type_of_school = "Inicial";
        $school23->community_id = $community1->id;
        $school23->human_id = $administrativeUser->id;
        $school23->save();

        // $community3 = Community::where('name', 'San Pedro Cholula')->first();

        $school24 = new School();
        $school24->name = "Educación Inicial";
        $school24->key = "0156F";
        $school24->type_of_school = "Inicial";
        $school24->community_id = $community3->id;
        $school24->human_id = $administrativeUser->id;
        $school24->save();

        // $community4 = Community::where('name', 'Santa Catarina Estetla')->first();

        $school25 = new School();
        $school25->name = "Educación Inicial";
        $school25->key = "0603W";
        $school25->type_of_school = "Inicial";
        $school25->community_id = $community4->id;
        $school25->human_id = $administrativeUser->id;
        $school25->save();

        // $community5 = Community::where('name', 'Corral de Piedra')->first();

        $school26 = new School();
        $school26->name = "Educación Inicial";
        $school26->key = "0155G";
        $school26->type_of_school = "Inicial";
        $school26->community_id = $community5->id;
        $school26->human_id = $administrativeUser->id;
        $school26->save();

        // $community6 = Community::where('name', 'Cañada de Hielo')->first();

        $school27 = new School();
        $school27->name = "Educación Inicial";
        $school27->key = "0332U";
        $school27->type_of_school = "Inicial";
        $school27->community_id = $community6->id;
        $school27->human_id = $administrativeUser->id;
        $school27->save();

        // $community7 = Community::where('name', 'El Duraznal')->first();

        $school28 = new School();
        $school28->name = "Educación Inicial";
        $school28->key = "0333T";
        $school28->type_of_school = "Inicial";
        $school28->community_id = $community7->id;
        $school28->human_id = $administrativeUser->id;
        $school28->save();

        // $community11 = Community::where('name', 'Rio Cacho')->first();

        $school32 = new School();
        $school32->name = "Preescolar El Carrizal";
        // $school32->key = "";
        $school32->type_of_school = "Inicial";
        $school32->community_id = $community11->id;
        $school32->human_id = $administrativeUser->id;
        $school32->save();

        /*
            Insertamos Albergues escolares
        */

        // $community1 = Community::where('name', 'San Juan Monte Flor')->first();

        $school33 = new School();
        $school33->name = "Redención";
        $school33->key = "20TA10124A";
        $school33->type_of_school = "Albergues escolares";
        $school33->community_id = $community1->id;
        $school33->human_id = $administrativeUser->id;
        $school33->save();

        // $community2 = Community::where('name', 'Santa Maria Peñoles')->first();

        $school34 = new School();
        $school34->name = "Redención";
        $school34->key = "TA10293W";
        $school34->type_of_school = "Albergues escolares";
        $school34->community_id = $community2->id;
        $school34->human_id = $administrativeUser->id;
        $school34->save();

        // $community8 = Community::where('name', 'Los Sabinos')->first();

        $school35 = new School();
        $school35->name = "Jefatura";
        $school35->key = "951 191 2401 951 191 2407";
        $school35->type_of_school = "Albergues escolares";
        $school35->community_id = $community8->id;
        $school35->human_id = $administrativeUser->id;
        $school35->save();
    }
}
