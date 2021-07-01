<div class="box no-border">
    <div class="box-body">
        {{ Form::model($seller, $formOptions) }}
        <div class="col-sm-8 col-lg-9">
            <div class="row row-5">
                <div class="col-sm-8">
                    <div class="form-group is-required">
                        {{ Form::label('name', 'Nome a apresentar no sistema')}}
                        {{ Form::text('name', null, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group is-required">
                        {{ Form::label('nif', 'NIF')}}
                        {{ Form::text('nif', null, array('class' =>'form-control','required' => true)) }}
                    </div>
                </div> 
                <div class="form-group is-required col-sm-8">
                {{ Form::label('email', 'E-mail')}}
                {{ Form::email('email', null, array('class' =>'form-control', 'required' => true)) }}
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        {{ Form::label('phone', 'Telemóvel')}}
                        {{ Form::text('phone', null, array('class' =>'form-control')) }}
                    </div>
                </div>
            <div class="form-group is-required col-sm-10">
                {{ Form::label('address', 'Morada')}}
                {{ Form::text('address', null, array('class' =>'form-control', 'required' => true)) }}
            </div>
            <div class="form-group is-required col-sm-5">
                {{ Form::label('postal_code', 'Código Postal')}}
                {{ Form::text('postal_code', null, array('class' =>'form-control', 'required' => true,'placeholder'=>'0000-000', 'pattern'=>'^\d{4}-\d{3}?$')) }}
            </div> <p>
            <div class="form-group is-required col-sm-5">
                {{ Form::label('city', 'Localidade')}}
                {{ Form::text('city', null, array('class' =>'form-control', 'required' => true)) }}
            </div>  
        </div>
        <h4 class="m-t-20 bold text-blue">Opções da conta</h4>
            <div class="row row-5">
                <div class="col-sm-6">
                    <table class="table table-condensed">
                        <tr>
                            <td>
                                <p class="form-control-static" style="border: none; padding-left: 0;">
                                    Empresa Ativa <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Se a empresa estiver a trabalhar com a plataforma"></i>
                                </p>
                            </td>
                            <td style="width: 120px">
                                {{ Form::select('active', [1 => 'Sim', 0 => 'Não'],null, array('class' =>'form-control select2')) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
    </div>
    
        <div class="col-sm-4 col-lg-3">
            {{ Form::label('image', 'Fotografia:', array('class' => 'form-label')) }}<br/>
            <div class="fileinput {{ $seller->filepath ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar.png') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if($seller->filepath)
                        <img src="{{ asset($seller->getCroppa(200, 200)) }}">
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
        <div class="col-sm-12">
            {{ Form::hidden('delete_photo') }}
            <button class="btn btn-primary">Gravar</button>
        </div>
        {{ Form::close() }}
    </div>
</div>