<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="../../assets/img/favicon.png" />    
    <title>Sistema de Citas en Linea | Vitacloud</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Vitacloud">
    <meta name="author" content="Vitacloud">

    <link rel="stylesheet" href="../../assets/css/fuentes.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />    

    <link rel="stylesheet" href="../../bower_components/animate.css/animate.css" />

    <link rel="stylesheet" href="../../bower_components/skylo/vendor/styles/skylo.css" />
    <link rel="stylesheet" href="../../bower_components/themify-icons/themify-icons.css" />

    <!--[if lt IE 10]>
        {{ "js/media.match.min.js" | asset_url | script_tag }}
        {{ "js/respond.min.js" | asset_url | script_tag }}
        {{ "js/placeholder.min.js" | asset_url | script_tag }}
    <![endif]-->
    <link rel="stylesheet" href="../../assets/css/custom.css">


    <!-- prochtml:remove:dist -->
    <!--<script type="text/javascript"> less = { env: 'production' }; </script>
    <script type="text/javascript" src="assets/plugins/misc/less.js"></script>-->
    <!-- /prochtml -->
    <style type="text/css">
        .tab-heading h2{
            color:#ce1d19;
            font-size: 30px; 
            font-weight: bold;         
        }

        /* Large Devices, Wide Screens */
        @media only screen and (max-width : 1200px) {

        }

        /* Medium Devices, Desktops */
        @media only screen and (max-width : 992px) {
            .tab-heading h2 {
                font-size: 25px;
            }
        }

        /* Small Devices, Tablets */
        @media only screen and (max-width : 768px) {
            .container-fluid {
                padding-top: 25%;
            }
        }

        /* Extra Small Devices, Phones */ 
        @media only screen and (max-width : 480px) {
            #topnav .navbar-brand {
                width: 160px !important;
            }

            .page-header .texto {
                font-size: 16px;
                letter-spacing: 0;
                position: relative;
                top: 10px;
            }

            .tab-heading h2 {
                font-size: 20px;
            }

            .container-fluid {
                padding-top: 50%;
            }
        }

        /* Custom, iPhone Retina */ 
        @media only screen and (max-width : 320px) {
            
        }
    </style>
    
</head>

<body>
    <header id="topnav" class="navbar" role="banner" ng-cloak >
        <section class="page-header logo-left secondary-page" >
            <div class="container col-md-12 ">
                <div class="clearfix row">
                    <div class="col-md-4 col-sm-4 col-xs-4">
                        <div class="logo-area">
                            <a href="#/" class="navbar-brand" >Vitacloud</a>    
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-8" >
                        <div class="logo-central">
                            <a href="#/" class="navbar-brand-central" >Vitacloud</a>
                        </div>
                        <div class="logo-whatsapp">
                            <a href="#/" class="navbar-brand-whatsapp" >Vitacloud</a>
                        </div>
                    </div>                  
                </div>
            </div>
        </section>
        <div class="page-separador"></div>    
    </header>
    <style>
        #topnav .navbar-brand  {
          background: url("../../assets/img/dinamic/empresa/logo-250x60.png") no-repeat left 0 center;
          background-size: contain;
          width: 250px;
          height: 60px;
        }

        #topnav .navbar-brand-central,
        #topnav .navbar-brand-whatsapp{
            font: 0/0 a !important;
            color: transparent !important;
            text-shadow: none !important;
            background-color: transparent !important;
            border: 0 !important;
            background-size: contain !important;
            width: 200px;
            height: 60px;
            display: block;
            float: left;            
        } 

        #topnav .logo-central{
            /*width: 99%;
            margin-left: auto;
            margin-right: auto;*/
            float: right;
        }           

        #topnav .logo-whatsapp{
            float: right;
        }       

        #topnav .navbar-brand-central {
          background: url("../../assets/img/dinamic/empresa/central-250x60.png") no-repeat left 0 center;
        }

        #topnav .navbar-brand-whatsapp {
          background: url("../../assets/img/dinamic/empresa/whatsapp-250x60.png") no-repeat left 0 center;          
        }


        .static-content-wrapper {
            padding: 0;
            margin-bottom: 0;
            background: url("../../assets/img/dinamic/empresa/banner-familia.jpg") no-repeat left top;
            background-size: cover;
        }
        .static-content-wrapper-top {
            background: url("../../assets/img/dinamic/empresa/banner-medico.png") no-repeat left 0;
            background-size: cover;
            width: 100%;
            height: 80%;
            position: absolute;
            z-index: 0;
            bottom: 0;
            left: 0;
            animation: animatedBackground 3s;
        }

        @keyframes animatedBackground {
            from { background-position: 0 150%; }
            to { background-position: 0 0; }
        }
    </style>

    <div id="wrapper">
        <div id="layout-static">
            <div class="static-content-wrapper"></div>
            <div class="static-content-wrapper-top active">
                <div class="static-content">
                    <div class="container-fluid "  style="visibility: visible; opacity: 1; display: block; transform: translateY(0px);">
                        <div class="col-md-6 col-sm-6 col-xs-12" style="padding-top: 5%;float: right;">                        
                            <div class="tab-heading">                                
                                <h2 style=""><i class="ti ti-check"></i>Ha sido activada tu cuenta satisfactoriamente</h2> 
                                <p class="descripcion" style="color: #262e32;font-size: 18px;">Comienza a disfrutar los beneficios de ser un paciente de Vitacloud</p>
                            </div> 
                            <div class="col-sm-12 col-xs-12 col-md-12" style="">                                                                                           
                                <a href="<?php echo base_url(); ?>" class="btn btn-page" style="width: 200px; -webkit-box-shadow: 2px 3px 5px 0px rgba(0,0,0,0.36);box-shadow: 2px 3px 5px 0px rgba(0,0,0,0.36);">INICIAR SESION</a>
                            </div>                           
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>