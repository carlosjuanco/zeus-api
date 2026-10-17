<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Models\Community;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * Debilidades identificadas
     * 
     * Si en el futuro se requiere enviar más parámetros de búsqueda siguiendo las buenas prácticas de Laravel (p. ej. parametro3/parametro4), se debe considerar que la búsqueda por fecha utiliza el formato dd/mm/yyyy.
     * 
     * Una solución viable es definir la variable $search como el tercer parámetro. El segundo parámetro sería una variable de texto que especifique el campo de la tabla teachers sobre el cual filtrar. De esta forma, se evitan conflictos con los términos de búsqueda ingresados en $search.
     * 
     * Planteo este escenario con base en la experiencia en proyectos reales, donde es frecuente que los clientes requieran filtros combinados debido al alto volumen de registros coincidentes.
     * 
     * Fuente youtrack: SS0-55 Pruebas unitarias del componente "Profesores"
     * Fuente mejorar la redacción:https://share.gemini.google/ASuWcN3aV7xd
     * 
     * Fortalezas
     * 
     * Agrupar todos los orWhere en un paréntesis SQL.
     * Esto es muy importante: sin agrupar, si luego agregas otro ->where(...) externo (ej. un filtro por school_id), tus orWhere podrían romper la lógica del query.
     * 
     * Sin agrupar se generaría algo como:
     * WHERE school_id = 5 AND name LIKE '%x%' OR telephone LIKE '%x%'
     * 
     * Agrupandolo se generaría algo como:
     * WHERE (name LIKE '%x%' OR telephone LIKE '%x%') AND school_id = 5
     * 
     * Esto lo digo por experiencia propia: recuerdo que al añadir un WHERE los resultados cambiaban por completo, y terminaba teniendo que corregir todo de prisa en producción
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function viewAny($paginate, $search = '')
    {
        // Consultar los campos con búsqueda y paginación
        $teachers = Teacher::select('id', 'name', 'paternal_surname', 'maternal_surname', 'curp', 'rfc', 'gender', 'budget_code', 'funcion', 'telephone', 'motivo', 'date_of_entry_into_the_sep', 'study_profile', 'language', 'language_variant', 'school_id')
            /**
             * with(): se usa para cargar relaciones (Eager Loading)
             * 
             * El problema que resuelve
             * Por defecto, cuando haces ->with('school'), Laravel trae todos los campos de 
             * la tabla shools.
             * 
             * El problema que resuelve la función anónima
             * Limita los campos que se cargan
             * 
             * ¿Por qué incluir id?
             * Laravel necesita el id (o la clave foránea que usa la relación) para poder 
             * mapear cada comunidad con su respectiva escuela.
             * 
             * @see https://laravel.com/docs/9.x/eloquent-relationships#eager-loading
             */
            ->with(['school' => function ($query) {
                // No olvides incluir 'id' que es la FK
                $query->select(['id', 'name']);
            }])
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                ->orWhere('paternal_surname', 'like', "%$search%")
                ->orWhere('maternal_surname', 'like', "%$search%")
                ->orWhere('curp', 'like', "%$search%")
                ->orWhere('rfc', 'like', "%$search%")
                ->orWhere('gender', 'like', "%$search%")
                ->orWhere('budget_code', 'like', "%$search%")
                ->orWhere('funcion', 'like', "%$search%")
                ->orWhere('telephone', 'like', "%$search%")
                ->orWhere('motivo', 'like', "%$search%")
                ->orWhere('date_of_entry_into_the_sep', 'like', "%$search%")
                ->orWhere('study_profile', 'like', "%$search%")
                ->orWhere('language', 'like', "%$search%")
                ->orWhere('language_variant', 'like', "%$search%")
                ->orWhereHas('school', function($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($paginate);

        return response()->json($teachers, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar la solicitud
        /**
         * VALIDACIÓN DEL CAMPO 'name'
         * ===========================
         * 
         * 📌 REGLAS APLICADAS:
         *   - required  → Obligatorio
         *   - string    → Texto plano
         *   - max:20    → Límite de 20 caracteres
         *   - regex     → Patrón de caracteres permitidos
         * 
         * 📝 PATRÓN REGEX: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/
         * 
         * 🔍 QUÉ PERMITE:
         *   ✓ Letras minúsculas (a-z)
         *   ✓ Letras mayúsculas (A-Z)
         *   ✓ Vocales con acento (áéíóú ÁÉÍÓÚ)
         *   ✓ Letra eñe (ñ Ñ)
         *   ✓ Espacios (\s)
         *   ✓ Permite validar todo el texto (+$)
         * 
         * 🚫 QUÉ BLOQUEA:
         *   ✗ Números (0-9)
         *   ✗ Signos de puntuación (.,;:!? etc.)
         *   ✗ Caracteres especiales (@#$%&* etc.)
         *   ✗ Guiones (-) y guiones bajos (_)
         * 
         * 💡 EJEMPLOS:
         *   ✅ "María José"   - Válido
         *   ✅ "Ñañez"        - Válido
         *   ✅ "Álvaro"       - Válido
         *   ❌ "Juan123"      - Inválido (contiene números)
         *   ❌ "María!"       - Inválido (contiene !)
         *   ❌ "Pedro_García" - Inválido (contiene _)
         * 
         * ⚡ RENDIMIENTO:
         *   - Validación server-side (no afecta al cliente)
         *   - La regex se compila eficientemente
         *   - Sin impacto significativo en el rendimiento
         * 
         * 🛡️ SEGURIDAD:
         *   - Previene inyección de caracteres especiales
         *   - Sanitiza nombres de posibles ataques
         *   - Complementa la validación front-end
         * 
         * 📚 DOCUMENTACIÓN OFICIAL:
         * @see https://laravel.com/docs/9.x/validation#rule-regex
         */
        /**
         * VALIDACIÓN DEL CAMPO 'curp'
         * ===========================
         * 
         * CURP (Clave Única de Registro de Población) - México
         * 
         * 📌 REGLAS APLICADAS:
         *   - required: Obligatorio
         *   - string: Texto
         *   - size:18: Exactamente 18 caracteres
         *   - regex: Patrón de estructura CURP
         *   - uppercase: Convertir a mayúsculas
         * 
         * 📝 PATRÓN REGEX: /^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z0-9]{3}\d{2}$/
         * 
         * 🔍 DESGLOSE DEL PATRÓN:
         *   ^                → Inicio de la cadena
         *   [A-Z]{4}         → 4 letras mayúsculas (apellidos y nombre)
         *   \d{6}            → 6 dígitos (fecha: AAMMDD)
         *   [HM]             → Sexo (H=Hombre, M=Mujer)
         *   [A-Z]{2}         → 2 letras (entidad federativa)
         *   [A-Z0-9]{3}      → 3 caracteres alfanuméricos (homoclave)
         *   \d               → 1 dígito (verificador)
         *   $                → Fin de la cadena
         * 
         * 💡 EJEMPLOS VÁLIDOS:
         *   ✅ "GODE561231HDFRRL09" - CURP válida
         *   ✅ "SAZD930215HDFNRL05" - CURP válida
         * 
         * ❌ EJEMPLOS INVÁLIDOS:
         *   ❌ "GODE561231HDFRRL0"  - 17 caracteres (debe ser 18)
         *   ❌ "GODE561231XDFRRL09" - Sexo inválido (debe ser H o M)
         *   ❌ "gode561231hdfrrl09" - Minúsculas (debe ser mayúsculas)
         * 
         * ⚠️ NOTA IMPORTANTE:
         *   Esta validación SOLO verifica la estructura y formato.
         *   NO valida el dígito verificador (requiere algoritmo adicional).
         * 
         * 📚 DOCUMENTACIÓN OFICIAL:
         *   - Regla Regex: https://laravel.com/docs/9.x/validation#rule-regex
         *   - RENAPO: https://www.gob.mx/curp/
         * 
         * 🛠️ RECOMENDACIÓN:
         *   Para una validación completa, combinar con validación del
         *   dígito verificador (algoritmo CURP).
         */

        /**
         * VALIDACIÓN DEL CAMPO 'rfc'
         * ===========================
         * 
         * RFC (Registro Federal de Contribuyentes) - Persona Física
         * 
         * 📌 REGLAS APLICADAS:
         *   - required: Obligatorio
         *   - string: Texto
         *   - size:13: Exactamente 13 caracteres
         *   - regex: Patrón de estructura RFC
         *   - uppercase: Convertir a mayúsculas
         * 
         * 📝 PATRÓN REGEX: /^[A-Z]{4}\d{6}[A-Z0-9]{3}$/
         * 
         * 🔍 DESGLOSE DEL PATRÓN:
         *   ^                → Inicio de la cadena
         *   [A-Z]{4}         → 4 letras mayúsculas (apellidos y nombre)
         *   \d{6}            → 6 dígitos (fecha: AAMMDD)
         *   [A-Z0-9]{3}      → 3 caracteres alfanuméricos (homoclave)
         *   $                → Fin de la cadena
         * 
         * 💡 EJEMPLOS VÁLIDOS:
         *   ✅ "GODE561231HDF" - RFC válido
         *   ✅ "SAZD930215HN1" - RFC válido
         *   ✅ "PEPE850101HDF" - RFC válido
         * 
         * ❌ EJEMPLOS INVÁLIDOS:
         *   ❌ "GODE561231HDF"   - 12 caracteres (debe ser 13)
         *   ❌ "GODE561231HD"    - Faltan caracteres
         *   ❌ "gode561231hdf"   - Minúsculas (debe ser mayúsculas)
         *   ❌ "GODE561231HDF1"  - 14 caracteres (excede)
         * 
         * 📝 CONSIDERACIONES ESPECIALES:
         *   - La homoclave puede incluir números y letras (A-Z, 0-9)
         *   - El SAT asigna la homoclave para evitar duplicidades
         *   - Los 4 caracteres iniciales son SIEMPRE letras
         * 
         * ⚠️ NOTA IMPORTANTE:
         *   Esta validación SOLO verifica la estructura y formato.
         *   NO valida el dígito verificador (requiere algoritmo adicional).
         * 
         * 📚 DOCUMENTACIÓN OFICIAL:
         *   - Regla Regex: https://laravel.com/docs/9.x/validation#rule-regex
         *   - SAT: https://www.sat.gob.mx/
         * 
         * 🛠️ RECOMENDACIÓN:
         *   Para una validación completa, combinar con validación del
         *   dígito verificador (algoritmo RFC).
         */

        /**
         * VALIDACIÓN DEL CAMPO 'teléfono'
         * ================================
         * 
         * 📌 REGLAS APLICADAS:
         *   - required: Obligatorio
         *   - string: Texto
         *   - regex: Patrón de formato telefónico
         * 
         * 📝 PATRÓN REGEX: /^\d{3} \d{3} \d{4}$/
         * 
         * 🔍 DESGLOSE DEL PATRÓN:
         *   ^                → Inicio de la cadena
         *   \d{3}            → 3 dígitos (código de área/lada)
         *   [ ]              → Espacio en blanco
         *   \d{3}            → 3 dígitos (primer bloque)
         *   [ ]              → Espacio en blanco
         *   \d{4}            → 4 dígitos (segundo bloque)
         *   $                → Fin de la cadena
         * 
         * 💡 EJEMPLOS VÁLIDOS:
         *   ✅ "951 213 0374" - Formato correcto
         *   ✅ "555 123 4567" - Formato correcto
         *   ✅ "442 987 6543" - Formato correcto
         * 
         * ❌ EJEMPLOS INVÁLIDOS:
         *   ❌ "9512130374"   - Sin espacios
         *   ❌ "951 213 037"  - Faltan dígitos (3+3+3 en lugar de 3+3+4)
         *   ❌ "951-213-0374" - Guiones en lugar de espacios
         *   ❌ "951 213 03745" - Dígitos de más
         *   ❌ "abc 213 0374" - Letras en lugar de números
         * 
         * 📚 DOCUMENTACIÓN OFICIAL:
         *   - Regla Regex: https://laravel.com/docs/9.x/validation#rule-regex
         */

        /**
         * VALIDACIÓN DE FECHAS: date vs date_format
         * =========================================
         *
         * 📌 REGLAS APLICADAS (CASO DE ERROR):
         *   'date_of_entry_into_the_sep' => 'nullable|date|date_format:d/m/Y'
         *
         * ❌ PROBLEMA DETECTADO:
         *   La regla 'date' se ejecuta ANTES que 'date_format'.
         *   'date' usa strtotime() de PHP, que NO reconoce el formato 'd/m/Y'
         *   (lo interpreta como 'm/d/Y'). Por eso, un valor como "26/07/2026"
         *   falla antes de que 'date_format' pueda validar el formato correcto.
         *
         * 🔧 SOLUCIÓN APLICADA:
         *   'date_of_entry_into_the_sep' => 'nullable|date_format:d/m/Y'
         *
         * ✅ POR QUÉ FUNCIONA:
         *   - 'date_format' valida POR SÍ SOLO que el string coincida
         *     exactamente con el formato y que sea una fecha real en ese formato.
         *   - No necesita la regla 'date' adicional.
         *
         * 📝 COMPORTAMIENTO DE CADA REGLA:
         *   +----------------+--------------------------------------------------+
         *   | Regla          | Comportamiento                                   |
         *   +----------------+--------------------------------------------------+
         *   | 'date'         | Valida que el valor sea una fecha REAL usando    |
         *   |                | strtotime(). Acepta muchos formatos, pero NO     |
         *   |                | 'd/m/Y' si el día > 12 (lo confunde con m/d/Y). |
         *   +----------------+--------------------------------------------------+
         *   | 'date_format'  | Valida que el string coincida EXACTAMENTE con    |
         *   |                | el formato especificado (ej: d/m/Y) Y que sea   |
         *   |                | una fecha válida. Es más estricto y predecible.  |
         *   +----------------+--------------------------------------------------+
         *
         * 💡 EJEMPLOS PRÁCTICOS:
         *   +------------------------------------+---------------------------+
         *   | Validación                         | "26/07/2026"  | "2026-07-26" |
         *   +------------------------------------+--------------+-------------+
         *   | date                               | ❌ Falla     | ✅ Válido   |
         *   | date_format:Y-m-d                  | ❌ Falla     | ✅ Válido   |
         *   | date_format:d/m/Y                  | ✅ Válido    | ❌ Falla    |
         *   | date|date_format:d/m/Y (con error) | ❌ Falla     | ✅ Válido   |
         *   | date_format:d/m/Y (solo)           | ✅ Válido    | ❌ Falla    |
         *   +------------------------------------+--------------+-------------+
         *
         * 🎯 RECOMENDACIÓN FINAL:
         *   - Para formatos específicos (como d/m/Y), USA SOLO 'date_format'.
         *   - Para formatos estándar (Y-m-d), PUEDES USAR 'date' o 'date_format'.
         *   - Si usas 'date', asegúrate de que el formato sea reconocible por PHP.
         *
         * 📚 DOCUMENTACIÓN OFICIAL:
         *   - Regla date: https://laravel.com/docs/9.x/validation#rule-date
         *   - Regla date_format: https://laravel.com/docs/9.x/validation#rule-date-format
         *   - Formatos de PHP: https://www.php.net/manual/es/datetime.format.php
         */

        $validated = $request->validate([
            'name' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'paternal_surname' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'maternal_surname' => 'nullable|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'curp' => 'required|string|max:18|regex:/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z0-9]{3}\d{2}$/|uppercase',
            'rfc' => 'required|string|max:13|regex:/^[A-Z]{4}\d{6}[A-Z0-9]{3}$/|uppercase',
            'gender' => 'required|in:Hombre,Mujer',
            'budget_code' => 'required|string|max:23',
            'funcion' => 'nullable|in:Docente,Administrativo,Docente con grupo,Director',
            'telephone' => 'required|string|regex:/^\d{3} \d{3} \d{4}$/',
            'motivo' => 'nullable|integer|between:1,99',
            'date_of_entry_into_the_sep' => 'nullable|date_format:d/m/Y',
            'study_profile' => 'nullable|in:Titulado de U.P.N.,Pasante de normal superior,Pasante de maestría,Pasante de U.P.N.',
            'language' => 'nullable|in:Mixteca,Cañada,Costa,Istmo,Papaloapan,Sierra sur,Sierra norte,Valles centrales',
            'language_variant' => 'nullable|in:Alta,Baja',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Registramos quién guarda el registro
        $validated["human_id"] = $request->user()->id;

        // Guardar el registro
        Teacher::create($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'paternal_surname' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'maternal_surname' => 'nullable|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'curp' => 'required|string|max:18|regex:/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z0-9]{3}\d{2}$/|uppercase',
            'rfc' => 'required|string|max:13|regex:/^[A-Z]{4}\d{6}[A-Z0-9]{3}$/|uppercase',
            'gender' => 'required|in:Hombre,Mujer',
            'budget_code' => 'required|string|max:23',
            'funcion' => 'nullable|in:Docente,Administrativo,Docente con grupo,Director',
            'telephone' => 'required|string|regex:/^\d{3} \d{3} \d{4}$/',
            'motivo' => 'nullable|integer|between:1,99',
            'date_of_entry_into_the_sep' => 'nullable|date_format:d/m/Y',
            'study_profile' => 'nullable|in:Titulado de U.P.N.,Pasante de normal superior,Pasante de maestría,Pasante de U.P.N.',
            'language' => 'nullable|in:Mixteca,Cañada,Costa,Istmo,Papaloapan,Sierra sur,Sierra norte,Valles centrales',
            'language_variant' => 'nullable|in:Alta,Baja',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Actualizar el registro con binding implícito
        $teacher->update($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Eliminar el registro con binding implícito
        $teacher->delete();

        return response()->json([
            'message' => '¡Listo! Tu dato fue eliminado bien'
        ], 200);
    }

    /**
     * Obtener reporte por comunidad
     * 
     * Realizar una consulta para mostrarlo en el componente "reporte por comunidad"
     * que es igual a la "Imágen 1" del documento "Manual de requerimiento".
     * 
     * Este método ya no forma parte de los básicos para un CRUD.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getReportByCommunity()
    {
        /**
         * groupBy después de get()
         * ===========================
         * 
         * COMPORTAMIENTO: Después de obtener todos los registros, al aplicar group by, 
         * ya no es directo a la base de datos, si no que ya es aplicado a una collection, 
         * esto hace que devuelva
         *  
         *  Illuminate\Support\Collection {#1315 // app/Http/Controllers/TeacherController.php:477
         *     #items: array:4 [
         *       "Primaria" => Illuminate\Support\Collection 
         *       ]
         *
         *   Es decir agrupa por el tipo de escuela en la collection
         * 
         * @see: https://chat.deepseek.com/share/22xc37y8kk8q3lqe9r
         */

        /**
         * inner join en vez de ORM puro
         * ===========================
         * 
         * COMPORTAMIENTO: Tal como recomienda DeepSeek, conviene utilizar esta opción porque
         * actúa directamente sobre la base de datos; en cambio, el ORM opera una vez que ya
         * ha obtenido todos los registros y los procesa en memoria.
         * 
         * @see: https://chat.deepseek.com/share/22xc37y8kk8q3lqe9r
         */

        $resultados = DB::table('teachers as t')
            ->join('schools as s', 't.school_id', '=', 's.id')
            ->join('communities as c', 's.community_id', '=', 'c.id')
            ->whereIn('s.type_of_school', ['Primaria', 'Preescolar', 'Inicial', 'Albergues escolares'])
            ->select([
                's.type_of_school',
                'c.name as nombreComunidad',
                's.name as nombreEscuela',
                's.secondary_number',
                't.telephone',
                't.id',
                's.key as claveEscuelaCompleta',
            ])
            ->selectRaw("CONCAT(t.name, ' ', t.paternal_surname, ' ', t.maternal_surname) as nombreProfesor")
            ->selectRaw("if(s.type_of_school != 'Albergues escolares', SUBSTRING(s.key, 6), s.key) as claveEscuela")
            ->orderBy('s.type_of_school')
            ->get()
            ->groupBy('type_of_school');

        // Accesos directos ya agrupados y ordenados
        $primarias = $resultados->get('Primaria', collect());
        $preescolares = $resultados->get('Preescolar', collect());
        $iniciales = $resultados->get('Inicial', collect());
        $alberguesEscolares = $resultados->get('Albergues escolares', collect());

        $allCommunities = Community::all();

        $coleccionFinal = $allCommunities->map(function ($community) 
            use ($primarias, $preescolares, $iniciales, $alberguesEscolares) {
            // La primera coincidencia
            $primaria = $primarias->where('nombreComunidad', $community->name)->first();
            $preescolar = $preescolares->where('nombreComunidad', $community->name)->first();
            $inicial = $iniciales->where('nombreComunidad', $community->name)->first();
            $albergueEscolar = $alberguesEscolares->where('nombreComunidad', $community->name)->first();
            return [
                'nombreComunidad' => $community->name,
                'primaria' => $primaria ? $primaria->nombreEscuela : '',
                'claveEscuelaPrimaria' => $primaria ? $primaria->claveEscuela : '',
                'claveEscuelaPrimariaCompleta' => $primaria ? $primaria->claveEscuelaCompleta : '',
                'directivoPrimaria' => $primaria ? $primaria->nombreProfesor : '',
                'idDirectivoPrimaria' => $primaria ? $primaria->id : '',
                'telefonoDelProfesorDeLaPrimaria' => $primaria ? $primaria->telephone : '',
                'numeroProgresivoPrimaria'=> $primaria ? $primaria->secondary_number : '',

                'preescolar' => $preescolar ? $preescolar->nombreEscuela : '',
                'claveEscuelaPreescolar' => $preescolar ? $preescolar->claveEscuela : '',
                'claveEscuelaPreescolarCompleta' => $preescolar ? $preescolar->claveEscuelaCompleta : '',
                'directivoPreescolar' => $preescolar ? $preescolar->nombreProfesor : '',
                'idDirectivoPreescolar' => $preescolar ? $preescolar->id : '',
                'telefonoDelProfesorDePreescolar' => $preescolar ? $preescolar->telephone : '',
                'numeroProgresivoPreescolar' => $preescolar ? $preescolar->secondary_number : '',

                'inicial' => $inicial ? $inicial->nombreEscuela : '',
                'claveEscuelaInicial'   => $inicial ? $inicial->claveEscuela : '',
                'claveEscuelaInicialCompleta' => $inicial ? $inicial->claveEscuelaCompleta : '',
                'directivoInicial'=> $inicial ? $inicial->nombreProfesor : '',
                'idDirectivoInicial'=> $inicial ? $inicial->id : '',
                'telefonoDelProfesorDeInicial'  => $inicial ? $inicial->telephone : '',

                'albergueEscolar' => $albergueEscolar ? $albergueEscolar->nombreEscuela : '',
                'claveEscuelaAlbergueEscolar'   => $albergueEscolar ? $albergueEscolar->claveEscuela : '',
                'directivoAlbergueEscolar'=> $albergueEscolar ? $albergueEscolar->nombreProfesor : '',
                'idDirectivoAlbergueEscolar'=> $albergueEscolar ? $albergueEscolar->id : '',
                'telefonoDelProfesorDelAlbergueEscolar'  => $albergueEscolar ? $albergueEscolar->telephone : '',
            ];
        });

        return response()->json($coleccionFinal->toArray(), 200);
    }

    /**
     * Obtener información del profesor
     * 
     * Realizar una consulta donde obtegamos los datos completos de un profesor, 
     * para mostrarlo en el componente "ProfesorInformacion", que tiene funcionamiento 
     * en dos módulos "Profesores" y "reporte por comunidad"
     * 
     * Este método ya no forma parte de los básicos para un CRUD.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getInformationFromTheTeacher($id)
    {
        $teacher = DB::table('teachers as t')
            ->select([
                't.id',
                't.name',
                't.paternal_surname',
                't.maternal_surname',
                't.curp',
                't.rfc',
                't.gender',
                't.budget_code',
                't.funcion',
                't.telephone',
                't.motivo',
                't.date_of_entry_into_the_sep',
                't.study_profile',
                't.language',
                't.language_variant',
                't.school_id'
            ])
            ->selectRaw("CONCAT(t.name, ' ', t.paternal_surname, ' ', t.maternal_surname) as full_name")
            ->where('id', $id)
            ->first();

        $school = DB::table('schools as c')
            ->select([
                'c.id',
                'c.name',
            ])
            ->where('id', $teacher->school_id)
            ->first();

        // 👇 aquí asigno la escuela como propiedad
        $teacher->school = $school;

        return response()->json($teacher, 200);
    }
}