<div class="box no-border">
    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="section-title">
                            <a href="?tab=customization"
                               data-toggle="tooltip"
                               title="Altere a cor da aplicação só para a sua conta sem afetar outros utilizadores."
                               style="margin: -4px;" class="btn btn-xs btn-primary pull-right">
                                Personalizar para mim
                            </a>
                            Design e Usabilidade
                        </h4>
                        <table class="table table-condensed m-0">
                            <tr style="border-bottom: 1px solid #eee">
                                <td>{{ Form::label('app_skin', 'Cor da aplicação', ['class' => 'control-label']) }}</td>
                                <td class="w-1">
                                    <div class="{{ Setting::get('app_skin') }}">
                                        <div class="skin-preview skin-master" data-current-skin="{{ Setting::get('app_skin') }}" style="height: 22px; width: 22px; margin: 6px -3px 0 0;"></div>
                                    </div>
                                </td>
                                <td class="w-200px">{{ Form::select('app_skin', ['skin-yellow' => 'Amarelo','skin-red'=>'Vermelho'], Setting::get('app_skin'), ['class' =>'form-control select2']) }}</td>
                                
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <hr/>
                {{ Form::submit('Gravar', array('class' => 'btn btn-primary' ))}}
            </div>
        </div>
    </div>
</div>

