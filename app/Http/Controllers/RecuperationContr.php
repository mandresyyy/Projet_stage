<?php

namespace App\Http\Controllers;

use App\Mail\Sender_Mail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Recuperation;
use App\Models\Utilisateur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Logs;
use App\Models\Type_action;


class RecuperationContr extends Controller
{
    public function Send($email,Request $request){
        $util=new Utilisateur();
        $util->email=$email;
        
        $check=$util->verifierMailrecuperation();
       
        if($check!=false){
            $recup=new Recuperation();
            $code=$recup->generate();
            $request->session()->put('recup_util',$check);

            Recuperation::create([
                'id_utilisateur' => $check->id,
                'code' => $code,
            ]);
            // dd($code);

            $sujet="Code de recuperation";
            $view="Mail.recuperation";

            $data = ['code' => $code];
            $mail=new Sender_Mail($sujet,$view,15);
            $mail->with($data);

            Mail::to($email)->send($mail);
            $data = array(
                "Check" => true,
                "Message" => "Code de recuperation envoyé",
            );
        }

        else{
            $data = array(
                "Check" => false,
                "Message" => "Adresse mail incorrect",
            );
        }
        return json_encode($data);
    }

    public function verifCode(Request $request,$code){
        $util=$request->session()->get('recup_util');
        $check=Recuperation::where('id_utilisateur','=',$util->id)->where('etat','=','0')
                            ->orderBy('id','DESC')->first();

        if($check->code!=$code){
            $retour = array(
                "Check" => false,
                "Message" => "Code incorrect",
            );
        }
        else{
            $now = Carbon::now();
            $now=$now->addHours(3);
            $now= Carbon::parse($now);
            $date_envoie=Carbon::parse($check->date_envoie);
            $diff=$date_envoie->diffInMinutes($now);
            // dd($diff);
            if($diff>15){ // duree
                $retour = array(
                    "Check" => false,
                    "Message" => "Code déjà éxpiré" .$diff .' '.$check->date_envoie.' '.$now,
                );
            }
            else{
                $retour = array(
                    "Check" => true,
                    "Message" => "OK",
                );
                $check->etat=1;
                $check->save();
            }
          
        }
        return $retour;
    }

    public function reset_password(Request $request){
        
            $request->validate([
                'password' => 'required|min:4',
                'rpassword' => 'required|min:4',
            ]);
            if($request->session()->get('recup_util')!=null){
                $util=$request->session()->get('recup_util');
                if($request->input('password')!=$request->input('rpassword')){
                    return back()->withErrors(['Erreur'=>'Mot de passe non identique']);
                }
    
                else{
                    $util->motdepasse=bcrypt($request->input('rpassword'));
                    $util->save();
                    $action = new Logs();
                    $idtypeaction = Type_action::where('action', '=', 'modification')->pluck('id')->first();
                        $action->id_type_action = $idtypeaction;
                        $action->detail = 'Reinitialisation mot de passe : '.$util->email.' '.$util->matricule;
                        $action->id_utilisateur=$util->id;
                        $action->newLogs();
                    $request->session()->forget('recup_util');
                    return back()->with(['success' => 'Mot de passe changé avec succés']);
                }
            }
            else{
                return view('login');
            }
           
           
           
       
    }
}
