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
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('css/css') }}" rel="stylesheet">
    

</head>
<body>
    <div id="app">
            <nav class="navbar navbar-expand-lg bg-warning mt-4">
                    <div class="container">
                        <ul class="navbar-nav ml-lg-auto">
                            
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                              <li class="nav-item px-2">
                                <a class="nav-link btn btn-success" onclick="save()" href="#">
                                  <img width="40" src="{{ asset('icon/icons8-coche-512.png') }}"><b> Enregister</b></a>
                              </li>
                              <li class="nav-item px-2">
                                <a class="nav-link btn btn-danger px-2" href="#">
                                  <img width="40" src="{{ asset('icon/icons8-effacer-128.png') }}"><b> Annuler</b></a>
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
                              <img width="35" src="{{ asset('icon/icons8-carte-de-garantie-96.png') }}"><b> Gamme des produits</b></a>
                            </div>
                          </li>
                        </ul>
                        </ul>
                      </div>
                    </div>
                  </nav>


        <main class="container py-4">
        <div class="row">
          <div class="col-md-3">
            <ul class="nav flex-column">
            <li class="nav-item mb-3">
              <a class="nav-link btn btn-secondary" href="#" onclick="parametreHome()"><img width="50" src="{{ asset('icon/icons8-menu-512.png') }}"><b> Home</b></a></li>
             @yield('sub-menu')
           </ul>
          </div>
          <div class="col-md-9">
            @yield('contents')
          </div>
          </div>
        </main>
    </div>
    @yield('scripts')
    <script type="text/javascript">
        function nouvelleRef(){
            window.location.href = "{{ route('app.nouveaulot')}}";          
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
        function parametreHome(){
          window.location.href = "{{ route('home')}}";
        }                                                                                     
    </script>
</body>
</html>
