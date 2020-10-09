@extends('layout.app3')
@section('sub-menu')
<nav class="navbar navbar-expand-lg bg-warning text-dark mt-4">
<ul class="nav flex-column">
    <li class="nav-link"><a href="#" onclick="getOperationCharges()">Charge de production</a></li>
    <li class="nav-link"><a href="#" onclick="getOperationPertes()">Perte sur production</a></li>
    <li class="nav-link"><a href="#" onclick="getOperationProcess()">Les processus de production</a></li>
</ul>
</nav>
@endsection
@section('contents')
@endsection 
@section('scripts')
<script type="text/javascript">
function loading(){

}
$(document).ready(function(){
    loading();
});
</script>
@endsection