@if(isset($breadcrumbs) && count($breadcrumbs) > 0)
<div class="breadcrumb-bar">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="/"><x-lucide-home style="width:12px;height:12px;vertical-align:middle;margin-right:2px" /> Beranda</a></li>
            @foreach($breadcrumbs as $bc)
                <li><span class="sep">›</span></li>
                @if($loop->last)
                    <li class="active">{{ $bc['label'] }}</li>
                @else
                    <li><a href="{{ $bc['url'] }}">{{ $bc['label'] }}</a></li>
                @endif
            @endforeach
        </ol>
    </div>
</div>
@endif
