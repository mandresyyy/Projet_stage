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
<html lang="fr">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Infrastructure</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{asset('css/font.googleapis.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{asset('login/assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset('login/assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{asset('login/assets/layouts/layout/css/layout.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('login/assets/layouts/layout/css/themes/darkblue.min.css')}}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{asset('login/assets/layouts/layout/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" />


</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-gray">
    <!-- BEGIN HEADER -->
    <div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="index.html">
                    <img src="{{asset('login/logo_artec.png')}}" style="width: 86px;margin-top:0px;margin-left:40px" alt="Artec" class="logo-default"/> </a>
                <div class="menu-toggler sidebar-toggler" >
                    <span></span>
                </div>
            </div>
            <!-- END LOGO -->
            <!-- BEGIN RESPONSIVE MENU TOGGLER -->
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse" id="menu">
                <span></span>
            </a>
            <!-- END RESPONSIVE MENU TOGGLER -->
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">

                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <img alt="" class="" src="{{asset('user.png')}}" />
                            <span class="username username-hide-on-mobile"> {{$utilisateur->prenom}} </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{{route('admin.profile')}}">
                                    <i class="icon-user"></i> Mon compte </a>
                            </li>
                            <li class="divider"> </li>

                            <li>
                                <a href="{{route('user.log_out')}}">
                                    <i class="icon-key"></i> Se deconnecter </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                    <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                    <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                    <!-- <li class="dropdown dropdown-quick-sidebar-toggler">
                            <a href="javascript:;" class="dropdown-toggle">
                                <i class="icon-logout"></i>
                            </a>
                        </li> -->
                    <!-- END QUICK SIDEBAR TOGGLER -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->
    <div class="clearfix"> </div>
    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container" >
        <!-- BEGIN SIDEBAR -->
        <div class="page-sidebar-wrapper">
            <!-- BEGIN SIDEBAR -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <div class="page-sidebar navbar-collapse collapse">
                <!-- BEGIN SIDEBAR MENU -->
                <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 50px">
                    <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                    <li class="sidebar-toggler-wrapper hide">
                        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                        <div class="sidebar-toggler">
                            <span></span>
                        </div>
                        <!-- END SIDEBAR TOGGLER BUTTON -->
                    </li>
                    <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
                  
                    <li class="nav-item start @if($page=='stats') active @endif">
                        <a href="{{route('admin.dashboard')}}" class="nav-link ">
                            <i class="icon-bar-chart"></i>
                            <span class="title">Statistiques</span>
                        </a>
                    </li>
                  
                   
                   
                    <li class="nav-item start @if($page=='map') active @endif ">
                        <a href="{{route('admin.acceuil')}}" class="nav-link ">
                            <i class="fa fa-map-o"></i>
                            <span class="title">Carte infrastructure</span>
                        </a>
                    </li>
                    
                
                    
                    <li class="nav-item start @if($page=='releve') active open @endif">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fas fa-signal"></i>
                            <span class="title">Relevé signal</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{route('admin.releve_signal')}}" class="nav-link ">
                                    <i class="fa fa-map-o"></i>
                                    <span class="title">Carte relevé signal</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('admin.releve.liste')}}" class="nav-link ">

                                    <span class="title">Liste des relévés</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('new.releve_signal')}}" class="nav-link ">

                                    <span class="title">Importer relevé</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item start @if($page=='infra') active open @endif">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-satellite-dish"></i>
                            <span class="title">Infrastructure</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{route('infra.form')}}" class="nav-link ">

                                    <span class="title">Ajout infrastructure</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('infra.liste')}}" class="nav-link ">

                                    <span class="title">Les infrastructures</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('infra.upload_form')}}" class="nav-link ">

                                    <span class="title">Importer CSV</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item start @if($page=='util') active open @endif">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-user"></i>
                            <span class="title">Utilisateur</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{route('utilisateur.form')}}" class="nav-link ">
                                    <i class="fa fa-user-plus"></i>
                                    <span class="title">Nouveau utilisateur</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('utilisateur.liste')}}" class="nav-link ">
                                    <i class="fa fa-users"></i>
                                    <span class="title">Les utilisateurs</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item start @if($page=='op') active open @endif">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-phone"></i>
                            <span class="title">Operateur</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{route('operateur.form')}}" class="nav-link ">

                                    <span class="title">Ajout operateur</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('operateur.liste')}}" class="nav-link ">

                                    <span class="title">Les operateurs</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item start @if($page=='type') active open @endif">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-tag"></i>
                            <span class="title">Type d'infrastructure</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{route('type.form')}}" class="nav-link ">

                                    <span class="title">Nouveau type</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('type.liste')}}" class="nav-link ">

                                    <span class="title">Les types</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item start @if($page=='tech') active open @endif">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fas fa-wifi"></i>
                            <span class="title">Technologie</span>
                            <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="nav-item start ">
                                <a href="{{route('techno.form')}}" class="nav-link ">

                                    <span class="title">Nouvelle technologie</span>
                                </a>
                            </li>
                            <li class="nav-item start ">
                                <a href="{{route('techno.liste')}}" class="nav-link ">

                                    <span class="title">Les technologies</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item start @if($page=='logs') active @endif">
                        <a href="{{route('logs')}}" class="nav-link ">
                            <i class="fa fa-newspaper-o"></i>
                            <span class="title">Logs</span>
                        </a>
                    </li>





                </ul>
                <!-- END SIDEBAR MENU -->
                <!-- END SIDEBAR MENU -->
            </div>
            <!-- END SIDEBAR -->
        </div>
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div class="page-content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="page-content" >
                <!-- BEGIN PAGE HEADER-->
                <!-- BEGIN THEME PANEL -->

                <!-- END THEME PANEL -->
                <!-- BEGIN PAGE BAR -->
                <div class="page-bar">
                        <ul class="page-breadcrumb">
                            @yield('nav')
                        </ul>
                        <div class="page-toolbar">
                            
                        </div>
                    </div>



                <!-- END PAGE BAR -->
                <!-- BEGIN PAGE TITLE-->


                <!-- END PAGE HEADER-->
                <div class="row" style="margin-top:10px;">
                    @yield('contenu')
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->

        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> 
                <a href="http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes" title="Purchase Metronic just for 27$ and get lifetime updates for free" target="_blank">Purchase Metronic!</a>
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
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
       
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL SCRIPTS -->


        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('login/assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{asset('login/assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('login/assets/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('login/assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->


</body>

</html>