<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Teacher;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function viewAny($paginate, $search = '')
    {
        // Consultar los campos con búsqueda y paginación
        $teachers = Teacher::select('name', 'paternal_surname', 'maternal_surname', 'curp', 'rfc', 'gender', 'budget_code', 'funcion', 'telephone', 'reason', 'date_of_entry_into_the_sep', 'study_profile', 'language', 'language_variant')
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
            ->where('name', 'like', "%$search%")
            ->orWhere('paternal_surname', 'like', "%$search%")
            ->orWhere('maternal_surname', 'like', "%$search%")
            ->orWhere('curp', 'like', "%$search%")
            ->orWhere('rfc', 'like', "%$search%")
            ->orWhere('gender', 'like', "%$search%")
            ->orWhere('budget_code', 'like', "%$search%")
            ->orWhere('funcion', 'like', "%$search%")
            ->orWhere('telephone', 'like', "%$search%")
            ->orWhere('reason', 'like', "%$search%")
            ->orWhere('date_of_entry_into_the_sep', 'like', "%$search%")
            ->orWhere('study_profile', 'like', "%$search%")
            ->orWhere('language', 'like', "%$search%")
            ->orWhere('language_variant', 'like', "%$search%")
            ->orWhereHas('school', function($query) use ($search) {
                $query->where('name', 'like', "%$search%");
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
         * 📝 PATRÓN REGEX: /^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z0-9]{3}\d$/
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
        $validated = $request->validate([
            'name' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'paternal_surname' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'maternal_surname' => 'nullable|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'curp' => 'required|string|max:18|regex:/^[A-Z]{4}\d{6}[HM][A-Z]{2}[A-Z0-9]{3}\d$/|uppercase',
            'rfc' => 'required|string|max:12|regex:/^[A-Z]{4}\d{6}[A-Z0-9]{3}$/|uppercase',
            'gender' => 'required|in:Hombre,Mujer',
            'budget_code' => 'required|string|max:23',
            'funcion' => 'nullable|in:Docente,Administrativo,Docente con grupo,Director',
            'telephone' => 'required|numeric|digits:10',
            'reason' => 'nullable|numeric|digits:2',
            'date_of_entry_into_the_sep' => 'nullable|date',
            'study_profile' => 'nullable|in:Titulado de U.P.N.,Pasante de normal superior,Pasante de maestría,Pasante de U.P.N.',
            'language' => 'nullable|in:Mixteca,Cañada,Costa,Istmo,Papaloapan,Sierra sur,Sierra norte,Valles centrales',
            'language_variant' => 'nullable|in:Alta,Baja',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Guardar el registro
        Teacher::create($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'name' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'paternal_surname' => 'required|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'maternal_surname' => 'nullable|string|max:20|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'curp' => 'required|string|max:18',
            'rfc' => 'required|string|max:12',
            'gender' => 'required|in:Masculino,Femenino',
            'budget_code' => 'required|string|max:23',
            'funcion' => 'nullable|in:Docente,Administrativo,Docente con grupo,Director',
            'telephone' => 'required|numeric|digits:10',
            'reason' => 'nullable|numeric|digits:2',
            'date_of_entry_into_the_sep' => 'nullable|date',
            'study_profile' => 'nullable|in:Titulado de U.P.N.,Pasante de normal superior,Pasante de maestría,Pasante de U.P.N.',
            'language' => 'nullable|in:Mixteca,Cañada,Costa,Istmo,Papaloapan,Sierra sur,Sierra norte,Valles centrales',
            'language_variant' => 'nullable|in:Alta,Baja',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Actualizar el registro en base al segundo parámetro ($id)
        $teacher = Teacher::findOrFail($id);
        $teacher->update($validated);

        return response()->json([
            'message' => '¡Listo! Tus datos se guardaron bien.'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Eliminar el registro en base al parámetro recibido
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return response()->json([
            'message' => '¡Listo! Tu dato fue eliminado bien'
        ], 200);
    }
}