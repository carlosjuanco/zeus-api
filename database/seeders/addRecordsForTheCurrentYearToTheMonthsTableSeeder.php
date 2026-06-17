<?php

namespace Database\Seeders;

use App\Models\Month;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Carbon\Carbon;

class addRecordsForTheCurrentYearToTheMonthsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fecha = Carbon::now();

        $mesEnero = new Month();
        $mesEnero->month = "Enero";
        $mesEnero->status = $fecha->month == 1 ? "Abierto" : "Cerrado";
        $mesEnero->anio = 2026;
        $mesEnero->human_id = 1;
        $mesEnero->save();

        $mesFebrero = new Month();
        $mesFebrero->month = "Febrero";
        $mesFebrero->status = $fecha->month == 2 ? "Abierto" : "Cerrado";
        $mesFebrero->anio = 2026;
        $mesFebrero->month_id = 1;
        $mesFebrero->human_id = 1;
        $mesFebrero->save();

        $mesMarzo = new Month();
        $mesMarzo->month = "Marzo";
        $mesMarzo->status = $fecha->month == 3 ? "Abierto" : "Cerrado";
        $mesMarzo->anio = 2026;
        $mesMarzo->month_id = 2;
        $mesMarzo->human_id = 1;
        $mesMarzo->save();

        $mesAbril = new Month();
        $mesAbril->month = "Abril";
        $mesAbril->status = $fecha->month == 4 ? "Abierto" : "Cerrado";
        $mesAbril->anio = 2026;
        $mesAbril->month_id = 3;
        $mesAbril->human_id = 1;
        $mesAbril->save();

        $mesMayo = new Month();
        $mesMayo->month = "Mayo";
        $mesMayo->status = $fecha->month == 5 ? "Abierto" : "Cerrado";
        $mesMayo->anio = 2026;
        $mesMayo->month_id = 4;
        $mesMayo->human_id = 1;
        $mesMayo->save();

        $mesJunio = new Month();
        $mesJunio->month = "Junio";
        $mesJunio->status = $fecha->month == 6 ? "Abierto" : "Cerrado";
        $mesJunio->anio = 2026;
        $mesJunio->month_id = 5;
        $mesJunio->human_id = 1;
        $mesJunio->save();

        $mesJulio = new Month();
        $mesJulio->month = "Julio";
        $mesJulio->status = $fecha->month == 7 ? "Abierto" : "Cerrado";
        $mesJulio->anio = 2026;
        $mesJulio->month_id = 6;
        $mesJulio->human_id = 1;
        $mesJulio->save();

        $mesAgosto = new Month();
        $mesAgosto->month = "Agosto";
        $mesAgosto->status = $fecha->month == 8 ? "Abierto" : "Cerrado";
        $mesAgosto->anio = 2026;
        $mesAgosto->month_id = 7;
        $mesAgosto->human_id = 1;
        $mesAgosto->save();

        $mesSeptiembre = new Month();
        $mesSeptiembre->month = "Septiembre";
        $mesSeptiembre->status = $fecha->month == 9 ? "Abierto" : "Cerrado";
        $mesSeptiembre->anio = 2026;
        $mesSeptiembre->month_id = 8;
        $mesSeptiembre->human_id = 1;
        $mesSeptiembre->save();

        $mesOctubre = new Month();
        $mesOctubre->month = "Octubre";
        $mesOctubre->status = $fecha->month == 10 ? "Abierto" : "Cerrado";
        $mesOctubre->anio = 2026;
        $mesOctubre->month_id = 9;
        $mesOctubre->human_id = 1;
        $mesOctubre->save();

        $mesNoviembre = new Month();
        $mesNoviembre->month = "Noviembre";
        $mesNoviembre->status = $fecha->month == 11 ? "Abierto" : "Cerrado";
        $mesNoviembre->anio = 2026;
        $mesNoviembre->month_id = 10;
        $mesNoviembre->human_id = 1;
        $mesNoviembre->save();

        $mesDiciembre = new Month();
        $mesDiciembre->month = "Diciembre";
        $mesDiciembre->status = $fecha->month == 12 ? "Abierto" : "Cerrado";
        $mesDiciembre->anio = 2026;
        $mesDiciembre->month_id = 11;
        $mesDiciembre->human_id = 1;
        $mesDiciembre->save();
    }
}
