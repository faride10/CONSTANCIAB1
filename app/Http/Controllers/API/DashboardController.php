<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Conferencia;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Constancia;

class DashboardController extends Controller
{
    
    public function adminSummary(Request $request)
    {
        
        $activeConferences = Conferencia::count();
        $registeredStudents = Alumno::count();
        $activeTeachers = Docente::count();
        $issuedCertificates = Constancia::count();

        return response()->json([
            'active_conferences' => $activeConferences,
            'registered_students' => $registeredStudents,
            'active_teachers' => $activeTeachers,
            'issued_certificates' => $issuedCertificates,
        ]);
    }
}