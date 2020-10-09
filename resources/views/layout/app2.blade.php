<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TX-Lab Software') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/axios.min.js') }}"></script>
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/qrcode.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/a58d219296.js"></script>

    <link href="{{ asset('css/css') }}" rel="stylesheet">
<!--    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <!-- Nucleo Icons -->
  <!--  <link href="{{ asset('css/nucleo-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
    <!--<link href="{{ asset('css/nucleo-svg.css') }}" rel="stylesheet">
    <!-- CSS Files -->
  <!--  <link href="{{ asset('css/argon-design-system.min.css') }}" rel="stylesheet">
-->
</head>
<body>
    <div id="app">
            <nav class="navbar navbar-expand-lg bg-warning text-dark mt-4">
                    <div class="container">
                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item">
                            <a class="nav-link" onclick="goHome()" href="#"><img width="50" src="{{ asset('icon/icons8-dashboard-96.png') }}"></a>
                            </li>
                            
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn" href="javascript:;" id="navbar-primary_dropdown_0" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <img width="50" src="{{ asset('icon/icons8-graphique-en-zone-96.png') }}"><b> Rapports</b></a>
                            <div class="dropdown-menu dropdown-menu-right btn" aria-labelledby="navbar-primary_dropdown_0">
                              <a class="dropdown-item" href="javascript:;" onclick="reportSite()">
                              <img width="35" src="{{ asset('icon/icons8-usine-96.png') }}"><b> Par site</b></a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;">
                              <img width="35" src="{{ asset('icon/icons8-qr-code-96.png') }}"><b> Par reference</b></a>
                               <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;" onclick="IGUQualiteReport()">
                              <img width="35" src="{{ asset('icon/icons8-tube-à-essai-96.png') }}"><b> Qualite</b></a>
                            </div>
                          </li>

                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn" href="javascript:;" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img width="50" src="{{ asset('icon/icons8-qr-code-96.png') }}"><b>Référence</b></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">
                            <a class="dropdown-item" href="javascript:;" onclick="nouvelleRef()">
                            <img width="35" src="{{ asset('icon/icons8-plus-128.png') }}"><b> Nouvelle</b></a>
                             <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="Ref()">
                            <img width="35" src="{{ asset('icon/icons8-menu-512.png') }}"><b> Listes</b></a>
                          
                          </li>
                        </ul>
                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn" href="javascript:;" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img width="50" src="{{ asset('icon/icons8-truck-96.png') }}"><b>Stocks</b></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">
                            <a class="dropdown-item" href="javascript:;" onclick="nouveauArrivage()">
                              <img width="35" src="{{ asset('icon/icons8-plus-128.png') }}"><b> Nouvelle</b></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="Livraison()">
                            <img width="35" src="{{ asset('icon/icons8-record-96.png') }}"><b> Les enregistrements</b></a>
                            
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;" onclick="ViewStocks()">
                                <img width="35" src="{{ asset('icon/icons8-paramètres-96.png') }}"><b> Situation</b></a>
                                <a class="dropdown-item" href="javascript:;" onclick="Inventaire()">
                                <img width="35" src="{{ asset('icon/icons8-paramètres-96.png') }}"><b> Etiquette & Inventaire</b></a>
                            </div>
                          </li>
                        </ul>

                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn" href="javascript:;" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <img width="50" src="{{ asset('icon/icons8-tube-à-essai-96.png') }}"><b>Contrôle qualite</b></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">
                            <a class="dropdown-item" href="javascript:;" onclick="launchSaisiResult()">
                            <img width="35" src="{{ asset('icon/icons8-plus-128.png') }}"><b> Ajouter</b></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;">
                            <img width="35" src="{{ asset('icon/icons8-menu-512.png') }}"><b> Listes</b></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;">
                            <img width="35" src="{{ asset('icon/icons8-info-512.png') }}"><b> Historiques</b></a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;">
                              <img width="35" src="{{ asset('icon/icons8-paramètres-96.png') }}"><b> Modifier des valeurs</b></a>
                            </div>
                          </li>
                        </ul>

                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn" href="javascript:;" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                             <img width="50" src="{{ asset('icon/icons8-fournisseur-96.png') }}"><b>Les fournisseurs</b></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">
                            <a class="dropdown-item" href="javascript:;" onclick="parametreGrpFournisseur()">
                            <img width="35" src="{{ asset('icon/icons8-group-96.png') }}"><b> Groupe de fournissseur</b></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:;" onclick="parametreFournisseur()">
                            <img width="35" src="{{ asset('icon/icons8-fournisseur-96.png') }}"><b> Fournisseur</b></a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;">
                              <img width="35" src="{{ asset('icon/icons8-paramètres-96.png') }}"><b> Modifier</b></a>
                            </div>
                          </li>
                        </ul>

                        <ul class="navbar-nav ml-lg-auto">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn" href="javascript:;" id="navbar-primary_dropdown_1" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <img width="50" src="{{ asset('icon/icons8-paramètres-512.png') }}"><b> Configurations</b></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbar-primary_dropdown_1">
                              <a class="dropdown-item" href="javascript:;" onclick="parametreCategorie()">
                              <img width="35" src="{{ asset('icon/icons8-ungroup-objects-96.png') }}"><b> Categorie</b></a>
                              <a class="dropdown-item" href="javascript:;" onclick="parametreProduit()">
                              <img width="35" src="{{ asset('icon/icons8-produit-96.png') }}"><b> Produit</b></a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;" onclick="parametreGrpLocal()">
                              <img width="35" src="{{ asset('icon/icons8-usine-96.png') }}"><b> Sites</b></a>
                              <a class="dropdown-item" href="javascript:;" onclick="parametreLocal()">
                              <img width="35" src="{{ asset('icon/icons8-accueil-512.png') }}"><b> Locaux</b></a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="javascript:;" onclick="parametreAnalyse()">
                              <img width="35" src="{{ asset('icon/icons8-tube-à-essai-96.png') }}"><b> Paramètres et mesure</b></a>
                              <a class="dropdown-item" href="javascript:;" onclick="parametreDescription()">
                              <img width="35" src="{{ asset('icon/icons8-tableau-96.png') }}"><b> Descriptions et triages</b></a>
                            <a class="dropdown-item" href="javascript:;" onclick="parametreQualite()">
                              <img width="35" src="{{ asset('icon/icons8-carte-de-garantie-96.png') }}"><b> Gamme des produits</b>
                            </a>
                            <a class="dropdown-item" href="javascript:;" onclick="getOperationGUI()">
                              <img width="35" src="{{ asset('icon/icons8-carte-de-garantie-96.png') }}"><b>Production</b>
                            </a>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </nav>           
        <main class="py-4">
            @yield('contents')
        </main>
        <nav class="navbar navbar-expand-lg navbar-dark bg-warning mt-4">
                    <div class="row container">
                      <div class="col-sm-5"></div>
                      <div class="col-sm-3"><a class="nav-link btn btn-success" href="#" onclick="save()">Enregister</a></div>
                      <div class="col-sm-1"></div>
                      <div class="col-sm-3"><a class="nav-link btn btn-light" href="javascript:;">Annuler</a></div>
                            
                             
                    </div>
                  </nav>

    </div>
    @yield('scripts')
    <script type="text/javascript">
       function goHome(){
            window.location.href = "{{ route('home')}}";          
        }

        function nouvelleRef(){
            window.location.href = "{{ route('app.nouveaulot')}}";          
        }
        function Ref(){
            window.location.href = "{{ route('app.lot')}}";          
        }
        function nouveauArrivage(){
            window.location.href = "{{ route('app.nouveauArrivage')}}";
        }
        function Livraison(){
            window.location.href = "{{ route('app.livraison')}}";
        }
        function parametreCategorie(){
            window.location.href = "{{ route('categorie.index')}}";
        }
        function parametreNouveauCategorie(){
            window.location.href = "{{ route('categorie.create')}}";
        }
        function parametreProduit(){
          window.location.href = "{{ route('produit.index')}}";
        }
        function parametreNouveauProduit(){
          window.location.href = "{{ route('produit.create')}}";
        }
        function parametreGrpFournisseur(){
          window.location.href = "{{ route('groupeFrs.index')}}";
        }
        function parametreNouveauGrpFournisseur(){
          window.location.href = "{{ route('groupeFrs.create')}}";
        }
        function parametreFournisseur(){
          window.location.href = "{{ route('frs.index')}}";
        }
        function parametreNouveauFournisseur(){
          window.location.href = "{{ route('frs.create')}}";
        }
        function parametreGrpLocal(){
          window.location.href = "{{ route('groupeLocal.index')}}";
        }
        function parametreNouveauGrpLocal(){
          window.location.href = "{{ route('groupeLocal.create')}}";
        }
        function parametreLocal(){
          window.location.href = "{{ route('local.index')}}";
        }
        function parametreNouveauLocal(){
          window.location.href = "{{ route('local.create')}}";
        }
        function parametreAnalyse(){
          window.location.href = "{{ route('analyse.index')}}";
        }
         function parametreDetailsAnalyse(){
          window.location.href = "{{ route('details.index')}}";
        }
        function parametresCreateNewDetailsAnalyse(){
          window.location.href = "{{ route('details.create')}}";
        }
        function parametreNouveauAnalyse(){
          window.location.href = "{{ route('analyse.create')}}";
        }
        function parametreDescription(){
          window.location.href = "{{ route('description.index')}}";
        }
        function parametreNouveauDescription(){
          window.location.href = "{{ route('description.create')}}";
        }
        function parametreQualite(){
          window.location.href = "{{ route('qualite.index')}}";
        }
        function parametreNouveauQualite(){
          window.location.href = "{{ route('qualite.create')}}";
        }
        function reportSite(){
          window.location.href = "{{ route('report.site')}}";
        } 
        function reportPerso(){
          window.location.href = "{{ route('report.getPerso')}}";
        } 
        function launchSaisiResult(){
          window.location.href = "{{route('app.saisieResultats2')}}";
        }
         function getOperationGUI(){
         window.location.href = "{{route('app.getOperationGUI')}}";
        }
        function getOperationCharges(){
         window.location.href = "{{route('report.charges')}}";
        }
        function getOperationPertes(){
         window.location.href = "{{route('report.pertes')}}";
        }
        function getOperationProcess(){
         window.location.href = "{{route('report.displayProcess')}}";
        }
        function createNewCharge(){
          window.location.href = "{{route('app.createCharges')}}";
        }
        function createNewPerte(){
          window.location.href = "{{route('app.createPerte')}}";
        }
        function createNewProcess(){
          window.location.href = "{{route('app.createProcess')}}";
        }
        function ViewStocks(){
          window.location.href = "{{route('app.getSituationStocksSite')}}";
        }
        function Inventaire(){
          window.location.href = "{{route('app.IGUInventaire')}}"; 
        }
        function IGUQualiteReport(){
          window.location.href = "{{route('app.IGUQualiteReport')}}"; 
        }
        function random_rgba() {
          var o = Math.round, r = Math.random, s = 255;
          return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
        }
        function makeTheQr(id,text){
          new QRCode(document.getElementById(id),text);
        }                                                        
    </script>
</body>
</html>
