@if($address['id'] != null)
{{Form::open(array('route'=>['customer.saveAddress',$address['id']]))}}
@else
{{Form::open(array('route'=>['customer.saveNewAddress']))}}
@endif
                <div class="col-sm-12">
                    <div class="form-group is-required">
                        {{ Form::label('address', 'Morada')}}
                        {{ Form::text('address', $address['address'], array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group is-required">
                        {{ Form::label('postal_code', 'Código Postal')}}
                        {{ Form::text('postal_code', $address['postal_code'], array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group is-required">
                        {{ Form::label('city', 'Localidade')}}
                        {{ Form::text('city', $address['city'], array('class' =>'form-control', 'required' => true)) }}
                    </div>
                </div>
                <div class="form-group">
                {{ Form::label('actual_shipment_address', 'Morada de Envio Atual') }}&nbsp
                {{ Form::checkbox('actual_shipment_address',$address['actual_shipment_address'],$address['actual_shipment_address'], ['class' => 'form-control']) }}
            </div>
                <div class="m-t-5">
                <button class="btn btn-edit pull-right">Guardar</button>
                </div>
        {{Form::close()}}