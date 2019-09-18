<div class="vertical-nav bg-white overflow-auto" id="sidebar">
    <div class="py-4 px-3 mb-4 bg-light">
        <div class="media d-flex align-items-center"><img
                src="{{ $user->avatar }}" alt="..." width="65"
                class="mr-3 rounded-circle img-thumbnail shadow-sm">
            <div class="media-body">
                <h4 class="m-0">{{ $user->username }}</h4>
                <p class="font-weight-light text-muted mb-0">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- <p class="text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">Wallets</p> --}}
    <a href="/home" class="nav-link text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">
        Home
    </a>

    <a href="#" data-toggle="collapse" data-target="#wallet" class="nav-link text-gray font-weight-bold text-uppercase px-3 small pb-4 mb-0">
        Wallet
    </a>

    <div id="wallet" class="collapse">
        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
                <a href="wallet/all" class="nav-link text-dark font-italic">
                    <i class="fas fa-wallet mr-3 text-primary fa-fw"></i>
                    All
                </a>
            </li>
            @foreach ($wallet as $__wallet)
                <li class="nav-item">
                    <a href="wallet/{{ $__wallet->id }}" class="nav-link text-dark font-italic">
                        <i class="fas fa-wallet mr-3 text-primary fa-fw"></i>
                        {{ $__wallet->name }}
                    </a>
                </li>
            @endforeach
            <li class="nav-item">
                <a href="wallet/create" class="nav-link text-dark font-italic">
                    <i class="fas fa-plus-circle mr-3 text-primary fa-fw"></i>
                    Create new wallet
                </a>
            </li>
        </ul>
    </div>

    <a href="#" data-toggle="collapse" data-target="#category" class="nav-link text-gray font-weight-bold text-uppercase px-3 small py-4 mb-0">
        Categories
    </a>

    <div id="category" class="collapse">
        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
                <div class="nav-link">
                    <a href="cat/income" class="text-dark font-italic">
                        <i class="fas fa-money-bill-alt mr-3 text-success fa-fw"></i>
                        Income                    
                    </a>
                    <i class="fas fa-sort-down mr-3 text-success fa-fw float-right" data-toggle="collapse" data-target="#income" style="cursor:pointer"></i>
                </div>
                <div id="income" class="collapse">
                    {!! $income !!}
                </div>
            </li>
            <li class="nav-item">
                <div class="nav-link">
                    <a href="cat/outcome" class="text-dark font-italic">
                        <i class="fas fa-money-bill-alt mr-3 text-danger fa-fw"></i>
                        Outcome
                    </a>
                    <i class="fas fa-sort-down mr-3 text-danger fa-fw float-right" data-toggle="collapse" data-target="#outcome" style="cursor:pointer"></i>
                </div>
                <div id="outcome" class="collapse">
                    {!! $outcome !!}
                </div>
            </li>
            <li class="nav-item">
                <div class="nav-link">
                    <a href="cat/transfer" class="text-dark font-italic">
                        <i class="fas fa-exchange-alt mr-3 text-info fa-fw"></i>
                        Transfer
                    </a>
                    <i class="fas fa-sort-down mr-3 text-info fa-fw float-right" data-toggle="collapse" data-target="#transfer" style="cursor:pointer"></i>
                </div>
                <div id="transfer" class="collapse">
                    {!! $transfer !!}
                </div>
            </li>
            <li class="nav-item">
                <a href="cat/create" class="nav-link text-dark font-italic">
                    <i class="fas fa-plus-circle mr-3 text-primary fa-fw"></i>
                    Create new category
                </a>
            </li>
        </ul>
    </div>

    <a href="#" data-toggle="collapse" data-target="#setting" class="nav-link text-gray font-weight-bold text-uppercase px-3 small py-4 mb-0">
        Setting
    </a>

    <div id="setting" class="collapse">
        <ul class="nav flex-column bg-white mb-0">
            <li class="nav-item">
                <a href="profile" class="nav-link text-dark font-italic">
                    <i class="fas fa-user mr-3 text-primary fa-fw"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a href="logout" class="nav-link text-dark font-italic">
                    <i class="fas fa-sign-out-alt mr-3 text-primary fa-fw"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>
