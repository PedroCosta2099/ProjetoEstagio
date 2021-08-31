@section('title')
    Painel de Controlo
@stop

@section('content-header')
    Painel de Controlo
@stop

@section('content')
    <div class="row row-10">
        <div class="col-sm-12">
            <h1 class="text-center">
            <div class="container justify-content-center">

<div class="row m-t-40">
    <div class="col-sm-4">
        <h3 class="text-center">
            Vendas nos últimos 30 dias
            <hr style="height:1px;background-color:gray">
            <canvas id="estado_chart" ></canvas>
        </h3>
    </div>
  
</div>
</div>
            </h1>
        </div>
    </div>
@stop

@section('scripts')
<script>
        var ctx = document.getElementById('estado_chart').getContext('2d');
        var estado_chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!!json_encode($countOrdersSeller_labels)!!},
                datasets: [{
                    label: 'Estado dos tickets',
                    backgroundColor: {!! json_encode($countOrdersSeller_colours)!!} ,
                    data:  {!! json_encode($countOrdersSeller_data)!!} ,
                    borderWidth: 1,
                    hoverOffset: 4

                }]
            }
        });
    </script>
@stop