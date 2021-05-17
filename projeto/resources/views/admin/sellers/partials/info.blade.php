<div class="box no-border">
    <div class="box-body">
        {{ Form::model($seller, $formOptions) }}
        <div class="col-sm-8 col-lg-9">
            <div class="row row-5">
                <div class="col-sm-7">
                    <div class="form-group is-required">
                        {{ Form::label('name', 'Nome a apresentar no sistema')}}
                        {{ Form::text('name', null, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        {{ Form::label('phone', 'Telemóvel')}}
                        {{ Form::text('phone', null, array('class' =>'form-control')) }}
                    </div>
                </div>
            </div>
            <div class="form-group is-required">
                {{ Form::label('email', 'E-mail')}}
                {{ Form::email('email', null, array('class' =>'form-control', 'required' => true)) }}
            </div>
            @if(empty($seller->password))
                <div class="form-group is-required">
                    {{ Form::label('password', 'Password')}}
                    <div class="input-group input-group">
                        {{ Form::text('password', str_random(8), array('class' =>'form-control', 'required' => true)) }}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-flat" id="random-password" type="button">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </span>
                    </div>
                </div>
            @else
                <div class="row row-5">
                    <div class="col-sm-6">
                        <div class="form-group m-b-0">
                            {{ Form::label('password', 'Password')}}
                            {{ Form::password('password', array('class' =>'form-control', 'autocomplete' => 'off', 'placeholder' => 'Deixar vazio para não alterar')) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-b-0">
                            {{ Form::label('password_confirmation', 'Confirmar Password')}}
                            {{ Form::password('password_confirmation', array('class' =>'form-control', 'autocomplete' => 'off')) }}
                        </div>
                    </div>
                </div>
            @endif

            <h4 class="m-t-20 bold text-blue">Perfil e Permissões</h4>
            <div class="row row-5">
                <div class="col-sm-6">
                    @if($seller->hasRole([config('permissions.role.admin')]) || Auth::user()->id != $seller->id)
                    <div class="form-group is-required">
                        {{ Form::label('role_id', 'Perfis do utilizador')}}
                        {{ Form::select('role_id[]', $roles, $assignedRoles, array('class' =>'form-control select2', 'multiple' => true, 'style' => 'width: 100%;', Auth::user()->hasRole([config('permissions.role.admin')]) ? '' : 'required')) }}
                    </div>
                    @endif
                </div>
            </div>

            @if($seller->hasRole([config('permissions.role.admin')]) || Auth::user()->id != $seller->id)
            <h4 class="m-t-20 bold text-blue">Opções da conta</h4>
            <div class="row row-5">
                <div class="col-sm-6">
                    <table class="table table-condensed">
                        <tr>
                            <td>
                                <p class="form-control-static" style="border: none; padding-left: 0;">
                                    Bloquear acesso à administração <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Impede o colaborador de iniciar sessão no programa."></i>
                                </p>
                            </td>
                            <td style="width: 120px">
                                {{ Form::select('active', [1 => 'Sim', 0 => 'Não'], $seller->active ? 0 : 1, array('class' =>'form-control select2')) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
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