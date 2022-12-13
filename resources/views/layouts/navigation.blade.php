<nav class="navbar navbar-vertical navbar-expand-xl navbar-light">
    <div class="d-flex align-items-center">
        <div class="toggle-icon-wrapper">
            <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-toggle="tooltip" data-placement="right" title="عرض/إخفاء القائمة الرئيسية"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
        </div>
        <a class="navbar-brand" href="{{ url('/') }}/dashboard">
            <div class="d-flex align-items-center py-3">
                <img class="mr-2" src="{{ asset('/public/themes/Falcon/v2.8.0/assets/img/favicons/logo.png') }}" alt="" width="40" />
                <span class="text-stc- fs-1" dir="ltr">{{ config('app.name', 'Qasetli Portal') }}</span>
            </div>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
        <div class="navbar-vertical-content perfect-scrollbar scrollbar pt-0">
            <div class="p-1" style="padding-top: 0px !important">
                <input type="text" class="form-control mb-1" placeholder="البحث في القائمة..." data-action="navbar-filter" />
            </div>
            <ul class="navbar-nav flex-column">
                <li class="pt-0 pb-2 px-2">
                    <div class="media align-items-center mb-1 employee">
                        <img class="rounded-circle" src="{{ \Auth::user()->personal_image_url }}" alt="{{ \Auth::user()->full_name }}" width="50" />
                        <div class="media-body ml-3">
                            <h6 class="mb-0">{{ \Auth::user()->first_name }}<h6>
                            @foreach (\Auth::user()->roles as $role)
                                <span class="badge badge-soft-info fs--3 mb-0">{{ $role->label }}</span>
                            @endforeach
                        </div>
                    </div>
                </li>
           
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}/dashboard">
                        <div class="d-flex align-items-center">
                            <span class="nav-link-icon"><span class="fas fa-chart-pie"></span></span>
                            <span class="nav-link-text">{{ __('الرئيسية') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <div class="d-flex align-items-center" data-action="change-password-create">
                            <span class="nav-link-icon"><span class="fas fa-cog"></span></span>
                            <span class="nav-link-text">{{ __('إعادة تعيين كلمة المرور') }}</span>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">
                        <div class="d-flex align-items-center" data-action="logout">
                            <span class="nav-link-icon"><span class="fas fa-sign-out-alt"></span></span>
                            <span class="nav-link-text">{{ __('تسجيل الخروج') }}</span>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>

            <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                @if(
                    \Auth::user()->can('Products_module_customers_manage') ||
                    \Auth::user()->can('Products_module_attributes_manage') ||
                    \Auth::user()->can('Products_module_Products_manage')
                )
                    @if(\Auth::user()->can('Products_module_categories_manage'))
                        <li class="nav-item @if(isset($activePage['categories'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/categories/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-sitemap"></span></span>
                                    <span class="nav-link-text">إدارة التصنيفات</span>
                                </div>
                            </a>
                        </li>
                    @endif


                    @if(\Auth::user()->can('Products_module_Products_manage'))
                        <li class="nav-item @if(isset($activePage['Products'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/products/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">إدارة المنتجات</span>
                                </div>
                            </a>
                        </li>
                    @endif

                    @if(\Auth::user()->can('Products_module_category_attribute_types_manage'))
                        <li class="nav-item @if(isset($activePage['category_attribute_types'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/category_attribute_types/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">إدارة المواصفات</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->can('Products_module_Products_manage'))
                        <li class="nav-item @if(isset($activePage['registrations'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/registrations/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">إدارة الحجوزات</span>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->can('Products_module_tags_manage'))
                        <li class="nav-item @if(isset($activePage['tags'])) active @endif">
                            <a class="nav-link" href="{{ url('/') }}/tags/manage">
                                <div class="d-flex align-items-center">
                                    <span class="nav-link-icon"><span class="fas fa-th-list"></span></span>
                                    <span class="nav-link-text">إدارة التاجات</span>
                                </div>
                            </a>
                        </li>
                    @endif
                @endif
            </ul>
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">

                @if(
                    \Auth::user()->can('users_module_roles_manage') ||
                    \Auth::user()->can('users_module_permissions_manage') ||
                    \Auth::user()->can('users_module_users_manage') ||
                    \Auth::user()->can('users_module_authentication_log_manage') ||
                    \Auth::user()->can('users_module_sessions_manage')
                )
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator" href="#vendors" data-toggle="collapse" role="button" @if(isset($activePage['vendors'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text">{{ __('إدارة الموردين') }}</span>
                            </div>
                        </a>
                        <ul class="nav collapse @if(isset($activePage['vendors'])) show @endif" id="vendors" data-parent="#navbarVerticalCollapse">
                            @if(\Auth::user()->can('vendors_module_vendors_manage'))
                            <li class="nav-item @if(isset($activePage['vendors']) && $activePage['vendors'] == 'vendors') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/vendors/manage">{{ __('الموردين') }}</a>
                            </li>
                            @endif

                            @if(\Auth::user()->can('vendors_module_type_of_vendors_manage'))
                            <li class="nav-item @if(isset($activePage['vendors']) && $activePage['vendors'] == 'type_of_vendors') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/type_of_vendors/manage">{{ __('انواع الموردين') }}</a>
                            </li>
                            @endif
                            @if(\Auth::user()->can('vendors_module_times_labelmanage'))
                            <li class="nav-item @if(isset($activePage['vendors']) && $activePage['vendors'] == 'times_label') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/times_label/manage">{{ __('انواع التوقيتات') }}</a>
                            </li>
                            @endif

                        </ul>
                    </li>
                @endif
            </ul>
            
            <div class="navbar-vertical-divider">
                <hr class="navbar-vertical-hr my-2" />
            </div>
            <ul class="navbar-nav flex-column" id="navbarVerticalNav">

                @if(
                    \Auth::user()->can('users_module_roles_manage') ||
                    \Auth::user()->can('users_module_permissions_manage') ||
                    \Auth::user()->can('users_module_users_manage') ||
                    \Auth::user()->can('users_module_authentication_log_manage') ||
                    \Auth::user()->can('users_module_sessions_manage')
                )
                    <li class="nav-item">
                        <a class="nav-link dropdown-indicator" href="#users" data-toggle="collapse" role="button" @if(isset($activePage['users'])) aria-expanded="true" @else aria-expanded="false" @endif aria-controls="pages">
                            <div class="d-flex align-items-center">
                                <span class="nav-link-icon"><span class="fas fa-users"></span></span>
                                <span class="nav-link-text">{{ __('إدارة المستخدمين') }}</span>
                            </div>
                        </a>
                        <ul class="nav collapse @if(isset($activePage['users'])) show @endif" id="users" data-parent="#navbarVerticalCollapse">
                            @if(\Auth::user()->can('users_module_roles_manage'))
                            <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'roles') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/users/roles/manage">{{ __('الأدوار') }}</a>
                            </li>
                            @endif

                            @if(\Auth::user()->can('users_module_permissions_manage'))
                            <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'permissions') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/users/permissions/manage">{{ __('الصلاحيات') }}</a>
                            </li>
                            @endif

                            @if(\Auth::user()->can('users_module_users_manage'))
                            <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'users') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/users/manage">{{ __('المستخدمين') }}</a>
                            </li>
                            @endif

                            @if(\Auth::user()->can('users_module_contact_us_manage'))
                            <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'contact_us') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/contact_us/manage">{{ __('الاستفسارات') }}</a>
                            </li>
                            @endif
                            @if(\Auth::user()->can('users_module_rating_manage'))
                            <li class="nav-item @if(isset($activePage['users']) && $activePage['users'] == 'rating') active @endif">
                                <a class="nav-link" href="{{ url('/') }}/rating/manage">{{ __('التقيميات') }}</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
