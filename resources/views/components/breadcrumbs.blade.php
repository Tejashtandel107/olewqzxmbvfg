@props(['breadcrumbs'=>[]])

<!-- Page Breadcrumbs -->
@if(isset($breadcrumbs) && !empty($breadcrumbs))
    <ol class="breadcrumb m-0">
    @foreach ($breadcrumbs as $title=>$url)
        <li class="breadcrumb-item">
            @if(strlen($url)>0)
                <a href="{{$url}}">{{$title}}</a>
            @else
                {{$title}}
            @endif
        </li>
    @endforeach
    </ol>
@endif

