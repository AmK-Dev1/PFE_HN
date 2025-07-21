<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="navbar-brand">
                <div class="brand-logo">
                    <img src="{{asset('/images/Alfred_bleu_tête.png')}}" height="30px" alt="" class="mr-0">
                    <img src="{{asset('/images/Alfred_bleu_texte.png')}}"  alt="">
                </div>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content overflow-hidden">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="menu-title text-truncate" data-i18n="dashboard">
                            {{ __('labels.fields.dashboard') }}
                        </span>
                    </a>
                </li>

                <li class="nav-item has-sub @if (request()->is(['admin/users*', 'admin/admins*','admin/roles*'])) sidebar-group-active open @endif">
                    <a href="#" class="d-flex align-items-center">
                        <i class="fas fa-user-circle"></i>
                        <span class="menu-title text-truncate">
                            {{ trans_choice('labels.models.account', 2) }}
                        </span>
                    </a>

                    <ul class="menu-content">
                            <li class="nav-item">
                                <a href="{{ route('admin.admins.index') }}"
                                   class="nav-link {{ request()->routeIs('admin.admins*') ? 'active' : '' }}"
                                   data-link="/admin/admins">
                                    <i class="fas fa-users-cog"></i>
                                    <span
                                        class="menu-title text-truncate">{{ trans_choice('labels.models.admin', 2) }}</span>
                                </a>
                            </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" data-link="/admin/users"
                               class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }} ">
                                <i class="fas fa-user-tag"></i>
                                <span
                                    class="menu-title text-truncate">{{ trans_choice('labels.models.user', 2) }}</span>
                            </a>
                        </li>

                        <li class="nav-item">
                             <a href="{{ route('admin.roles.index') }}" data-link="/admin/roles"
                                class="nav-link {{ request()->routeIs('admin.roles*') ? 'active' : '' }} ">
                                 <i class="fas fa-user-tag"></i>
                                 <span
                                     class="menu-title text-truncate">{{ trans_choice('labels.models.role', 3) }}</span>
                             </a>
                         </li>

                    </ul>
                </li>
            <li class=" nav-item {{ request()->routeIs('admin.companies*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.companies.index') }}">
                    <i class="fas fa-home"></i>
                    <span class="menu-title text-truncate" data-i18n="dashboard">
                            {{ trans_choice('labels.models.company', 2) }}
                        </span>
                </a>
            </li>

            <li class=" nav-item {{ request()->routeIs('admin.types*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.types.index') }}">
                    <i class="fas fa-list-alt"></i>
                    <span class="menu-title text-truncate" data-i18n="dashboard">
                            {{ trans_choice('labels.models.type', 2) }}
                        </span>
                </a>
            </li>

            <li class=" nav-item {{ request()->routeIs('admin.subtypes*') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.subtypes.index') }}">
                    <i class="fas fa-list-alt"></i>
                    <span class="menu-title text-truncate" data-i18n="dashboard">
                            {{ trans_choice('labels.models.subtype', 2) }}
                        </span>
                </a>
            </li>
            <!-- Section pour les données de cotisation dans la barre latérale -->
            <li class="nav-item {{ request()->routeIs('admin.contributions*') ? 'active' : '' }}">
                 <a class="d-flex align-items-center" href="{{ route('admin.contributions.index') }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-title text-truncate">
                           {{ trans_choice('labels.models.contribution', 2) }}
                         </span>
                 </a>
            </li>
                    
    
    </a>
</li>



        </ul>
    </div>
</div>
