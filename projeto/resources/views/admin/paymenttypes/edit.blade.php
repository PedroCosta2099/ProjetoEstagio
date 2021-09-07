{{ Form::model($type, $formOptions) }}
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
                {{ Form::label('name', 'Método de Pagamento') }}
                {{ Form::text('name', null, ['class' => 'form-control uppercase', 'required']) }}
            </div>
        </div>
           <div class="col-sm-4">
                                {{ Form::label('active', 'Estado') }}
                                {{ Form::select('active', [0 => 'Bloqueado', 1 => 'Ativo'], $type->active ? 1 : 0, array('class' =>'form-control select2')) }}
</div>
        <div class="col-sm-4 col-sm-offset-3 col-lg-3">
            {{ Form::label('image', 'Símbolo', array('class' => 'form-label')) }}<br/>
            <div class="fileinput {{ $type->filepath ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar2.jpg') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if($type->filepath)
                        <img src="{{ asset($type->getCroppa(200, 200)) }}">
                    @endif
                </div>
                <div>
                    <span class="btn btn-default btn-block btn-sm btn-file">
                        <span class="fileinput-new">Procurar...</span>
                        <span class="fileinput-exists"><i class="fa fa-refresh"></i> Alterar</span>
                        <input type="file" name="image">
                    </span>
                    <a href="#" class="btn btn-danger btn-block btn-sm fileinput-exists" data-dismiss="fileinput">
                        <i class="fa fa-close"></i> Remover
                    </a>
                </div>
            </div>
        </div>
     
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    {{ Form::hidden('delete_photo') }}
    <button class="btn btn-primary btn-submit">Gravar</button>
</div>
{{ Form::close() }}

<script>
    $('.select2').select2(Init.select2());
    $('input').iCheck(Init.iCheck());
    $('[data-toggle="tooltip"]').tooltip();
</script>
