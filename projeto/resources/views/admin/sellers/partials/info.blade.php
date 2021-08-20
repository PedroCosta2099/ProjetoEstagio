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
            <div class="form-group is-required col-sm-5">
                {{ Form::label('minimum_delivery_time', 'Tempo mínimo de entrega (em minutos)')}}
                {{ Form::number('minimum_delivery_time', null, array('class' =>'form-control', 'required' => true,'step'=>'5','min'=>'5','max'=>'60')) }}
            </div>
            <div class="form-group is-required col-sm-5">
                {{ Form::label('maximum_delivery_time', 'Tempo máximo de entrega (em minutos)')}}
                {{ Form::number('maximum_delivery_time', null, array('class' =>'form-control', 'required' => true,'step'=>'5','min'=>'5','max'=>'60')) }}
            </div>
            <div class="form-group is-required col-sm-5">
                {{ Form::label('delivery_fee', 'Taxa de Entrega (em €)')}}
                {{ Form::number('delivery_fee',number_format($seller->delivery_fee,2), array('class' =>'form-control', 'required' => true,'step'=>'0.01','min'=>'0.01')) }}
            </div>
              
        </div>
        @if(Auth::user()->isAdmin())
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
            @endif
    </div>
    
        <div class="col-sm-4 col-lg-3">
            {{ Form::label('thumbnail_image', 'Thumbnail', array('class' => 'form-label')) }}<br/>
            <div class="fileinput {{ $seller->thumbnail_filepath ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar2.jpg') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if($seller->thumbnail_filepath)
                        <img src="{{ asset($seller->getCroppaThumbnail(1440,450)) }}">
                    @endif
                </div>
                <div>
                    <span class="btn btn-default btn-block btn-sm btn-file">
                        <span class="fileinput-new">Procurar...</span>
                        <span class="fileinput-exists"><i class="fa fa-refresh"></i> Alterar</span>
                        <input type="file" name="thumbnail_image">
                    </span>
                    <a href="#" class="btn btn-danger btn-block btn-sm fileinput-exists" data-dismiss="fileinput" data-name="thumbnail">
                        <i class="fa fa-close"></i> Remover
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-3">
            {{ Form::label('banner_image', 'Banner', array('class' => 'form-label')) }}<br/>
            <div class="fileinput {{ $seller->banner_filepath ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar2.jpg') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if($seller->banner_filepath)
                        <img src="{{ asset($seller->getCroppaBanner(1440,450)) }}">
                    @endif
                </div>
                <div>
                    <span class="btn btn-default btn-block btn-sm btn-file">
                        <span class="fileinput-new">Procurar...</span>
                        <span class="fileinput-exists"><i class="fa fa-refresh"></i> Alterar</span>
                        <input type="file" name="banner_image">
                    </span>
                    <a href="#" class="btn btn-danger btn-block btn-sm fileinput-exists" data-dismiss="fileinput" data-name="banner">
                        <i class="fa fa-close"></i> Remover
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            {{ Form::hidden('delete_photo_banner') }}
            {{ Form::hidden('delete_photo_thumbnail') }}
            <button class="btn btn-primary">Gravar</button>
        </div>
        {{ Form::close() }}
    </div>
</div>