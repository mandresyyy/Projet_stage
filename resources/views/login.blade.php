<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.6
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>Metronic | User Login 1</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{asset('login/assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{asset('login/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset('login/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{asset('login/assets/pages/css/login.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
    <script src="{{asset('Utilitaire/sweetalert.min.js')}}"></script>
    <!-- BEGIN THEME LAYOUT STYLES -->
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->

<body class=" login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="index.html">
            <img src="{{asset('login/logo_artec_complet.png')}}" style="width: 200px;" alt="" /> </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <div class="content">
        <!-- BEGIN LOGIN FORM -->
        <form class="login-form" action="{{route('se_connecter')}}" method="post">
            @csrf
            <h3 class="form-title font-green">Login</h3>
            <div class="alert alert-danger display-hide">
                <button class="close" data-close="alert"></button>
                <span>Veuillez entrer votre email et mot de passe </span>
            </div>
            @if($errors->has('erreur'))
            <div class="alert alert-danger" style="color: red;">
                {{ $errors->first('erreur') }}
            </div>
            @endif
            <div class="form-group">
                <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                <label class="control-label visible-ie8 visible-ie9">Email</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="Mail" name="email" />
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">Mot de passe</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="Mot de passe" name="motdepasse" />
            </div>
            <div class="form-actions">
                <button type="submit" class="btn green uppercase">Se connecter</button>
                <a href="javascript:;" id="forget-password" class="forget-password">Mot de passe oublié ?</a>
            </div>


        </form>
        <!-- END LOGIN FORM -->
        <!-- BEGIN FORGOT PASSWORD FORM -->
        <style>
            #load_spin{
                display: none;
            }
        </style>
        <div class="forget-form">
            <h3 class="form-title font-green">Mot de passe oublié</h3>

            <div id="send_mail">
                <div class="form-group">
                    <p class="hint"> Veuillez entrer votre adresse mail. </p>
                    <input class="form-control placeholder-no-fix" type="email" id="mail_input" placeholder="Email" name="email" />
                </div>

                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn green btn-outline">Retour</i></button>
                    <button id="send_email" class="btn btn-success uppercase pull-right " onclick="show_form_code()">Envoyer <i class="fa fa-spinner fa-spin" id="load_spin"></i> </button>
                </div>
            </div>
            <div class="insert_code" style="display:none" id="insert_code">

                <div class="form-group">
                    <p class="hint"> Veuillez entrer le code de confirmation: </p>
                    <input class="form-control placeholder-no-fix" type="text" placeholder="" id="code" name="code" />
                </div>
                <div class="form-actions">
                    <button type="button" id="retour" class="btn green btn-outline" onclick="to_mail_form()">Retour</i></button>
                    <button id="register-submit-btn" class="btn btn-success uppercase pull-right" onclick="verif_code()">Verifier</button>
                </div>
                <a onclick="show_form_code()">Réenvoyer le mail</a>
            </div>

        </div>
        <script>
            var send_mail = document.getElementById("send_mail");
            var code_form = document.getElementById('insert_code');
            var retour = document.getElementById('retour');
            var mail = document.getElementById('mail_input');
            var code = document.getElementById('code');
            
            function show_form_code() {
                var spin=document.getElementById('load_spin');
                spin.style.display="block";
                fetch('/send/recuperation/' + mail.value, {
                        method: 'get',
                    })
                    .then(function(response) {
                        if (response.ok) {

                            return response.json();
                        } else {
                            spin.style.display='none';
                            throw new Error('Veuillez entrer votre mail');
                        }
                    })
                    .then(function(data) {
                        spin.style.display='none';
                        if (data.Check) {
                            Swal.fire({
                                title: 'Message',
                                text: data.Message, 
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });

                            send_mail.style.display = "none";
                            code_form.style.display = 'block';
                        } else {
                            Swal.fire({
                                title: 'Message',
                                text: data.Message, 
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });

                        }
                    })
                    .catch(function(error) {
                        // Gérer les erreurs
                        console.error(error);
                        Swal.fire({
                                title: 'Erreur',
                                text: error,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                    });

            }

            function to_mail_form() {
                send_mail.style.display = "block";
                code_form.style.display = 'none';
            }

            function verif_code() {
                fetch('/verif/code/' + code.value, {
                        method: 'get',
                    })
                    .then(function(response) {
                        if (response.ok) {

                            return response.json();
                        } else {
                            // Gérer les erreurs de la requête
                            throw new Error('Erreur lors de la requête AJAX');
                        }
                    })
                    .then(function(data) {
                        if (data.Check) {
                            window.location.href = '/reset';
                        } else {
                            Swal.fire({
                                title: 'Message',
                                text: data.Message, 
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(function(error) {
                        // Gérer les erreurs
                        console.error(error);
                    });

            }
        </script>
        <!-- END recup FORM -->
    </div>
    <div class="copyright"> 2014 © Metronic. Admin Dashboard Template. </div>
    
    <!-- BEGIN CORE PLUGINS -->
    <script src="{{asset('login/assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{asset('login/assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('login/assets/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{asset('login/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{asset('login/assets/pages/scripts/login.min.js')}}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>