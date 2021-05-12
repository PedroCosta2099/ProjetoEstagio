@if($bgExists)
    <htmlpageheader name="pageHeader" class="header">
        @if($documentTitle)
            <h1 class="text-uppercase text-right document-title line-height-1p4">
                <b>{{ $documentTitle }}</b><br/>
                <small class="bigger-150">{{ @$documentSubtitle }}</small>
            </h1>
        @endif
    </htmlpageheader>
@else
    <htmlpageheader name="pageHeader" class="header">
        <div style="background: {{ env('APP_MAIL_COLOR_PRIMARY') }}; height: 5mm;"></div>
        @if(File::exists(public_path() . '/assets/img/logo/logo_sm.png'))
            <img src="{{ asset('assets/img/logo/logo_sm.png') }}" style="float: left; margin: 15px 0 0 30px; max-width: 220px; max-height: 40px"/>
        @else
            <img src="{{ asset('assets/img/default/logo/logo_sm.png') }}" style="float: left; margin: 15px 0 0 30px; max-width: 220px; max-height: 40px"/>
        @endif
        @if($documentTitle)
            <h1 class="text-uppercase text-right document-title line-height-1p4" style="margin-right: 8mm; margin-top: -3mm">
                <b>{{ $documentTitle }}</b><br/>
                <small class="bigger-150">{{ @$documentSubtitle }}</small>
            </h1>
        @endif
    </htmlpageheader>
@endif
