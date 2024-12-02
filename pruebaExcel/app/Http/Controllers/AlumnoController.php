<?php

namespace App\Http\Controllers;

use App\Models\alumno;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AlumnoController extends Controller
{
    public function screenExcelAlumno(){
        return view('alumno');
    }

    public function import(Request $request)
    {

        // Validar si el archivo es Excel
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        // Obtener el archivo cargado
        $file = $request->file('file');

        // Cargar el archivo Excel
        $spreadsheet = IOFactory::load($file);

        // Obtener la primera hoja
        $sheet = $spreadsheet->getActiveSheet();

        $rowIndex = 1;
        // Iterar sobre las filas y leer los datos
        foreach ($sheet->getRowIterator() as $row) {
            // Omitir la primera fila (encabezados)
            if ($rowIndex == 1) {
                $rowIndex++;
                continue;  // Salta la primera fila
            }
            // Obtener las celdas de cada fila
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Para incluir celdas vacías
            $data = [];


            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }


            // Verificar si la fecha es válida y convertirla
            $fechaNacimiento = $data[3]; // Fecha de nacimiento
            if (is_numeric($fechaNacimiento)) {
                // Si el valor es un número, se convierte a fecha
                $fechaNacimiento = Date::excelToDateTimeObject($fechaNacimiento)->format('Y-m-d');
            } else {
                // Si el valor no es un número, podría ser una cadena de texto (fecha)
                try {
                    $fechaNacimiento = \Carbon\Carbon::createFromFormat('d/m/Y', $fechaNacimiento)->format('Y-m-d');
                } catch (\Exception $e) {

                }
            }

            // Comprobar si el alumno ya existe en la base de datos por nombre y apellido
            $existingAlumno = alumno::where('nombre', $data[0])
                ->where('apellidos', $data[1])
                ->first();

            if ($existingAlumno) {
                // Solo actualizamos si hay un cambio en los datos
                if ($existingAlumno->nombre != $data[0] || $existingAlumno->apellidos != $data[1] || $existingAlumno->edad != $data[2] || $existingAlumno->FechaNacimiento != $fechaNacimiento) {
                    $existingAlumno->update([
                        'nombre' => $data[0],
                        'apellidos' => $data[1],
                        'edad' => $data[2],
                        'FechaNacimiento' => $fechaNacimiento,
                        'updated_at' => now(),
                    ]);
                }
            } else {
                // Si el alumno no existe, insertarlo
                alumno::create([
                    'nombre' => $data[0],
                    'apellidos' => $data[1],
                    'edad' => $data[2],
                    'FechaNacimiento' => $fechaNacimiento,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        //return back()->with('success', 'Alumnos importados correctamente.');
        return response()->json(['status' => 'ok', 'mensaje' => 'Se importaron exitosamente los alumnos']);
    }
}
