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
