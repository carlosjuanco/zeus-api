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
                'telephone' => '951 123 4567',
                'reason' => 1,
                'date_of_entry_into_the_sep' => '30/08/1950',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_index' => $sanJuanMonteFlorPrimarySchool->id
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
                'reason' => null,
                'date_of_entry_into_the_sep' => '20/01/1980',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_index' => $santaMariaPeñolesPrimarySchool->id
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
                'reason' => 2,
                'date_of_entry_into_the_sep' => '05/04/1940',
                'language' => 'Costa',
                'language_variant' => null,
                'school_index' => $sanPedroChululaPrimarySchool->id
            ],
            [
                'name' => 'Arturo',
                'paternal_surname' => 'Pachuca',
                'maternal_surname' => 'Lopez',
                'curp' => 'GOTC940404HMCRRL04',
                'rfc' => 'GOTC940404HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-004-01',
                'funcion' => 'Director',
                'telephone' => '951 456 7890',
                'reason' => null,
                'date_of_entry_into_the_sep' => '30/01/1970',
                'language' => 'Istmo',
                'language_variant' => 'Alta',
                'school_index' => $santaCatarinaEstetlaPrimarySchool->id
            ],
            [
                'name' => 'Vilma',
                'paternal_surname' => 'Lopez',
                'maternal_surname' => 'Guzman',
                'curp' => 'DIFL750515HMCRRN05',
                'rfc' => 'DIFL750515HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-005-01',
                'funcion' => 'Dorector',
                'telephone' => '951 115 2876',
                'reason' => 3,
                'date_of_entry_into_the_sep' => '20/11/1977',
                'language' => 'Papaloapan',
                'language_variant' => 'Baja',
                'school_index' => $corralDePiedraPrimarySchool->id
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
                'reason' => null,
                'date_of_entry_into_the_sep' => '01/10/1966',
                'language' => 'Sierra sur',
                'language_variant' => null,
                'school_index' => $cañadaDeHieloPrimarySchool->id
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
                'reason' => 1,
                'date_of_entry_into_the_sep' => '08/10/1968',
                'language' => 'Sierra norte',
                'language_variant' => 'Alta',
                'school_index' => $elDuraznalPrimarySchool->id
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
                'reason' => null,
                'date_of_entry_into_the_sep' => '20/07/1969',
                'language' => 'Valles centrales',
                'language_variant' => 'Baja',
                'school_index' => $losSabinosPrimarySchool->id
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
                'reason' => 2,
                'date_of_entry_into_the_sep' => '01/05/1970',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_index' => $recibimientoPrimarySchool->id
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
                'reason' => null,
                'date_of_entry_into_the_sep' => '03/01/1971',
                'language' => 'Cañada',
                'language_variant' => 'Baja',
                'school_index' => $rioManzanitaPrimarySchool->id
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
                'reason' => 3,
                'date_of_entry_into_the_sep' => '15/09/1972',
                'language' => 'Costa',
                'language_variant' => 'Alta',
                'school_index' => $rioCachoPrimarySchool->id
            ],
            [
                'name' => 'Fernando',
                'paternal_surname' => 'Ramos',
                'maternal_surname' => 'Peña',
                'curp' => 'RAFE821212HMCRNN12',
                'rfc' => 'RAFE821212HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-012-01',
                'funcion' => 'Administrativo',
                'telephone' => 9512345678,
                'reason' => null,
                'date_of_entry_into_the_sep' => '2022-07-01',
                'study_profile' => 'Pasante de U.P.N.',
                'language' => 'Istmo',
                'language_variant' => null,
                'school_index' => 11
            ],
            [
                'name' => 'Luz',
                'paternal_surname' => 'Vargas',
                'maternal_surname' => 'Acosta',
                'curp' => 'VAAL831313HMCRNN13',
                'rfc' => 'VAAL831313HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-013-01',
                'funcion' => 'Docente con grupo',
                'telephone' => 9513456789,
                'reason' => 1,
                'date_of_entry_into_the_sep' => '2018-05-20',
                'study_profile' => 'Titulado de U.P.N.',
                'language' => 'Papaloapan',
                'language_variant' => 'Alta',
                'school_index' => 0
            ],
            [
                'name' => 'Alejandro',
                'paternal_surname' => 'Mora',
                'maternal_surname' => 'Campos',
                'curp' => 'MOCA841414HMCRNN14',
                'rfc' => 'MOCA841414HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-014-01',
                'funcion' => 'Docente',
                'telephone' => 9514567890,
                'reason' => null,
                'date_of_entry_into_the_sep' => '2020-11-15',
                'study_profile' => 'Pasante de normal superior',
                'language' => 'Sierra sur',
                'language_variant' => 'Baja',
                'school_index' => 1
            ],
            [
                'name' => 'Rosa',
                'paternal_surname' => 'Paredes',
                'maternal_surname' => 'Franco',
                'curp' => 'PAFR851515HMCRNN15',
                'rfc' => 'PAFR851515HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-015-01',
                'funcion' => 'Directora',
                'telephone' => 9515678901,
                'reason' => 2,
                'date_of_entry_into_the_sep' => '2016-02-10',
                'study_profile' => 'Pasante de maestría',
                'language' => 'Sierra norte',
                'language_variant' => 'Alta',
                'school_index' => 2
            ],
            [
                'name' => 'Manuel',
                'paternal_surname' => 'Guerrero',
                'maternal_surname' => 'Molina',
                'curp' => 'GUMR861616HMCRNN16',
                'rfc' => 'GUMR861616HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-016-01',
                'funcion' => 'Docente con grupo',
                'telephone' => 9516789012,
                'reason' => null,
                'date_of_entry_into_the_sep' => '2021-06-01',
                'study_profile' => 'Titulado de U.P.N.',
                'language' => 'Valles centrales',
                'language_variant' => 'Baja',
                'school_index' => 3
            ],
            [
                'name' => 'Teresa',
                'paternal_surname' => 'Rivas',
                'maternal_surname' => 'Cortés',
                'curp' => 'RACT871717HMCRNN17',
                'rfc' => 'RACT871717HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-017-01',
                'funcion' => 'Docente',
                'telephone' => 9517890123,
                'reason' => 3,
                'date_of_entry_into_the_sep' => '2019-12-05',
                'study_profile' => 'Pasante de normal superior',
                'language' => 'Mixteca',
                'language_variant' => 'Alta',
                'school_index' => 4
            ],
            [
                'name' => 'Jorge',
                'paternal_surname' => 'Navarro',
                'maternal_surname' => 'Salazar',
                'curp' => 'NASJ881818HMCRNN18',
                'rfc' => 'NASJ881818HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-018-01',
                'funcion' => 'Administrativo',
                'telephone' => 9518901234,
                'reason' => null,
                'date_of_entry_into_the_sep' => '2022-09-01',
                'study_profile' => 'Pasante de U.P.N.',
                'language' => 'Cañada',
                'language_variant' => null,
                'school_index' => 5
            ],
            [
                'name' => 'Monica',
                'paternal_surname' => 'Luna',
                'maternal_surname' => 'Ponce',
                'curp' => 'LUPM891919HMCRNN19',
                'rfc' => 'LUPM891919HCR',
                'gender' => 'Mujer',
                'budget_code' => 'BUD-2024-001-001-019-01',
                'funcion' => 'Docente con grupo',
                'telephone' => 9519012345,
                'reason' => 1,
                'date_of_entry_into_the_sep' => '2017-07-15',
                'study_profile' => 'Titulado de U.P.N.',
                'language' => 'Costa',
                'language_variant' => 'Alta',
                'school_index' => 6
            ],
            [
                'name' => 'Raúl',
                'paternal_surname' => 'Ortiz',
                'maternal_surname' => 'Guzmán',
                'curp' => 'OGOR902020HMCRNN20',
                'rfc' => 'OGOR902020HCR',
                'gender' => 'Hombre',
                'budget_code' => 'BUD-2024-001-001-020-01',
                'funcion' => 'Docente',
                'telephone' => 9510123456,
                'reason' => null,
                'date_of_entry_into_the_sep' => '2020-10-01',
                'study_profile' => 'Pasante de normal superior',
                'language' => 'Istmo',
                'language_variant' => 'Baja',
                'school_index' => 7
            ]
        ];

        // Crear los registros de maestros
        foreach ($teachersData as $data) {
            // Obtener una escuela según el índice, usando el operador módulo para ciclar si es necesario
            $school = $schools[$data['school_index'] % $schools->count()];

            $teacher = new Teacher();
            $teacher->name = $data['name'];
            $teacher->paternal_surname = $data['paternal_surname'];
            $teacher->maternal_surname = $data['maternal_surname'];
            $teacher->curp = $data['curp'];
            $teacher->rfc = $data['rfc'];
            $teacher->gender = $data['gender'];
            $teacher->budget_code = $data['budget_code'];
            $teacher->funcion = $data['funcion'];
            $teacher->telephone = $data['telephone'];
            $teacher->reason = $data['reason'];
            $teacher->date_of_entry_into_the_sep = $data['date_of_entry_into_the_sep'];
            $teacher->study_profile = $data['study_profile'];
            $teacher->language = $data['language'];
            $teacher->language_variant = $data['language_variant'];
            $teacher->school_id = $school->id;
            $teacher->human_id = $human->id;
            $teacher->save();
        }

        $this->command->info('Se han creado ' . count($teachersData) . ' maestros exitosamente.');
    }
}