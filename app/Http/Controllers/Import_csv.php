<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  Illuminate\Support\Facades\DB;
use App\Models\Commune;
class Import_csv extends Controller // class importation csv commune
{
    public function importCSV(Request $request)
{
    $filePath = "commune.csv";
    $delimiter = ','; // Le séparateur utilisé dans le fichier CSV

    $premier = true;
    
    if (($handle = fopen($filePath, 'r')) !== false) {
        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            if ($premier) {
                $premier = false;
            } else {
                $doublons=Commune::where('code_c','=',$data[4])->get();
               
                if(count($doublons)==0){
                   
                    DB::table('commune')->insert([
                        'commune'=>$data[5],
                        'code_c'=>$data[4],
                        'district'=>$data[3],
                        'code_d'=>$data[2],
                        'region' => $data[1],
                        'code_r' => $data[0]
                    ]);
                }
                else{
                   
                    continue;
                }
              
            }
           
        }
        fclose($handle); // Close the file after reading
    }
}

}
