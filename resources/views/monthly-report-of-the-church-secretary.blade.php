<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { color: #2c3e50; }
    </style>
</head>
<body>
    <h1>{{ $titulo }}</h1>
    <table class="table is-fullwidth dashed-bordered has-text-centered is-bordered mb-3">
        <tr>
            <td class="is-vcentered">
                
            </td>
            @foreach ($contenido['concepts'] as $concept)
                <td class="is-vcentered">
                    {{ $concept->concept }}
                </td>
            @endforeach
        </tr>
        @foreach ($contenido['semanas'] as $index => $semana)
            <tr>
                <td>
                    {{ ($index == 0 ? 'Primera semana' : ($index == 1 ? 'Segunda semana' : ($index == 2 ? 'Tercera semana' : ($index == 3 ? 'Cuarta semana' : '') ) ) ) }}
                </td>
                @foreach ($semana as $week)
                    <td>
                        {{ $week->value }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>