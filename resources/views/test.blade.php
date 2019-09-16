@extends('layouts.app')

@section('content')
    <div id="category" class="collapse show">
        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
                <a href="#" data-toggle="collapse" data-target="#income" class="nav-link text-dark font-italic">
                    <i class="fas fa-money-bill-alt mr-3 text-success fa-fw"></i>
                    Income
                </a>
                <div id="income" class="collapse">
                    {!! $income !!}
                </div>
            </li>
            <li class="nav-item">
                <a href="#" data-toggle="collapse" data-target="#outcome" class="nav-link text-dark font-italic">
                    <i class="fas fa-money-bill-alt mr-3 text-danger fa-fw"></i>
                    Outcome
                </a>
                <div id="outcome" class="collapse">
                    {!! $outcome !!}
                </div>
            </li>
        </ul>
    </div>
    @foreach ($cat as $item)
        {{ $item['id'] }}<br>
    @endforeach
@endsection
