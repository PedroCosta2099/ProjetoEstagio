{{ Form::model($address, $formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">Fechar</span>
    </button>
    <h4 class="modal-title">{{ $action }}</h4>
</div>
<div class="modal-body">
    <div class="row row-5">
        <div class="col-sm-12">
            <div class="form-group is-required">
                {{ Form::label('address', 'Morada') }}
                {{ Form::text('address', null, ['class' => 'form-control uppercase', 'required']) }}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group is-required">
                {{ Form::label('postal_code', 'Código Postal') }}
                {{ Form::text('postal_code', null, ['class' => 'form-control uppercase', 'required']) }}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group is-required">
                {{ Form::label('city', 'Localidade') }}
                {{ Form::text('city', null, ['class' => 'form-control uppercase', 'required']) }}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group is-required">
                {{ Form::label('customer', 'Cliente (NIF - Nome)') }}
                {{ Form::select('customer', $customers,null, ['class' => 'form-control select2', 'required']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_address', 'Morada de Faturação') }}&nbsp
                {{ Form::checkbox('billing_address',$address->billing_address,null, ['class' => 'form-control','id' => 'billing_address'])}}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('shipment_address', 'Morada de Envio') }}&nbsp
                {{ Form::checkbox('shipment_address',$address->shipment_address,null, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    <button class="btn btn-primary btn-submit">Gravar</button>
</div>

{{ Form::close() }}

<script>
    $('.select2').select2(Init.select2());
    $(document).ready(function () {
        $(".select2").select2({
            language: 'pt'
        });
    });

    $('input').iCheck(Init.iCheck());
    $('[data-toggle="tooltip"]').tooltip();
   
</script>
<script type="text/javascript">
</script>
