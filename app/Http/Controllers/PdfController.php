<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class PdfController extends Controller
{
    public function generatePdf(Request $request)
    {
        
       $htm=$request->session()->get('cle');
    //    dd($htm);
       $request->session()->forget('cle');
       $css_path=public_path().'/css/pdf.css';
       $logo_path=public_path().'/login/logo_artec_complet.png';
       $style='<link rel="stylesheet" href="'.$css_path.'"><body><center><img src="'.$logo_path.'" class="logo"></center><br/>';
       $html=$style.$htm.'</body>';
        $pdf = PDF::loadHTML("$html");

        // Téléchargez le PDF
        return $pdf->download('Statistique.pdf');
     
    }
    public function GenerateTable(Request $request)
    {
        $request->session()->put('cle', $request->input('table'));   
        return response()->json(['pdfUrl' => '/pdf']);
    }
}
