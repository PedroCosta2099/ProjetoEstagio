{{ Form::model($payment, $formOptions) }}
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
                {{ Form::label('payment_type_id', 'Método de Pagamento') }}
                {{ Form::select('payment_type_id', ['' => 'NENHUM'] + $payment_types, null, ['class' => 'form-control select2']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('entity', 'Entidade') }}
                {{ Form::number('entity',null, ['class' => 'form-control uppercase']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('reference', 'Referência') }}
                {{ Form::number('reference',null, ['class' => 'form-control uppercase']) }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group is-required">
                {{ Form::label('amount', 'Montante') }}
                {{ Form::number('amount',null, ['class' => 'form-control uppercase'],'required') }}
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                {{ Form::label('phone_number', 'Número de Telemóvel') }}
                {{ Form::number('phone_number',null, ['class' => 'form-control uppercase']) }}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group is-required">
                {{ Form::label('payment_status_id', 'Estado do Pagamento') }}
                {{ Form::select('payment_status_id', ['' => 'NENHUM'] + $payment_status, null, ['class' => 'form-control select2']) }}
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
    $('input').iCheck(Init.iCheck());
    $('[data-toggle="tooltip"]').tooltip();
</script>
