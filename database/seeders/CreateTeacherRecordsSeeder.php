<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Human;
use App\Models\Teacher;

class CreateTeacherRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrativeUser = Human::where('paternal_surname', 'administrative')->first();

        $sanJuanMonteFlorPrimarySchool = School::where("key", "20DPB0239T")->first();
        $santaMariaPeñolesPrimarySchool = School::where("key", "20DPB0450N")->first();
        $sanPedroChululaPrimarySchool = School::where("key", "20DPB0453k")->first();
        $santaCatarinaEstetlaPrimarySchool = School::where("key", "20DPB0454J")->first();
        $corralDePiedraPrimarySchool = School::where("key", "20DPB1014T")->first();
        $cañadaDeHieloPrimarySchool = School::where("key", "20DPB1471G")->first();
        $elDuraznalPrimarySchool = School::where("key", "20DPB1645G")->first();
        $losSabinosPrimarySchool = School::where("key", "20DPB2138I")->first();
        $recibimientoPrimarySchool = School::where("key", "20DPB2209M")->first();
        $rioManzanitaPrimarySchool = School::where("key", "20DPB2347O")->first();
        $rioCachoPrimarySchool = School::where("key", "20DPB2426A")->first();
        $sanJuanMonteFlorPreSchool = School::where("key", "20DCC0460P")->first();
        $santaMariaPeñolesPreSchool = School::where("key", "20DCC0461O")->first();
        $santaCatarinaEstetlaPreSchool = School::where("key", "20DCC0464L")->first();
        $cañadaDeHieloPreSchool = School::where("key", "20DCC2082S")->first();
        $elDuraznalPreSchool = School::where("key", "20DCC2324Z")->first();
        $rioHondoPreSchool = School::where("key", "20DCC2238C")->first();
        $recibimientoPreSchool = School::where("key", "20DCC2142Q")->first();
        $rioManzanitaPreSchool = School::where("key", "20DCC2418N")->first();
        $rioCachoPreSchool = School::where("key", "20DCC2499O")->first();
        $sanPedroChululaearlyChildhoodSchool = School::where("key", "20DIN0156F")->first();
        $santaCatarinaEstetlaearlyChildhoodSchool = School::where("key", "20DIN0603W")->first();
        $corralDePiedraearlyChildhoodSchool = School::where("key", "20DIN0155G")->first();
        $cañadaDeHieloearlyChildhoodSchool = School::where("key", "20DIN0332U")->first();
        $elDuraznalearlyChildhoodSchool = School::where("key", "20DIN0333T")->first();
        $sanJuanMonteFlorSchoolHostel = School::where("key", "20TA10124A")->first();
        $santaMariaPeñolesSchoolHostel = School::where("key", "TA10293W")->first();

        // Datos de los 20 maestros
        $teachersData = [
            [
                'name' => 'Pedro',
                'paternal_surname' => 'Mejía',
                'maternal_surname' => 'Caballero',
                'curp' => 'GALM800101HMCLRR01',
                'rfc' => 'GALM800101HCL',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-001-01',
                'funcion' => 'Director',
                'telephone' => '951 526 5683',
                'motivo' => 1,
                'date_of_entry_into_the_sep' => '30/08/1966',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_id' => $sanJuanMonteFlorPrimarySchool->id
            ],
            [
                'name' => 'Eloy Rojas',
                'paternal_surname' => 'Pérez',
                'maternal_surname' => 'Ramirez',
                'curp' => 'PEMJ850202HMCRRR02',
                'rfc' => 'PEMJ850202HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-002-01',
                'funcion' => 'Director',
                'telephone' => '951 120 4965',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '20/01/1980',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_id' => $santaMariaPeñolesPrimarySchool->id
            ],
            [
                'name' => 'Heriberto',
                'paternal_surname' => 'Salinas',
                'maternal_surname' => 'Lopez',
                'curp' => 'SARA900303HMCRNN03',
                'rfc' => 'SARA900303HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-003-01',
                'funcion' => 'Director',
                'telephone' => '951 345 6789',
                'motivo' => 2,
                'date_of_entry_into_the_sep' => '05/04/1970',
                'language' => 'Costa',
                'language_variant' => null,
                'school_id' => $sanPedroChululaPrimarySchool->id
            ],
            [
                'name' => 'Arturo',
                'paternal_surname' => 'Pacheco',
                'maternal_surname' => 'Lopez',
                'curp' => 'GOTC940404HMCRRL04',
                'rfc' => 'GOTC940404HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-004-01',
                'funcion' => 'Director',
                'telephone' => '951 354 6771',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '30/01/1970',
                'language' => 'Istmo',
                'language_variant' => 'Alta',
                'school_id' => $santaCatarinaEstetlaPrimarySchool->id
            ],
            [
                'name' => 'Vilma',
                'paternal_surname' => 'Lopez',
                'maternal_surname' => 'Guzman',
                'curp' => 'DIFL750515HMCRRN05',
                'rfc' => 'DIFL750515HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-005-01',
                'funcion' => 'Director',
                'telephone' => '951 115 2876',
                'motivo' => 3,
                'date_of_entry_into_the_sep' => '20/11/1977',
                'language' => 'Papaloapan',
                'language_variant' => 'Baja',
                'school_id' => $corralDePiedraPrimarySchool->id
            ],
            [
                'name' => 'Anami Claudia',
                'paternal_surname' => 'Lopez',
                'maternal_surname' => 'Perez',
                'curp' => 'MECJ860626HMCRNN06',
                'rfc' => 'MECJ860626HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-006-01',
                'funcion' => 'Director',
                'telephone' => '951 744 1320',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '01/10/1966',
                'language' => 'Sierra sur',
                'language_variant' => null,
                'school_id' => $cañadaDeHieloPrimarySchool->id
            ],
            [
                'name' => 'Bartolo Julian',
                'paternal_surname' => 'Garcia',
                'maternal_surname' => 'Ramirez',
                'curp' => 'HEOP770707HMCRRN07',
                'rfc' => 'HEOP770707HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-007-01',
                'funcion' => 'Director',
                'telephone' => '951 312 3470',
                'motivo' => 1,
                'date_of_entry_into_the_sep' => '08/10/1968',
                'language' => 'Sierra norte',
                'language_variant' => 'Alta',
                'school_id' => $elDuraznalPrimarySchool->id
            ],
            [
                'name' => 'Rosario',
                'paternal_surname' => 'Mendoza',
                'maternal_surname' => 'Martinez',
                'curp' => 'JIMR880808HMCRNN08',
                'rfc' => 'JIMR880808HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-008-01',
                'funcion' => 'Director',
                'telephone' => '951 527 6681',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '20/07/1969',
                'language' => 'Valles centrales',
                'language_variant' => 'Baja',
                'school_id' => $losSabinosPrimarySchool->id
            ],
            [
                'name' => 'Geronimo',
                'paternal_surname' => 'Hernandez',
                'maternal_surname' => '',
                'curp' => 'GORC790909HMCRNN09',
                'rfc' => 'GORC790909HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-009-01',
                'funcion' => 'Director',
                'telephone' => '951 238 5521',
                'motivo' => 2,
                'date_of_entry_into_the_sep' => '01/05/1970',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_id' => $recibimientoPrimarySchool->id
            ],
            [
                'name' => 'Silverio',
                'paternal_surname' => 'Luis',
                'maternal_surname' => 'Martinez',
                'curp' => 'RUSM801010HMCRNN10',
                'rfc' => 'RUSM801010HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-010-01',
                'funcion' => 'Director',
                'telephone' => '951 472 7422',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '03/01/1971',
                'language' => 'Cañada',
                'language_variant' => 'Baja',
                'school_id' => $rioManzanitaPrimarySchool->id
            ],
            [
                'name' => 'Lenin J.',
                'paternal_surname' => 'Mejia',
                'maternal_surname' => 'Santiago',
                'curp' => 'CAVI811111HMCRNN11',
                'rfc' => 'CAVI811111HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-011-01',
                'funcion' => 'Director',
                'telephone' => '951 255 1042',
                'motivo' => 3,
                'date_of_entry_into_the_sep' => '15/09/1972',
                'language' => 'Costa',
                'language_variant' => 'Alta',
                'school_id' => $rioCachoPrimarySchool->id
            ],
            [
                'name' => 'Julia A.',
                'paternal_surname' => 'Hernandez',
                'maternal_surname' => 'Santiago',
                'curp' => 'RAFE821212HMCRNN12',
                'rfc' => 'RAFE821212HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-012-01',
                'funcion' => 'Director',
                'telephone' => '951 883 3252',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '07/01/1973',
                'language' => 'Istmo',
                'language_variant' => null,
                'school_id' => $sanJuanMonteFlorPreSchool->id
            ],
            [
                'name' => 'Nashielly',
                'paternal_surname' => 'Martinez',
                'maternal_surname' => 'Velasco',
                'curp' => 'VAAL831313HMCRNN13',
                'rfc' => 'VAAL831313HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-013-01',
                'funcion' => 'Director',
                'telephone' => '951 347 2309',
                'motivo' => 1,
                'date_of_entry_into_the_sep' => '20/05/1974',
                'language' => 'Papaloapan',
                'language_variant' => 'Alta',
                'school_id' => $santaMariaPeñolesPreSchool->id
            ],
            [
                'name' => 'Maricela',
                'paternal_surname' => 'San Juan',
                'maternal_surname' => 'A.',
                'curp' => 'MOCA841414HMCRNN14',
                'rfc' => 'MOCA841414HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-014-01',
                'funcion' => 'Director',
                'telephone' => '951 157 2232',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '15/11/1975',
                'language' => 'Sierra sur',
                'language_variant' => 'Baja',
                'school_id' => $santaCatarinaEstetlaPreSchool->id
            ],
            [
                'name' => 'Domitila',
                'paternal_surname' => 'Hernandez',
                'maternal_surname' => 'Hernandez',
                'curp' => 'PAFR851515HMCRNN15',
                'rfc' => 'PAFR851515HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-015-01',
                'funcion' => 'Director',
                'telephone' => '951 411 2618',
                'motivo' => 2,
                'date_of_entry_into_the_sep' => '02/10/1976',
                'language' => 'Sierra norte',
                'language_variant' => 'Alta',
                'school_id' => $cañadaDeHieloPreSchool->id
            ],
            [
                'name' => 'Beatriz',
                'paternal_surname' => 'Santiago',
                'maternal_surname' => 'Julian',
                'curp' => 'GUMR861616HMCRNN16',
                'rfc' => 'GUMR861616HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-016-01',
                'funcion' => 'Director',
                'telephone' => '951 258 7622',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '06/01/1977',
                'language' => 'Valles centrales',
                'language_variant' => 'Baja',
                'school_id' => $elDuraznalPreSchool->id
            ],
            [
                'name' => 'Ita',
                'paternal_surname' => 'Hernandez',
                'curp' => 'RACT871717HMCRNN17',
                'rfc' => 'RACT871717HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-017-01',
                'funcion' => 'Director',
                'telephone' => '951 657 7195',
                'motivo' => 3,
                'date_of_entry_into_the_sep' => '12/05/1978',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_id' => $rioHondoPreSchool->id
            ],
            [
                'name' => 'Raquel',
                'paternal_surname' => 'Aquino',
                'maternal_surname' => 'Garcia',
                'curp' => 'NASJ881818HMCRNN18',
                'rfc' => 'NASJ881818HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-018-01',
                'funcion' => 'Director',
                'telephone' => '951 430 8038',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '09/01/1979',
                'language' => 'Cañada',
                'language_variant' => null,
                'school_id' => $recibimientoPreSchool->id
            ],
            [
                'name' => 'Inocente',
                'paternal_surname' => 'Caballero',
                'curp' => 'LUPM891919HMCRNN19',
                'rfc' => 'LUPM891919HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-019-01',
                'funcion' => 'Director',
                'telephone' => '951 644 0124',
                'motivo' => 1,
                'date_of_entry_into_the_sep' => '15/07/1980',
                'language' => 'Costa',
                'language_variant' => 'Alta',
                'school_id' => $rioManzanitaPreSchool->id
            ],
            [
                'name' => 'Ana',
                'paternal_surname' => 'Ramirez',
                'maternal_surname' => 'Pacheco',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 321 1179',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1981',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $rioCachoPreSchool->id
            ],
            [
                'name' => 'Janeth',
                'paternal_surname' => 'Martinez',
                'maternal_surname' => 'Luis',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 693 5437',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1982',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $sanPedroChululaearlyChildhoodSchool->id
            ],
            [
                'name' => 'David',
                'paternal_surname' => 'Hernandez',
                'maternal_surname' => 'Lopez',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 469 6522',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1983',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $santaCatarinaEstetlaearlyChildhoodSchool->id
            ],
            [
                'name' => 'Judith',
                'paternal_surname' => 'Cruz',
                'maternal_surname' => 'Angel',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 201 6200',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1984',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $corralDePiedraearlyChildhoodSchool->id
            ],
            [
                'name' => 'Nubia D.',
                'paternal_surname' => 'Hernandez',
                'maternal_surname' => 'Bautista',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 283 9580',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1985',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $cañadaDeHieloearlyChildhoodSchool->id
            ],
            [
                'name' => 'Liboria',
                'paternal_surname' => 'Martinez',
                'maternal_surname' => 'Bautista',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 589 5074',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1986',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $elDuraznalearlyChildhoodSchool->id
            ],
            [
                'name' => 'Casto',
                'paternal_surname' => 'Ines',
                'maternal_surname' => 'Sanchez',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 360 7369',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1987',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $sanJuanMonteFlorSchoolHostel->id
            ],
            [
                'name' => 'Gustavo',
                'paternal_surname' => 'E.',
                'maternal_surname' => 'Rojas',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Director',
                'telephone' => '951 255 1042',
                'motivo' => null,
                'date_of_entry_into_the_sep' => '10/01/1988',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_id' => $santaMariaPeñolesSchoolHostel->id
            ]
        ];

        // Crear los registros de maestros
        foreach ($teachersData as $data) {
            $teacher = new Teacher();
            $teacher->name = $data['name'];
            $teacher->paternal_surname = $data['paternal_surname'];
            $teacher->maternal_surname = array_key_exists('maternal_surname', $data) ? $data['maternal_surname'] : '';
            $teacher->curp = $data['curp'];
            $teacher->rfc = $data['rfc'];
            $teacher->gender = $data['gender'];
            $teacher->budget_code = $data['budget_code'];
            $teacher->funcion = $data['funcion'];
            $teacher->telephone = $data['telephone'];
            $teacher->motivo = $data['motivo'];
            $teacher->date_of_entry_into_the_sep = $data['date_of_entry_into_the_sep'];
            $teacher->language = $data['language'];
            $teacher->language_variant = $data['language_variant'];
            $teacher->school_id = $data['school_id'];
            $teacher->human_id = $administrativeUser->id;
            $teacher->save();
        }
    }
}