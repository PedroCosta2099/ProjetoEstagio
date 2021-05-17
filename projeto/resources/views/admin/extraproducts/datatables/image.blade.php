@if($row->filepath)
    <img src="{{ asset($row->getCroppa(200, 200)) }}" id="" style="border:none" class="w-60px"/>
@else
    <img src="{{ asset('assets/img/default/avatar2.jpg') }}" style="border:none" class="w-60px"/>
@endif