<div class="box no-border">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4">
                <h4 class="section-title">Registo de Erros</h4>
                <table class="table table-condensed">
                    <tr>
                        <td>{{ Form::label('debug_mode', 'Modo de Debug', ['class' => 'control-label']) }}</td>
                        <td class="check">{{ Form::checkbox('debug_mode', 1, Setting::get('debug_mode'), ['class' => 'ios'] ) }}</td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('api_debug_mode', 'Debug chamadas API', ['class' => 'control-label']) }}</td>
                        <td class="check">{{ Form::checkbox('api_debug_mode', 1, Setting::get('api_debug_mode'), ['class' => 'ios'] ) }}</td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('error_log_email_active', 'Enviar Log por e-mail', ['class' => 'control-label']) }}</td>
                        <td class="check">{{ Form::checkbox('error_log_email_active', 1, Setting::get('error_log_email_active'), ['class' => 'ios'] ) }}</td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('error_log_email', 'E-mail para notificação', ['class' => 'control-label']) }}</td>
                        <td class="w-45">{{ Form::text('error_log_email', Setting::get('error_log_email'), ['class' =>'form-control']) }}</td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('server_size_gb', 'Espaço em servidor', ['class' => 'control-label']) }}</td>
                        <td class="w-45">
                            <div class="input-group">
                                {{ Form::text('server_size_gb', Setting::get('server_size_gb') ? Setting::get('server_size_gb') : 10, ['class' =>'form-control']) }}
                                <div class="input-group-addon">
                                    GB
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('debug_ignore_ip', 'Ignorar para os IP\'s', ['class' => 'control-label']) }}</td>
                        <td>{{ Form::text('debug_ignore_ip', client_ip(), ['class' =>'form-control']) }}</td>
                    </tr>
                </table>
                <h4 class="section-title">Sistema em Manutenção</h4>
                <table class="table table-condensed">
                    <tr>
                        <td>{{ Form::label('maintenance_mode', 'Sistema em Manutenção', ['class' => 'control-label']) }}</td>
                        <td class="check">{{ Form::checkbox('maintenance_mode', 1, Setting::get('maintenance_mode'), ['class' => 'ios'] ) }}</td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('maintenance_time', 'Tempo previsto manutenção', ['class' => 'control-label']) }}</td>
                        <td class="w-45">
                            <div class="input-group">
                                {{ Form::text('maintenance_time', Setting::get('maintenance_time'), ['class' =>'form-control']) }}
                                <div class="input-group-addon">min</div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>{{ Form::label('maintenance_ignore_ip', 'Ignorar para os IP\'s', ['class' => 'control-label']) }}</td>
                        <td >{{ Form::text('maintenance_ignore_ip', client_ip(), ['class' =>'form-control']) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-12">
                <hr/>
                {{ Form::submit('Gravar', array('class' => 'btn btn-primary' ))}}
            </div>
        </div>
    </div>
</div>

