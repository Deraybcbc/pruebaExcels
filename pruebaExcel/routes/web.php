<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/screenExcel', [AlumnoController::class, 'screenExcelAlumno'])->name('screenExcel');
Route::post('/importExcel', [AlumnoController::class, 'import'])->name('excel.import');
