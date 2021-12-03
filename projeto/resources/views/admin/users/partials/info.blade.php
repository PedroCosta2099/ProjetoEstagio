<div class="box no-border">
    <div class="box-body">
        {{ Form::model($user, $formOptions) }}
        <div class="col-sm-8 col-lg-9">
            <div class="row row-5">
                <div class="col-sm-8">
                    <div class="form-group is-required">
                        {{ Form::label('name', 'Nome a apresentar no sistema')}}
                        {{ Form::text('name', null, array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                @if(Auth::user()->isAdmin())
                <div class="col-sm-4">
                    <div class="form-group">
                        {{ Form::label('seller_id', 'Vendedor') }}
                        {{ Form::select('seller_id', ['0' => 'NENHUM'] + $sellers, null, ['class' => 'form-control select2']) }}
                    </div>
                </div>
                 @endif 
            </div>
            <div class="form-group is-required col-sm-8">
                {{ Form::label('email', 'E-mail')}}
                {{ Form::email('email', null, array('class' =>'form-control', 'required' => true)) }}
            </div>
            <div class="col-sm-4">
                    <div class="form-group">
                        {{ Form::label('phone', 'Telemóvel')}}
                        {{ Form::text('phone', null, array('class' =>'form-control')) }}
                    </div>
                </div>
            @if(empty($user->password))
                <div class="form-group is-required col-sm-12">
                    {{ Form::label('password', 'Password')}}
                    <div class="input-group input-group">
                        {{ Form::text('password', str_random(8), array('class' =>'form-control', 'required' => true,'autocomplete'=>'off')) }}
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-flat" id="random-password" type="button">
                                <i class="fas fa-redo"></i>
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
                    @if($user->hasRole([config('permissions.role.admin')]) || Auth::user()->id != $user->id)
                    <div class="form-group is-required">
                        {{ Form::label('role_id', 'Perfil do utilizador')}}
                        {{ Form::select('role_id[]', $roles, $assignedRoles, array('class' =>'form-control select2', 'multiple' => true, 'style' => 'width: 100%;', Auth::user()->hasRole([config('permissions.role.admin')]) ? '' : 'required')) }}
                    </div>
                    @endif
                </div>
            </div>

            @if($user->hasRole([config('permissions.role.admin')]) || Auth::user()->id != $user->id)
            <h4 class="m-t-20 bold text-blue">Opções da conta</h4>
            <div class="row row-5">
                <div class="col-sm-6">
                    <table class="table table-condensed">
                        <tr>
                            <td>
                                <p class="form-control-static" style="border: none; padding-left: 0;">
                                    Bloquear acesso ao programa <i class="fa fa-info-circle text-blue" data-toggle="tooltip" title="Impede o colaborador de iniciar sessão no programa."></i>
                                </p>
                            </td>
                            <td style="width: 120px">
                                {{ Form::select('active', [0 => 'Não', 1 => 'Sim'], $user->active ? 1 : 0, array('class' =>'form-control select2')) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            @endif
        </div>
        <div class="col-sm-4 col-lg-3">
            {{ Form::label('image', 'Fotografia:', array('class' => 'form-label')) }}<br/>
            <div class="fileinput {{ $user->filepath ? 'fileinput-exists' : 'fileinput-new'}}" data-provides="fileinput">
                <div class="fileinput-new thumbnail" style="width: 150px; height: 150px;">
                    <img src="{{ asset('assets/img/default/avatar.png') }}">
                </div>
                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 150px; max-height: 150px;">
                    @if($user->filepath)
                        <img src="{{ asset($user->getCroppa(200, 200)) }}">
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