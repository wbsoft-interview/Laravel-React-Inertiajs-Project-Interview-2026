<nav id="sidebar" class="
">
    <div class="sidebar-header">
        <h3>
            @php
            //To get logo...
            $softLogo = App\Models\Logo::getSoftwareLogo();
            @endphp

            @if(isset($softLogo) && $softLogo->logo_image != null)
            <img class="custom-sidebar-logo" src="{{ $softLogo->logo_image }}"
                class="img-fluid" />
            @else
            <img src="{{asset('backend')}}/billing_invoice_logo.png" class="img-fluid" />
            @endif
        </h3>
    </div>

    <ul class="list-unstyled components custom-sidebar-menu-icon">

        @if(session('package_expired'))

        <li class="active">
            <a href="{{route('package-renew')}}" class="dashboard
            {{ request()->is('package-renew') ? 'custom-sidebar-menu-active' : '' }}
            ">
                <i class="material-symbols-outlined">dashboard</i><span>Renew Package</span>
            </a>
        </li>

        @else

        <li class="active">
            <a href="{{route('admin.dashboard')}}" class="dashboard
            {{ request()->is('admin/dashboard') ? 'custom-sidebar-menu-active' : '' }}
            ">
                <i class="material-symbols-outlined">dashboard</i><span>Dashboard</span>
            </a>
        </li>

        @if(Auth::user()->role == 'superadmin')
        <div class="small-screen navbar-display">
            @if(Auth::user()->can('account-list'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('account') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('account-profile/*') ? 'custom-sidebar-menu-active' : '' }}
                        {{ request()->is('account-category') ? 'custom-sidebar-menu-active' : '' }}
                    " onclick="sidebarCollapseMenu(4)">
                    <i class="material-symbols-outlined">clinical_notes</i><span>Accounts</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('account') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('account-profile/*') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('account-category') ? 'custom-sidebar-open' : '' }}

                    " id="sidebar_collapse_4">

                    {{-- @if(Auth::user()->can('account-category-list'))
                    <li class="ms-2
                        {{ request()->is('account-category') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('account-category.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Category</a>
                    </li>
                    @endif --}}

                    @if(Auth::user()->can('account-list'))
                    <li class="ms-2
                        {{ request()->is('account') ? 'custom-sidebar-submenu-active' : '' }}
                        {{ request()->is('account-profile/*') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('account.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Account List</a>
                    </li>
                    @endif


                </ul>
            </li>
            @endif
        </div>
        
        <div class="small-screen navbar-display">
            @if(Auth::user()->can('package-category-list') || Auth::user()->can('package-list'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('package') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('package-category') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('purchase-account-list') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('purchase-account-profile/*') ? 'custom-sidebar-menu-active' : '' }}
                    " onclick="sidebarCollapseMenu(6)">
                    <i class="material-symbols-outlined">subscriptions</i>
                    <span>Subscriptions</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('package') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('package-category') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('purchase-account-list') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('purchase-account-profile/*') ? 'custom-sidebar-open' : '' }}

                    " id="sidebar_collapse_6">

                    @if(Auth::user()->can('package-category-list'))
                    <li class="ms-2
                        {{ request()->is('package-category') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('package-category.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Category List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('package-list'))
                    <li class="ms-2
                        {{ request()->is('package') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('package.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Package List</a>
                    </li>
                    @endif
                    
                    @if(Auth::user()->can('package-list'))
                    <li class="ms-2
                        {{ request()->is('purchase-account-list') ? 'custom-sidebar-submenu-active' : '' }}
                        {{ request()->is('purchase-account-profile/*') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('purchase-account-list') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Purchase Account</a>
                    </li>
                    @endif


                </ul>
            </li>
            @endif
        </div>
        @endif

        @if(Auth::user()->role != 'superadmin')
        <div class="small-screen navbar-display">
            @if(Auth::user()->can('notice-sms-list') || Auth::user()->can('sms-template-list') || Auth::user()->can('sms-count-view-access'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('user-sms') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('sms-dashboard') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('sms-template') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('user-sms/create') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('user-sms/*/edit') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('user-sms-send') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('notice-sms') ? 'custom-sidebar-menu-active' : '' }}

                    " onclick="sidebarCollapseMenu(20)">
                    <i class="material-symbols-outlined">stream_apps</i><span>SMS Module</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('user-sms') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('sms-dashboard') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('sms-template') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('suser-sms/create') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('user-sms/*/edit') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('user-sms-send') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('notice-sms') ? 'custom-sidebar-open' : '' }}

                    " id="sidebar_collapse_20">
                   
                    @if(Auth::user()->can('sms-count-view-access'))
                    @if(Auth::user()->role != 'superadmin')
                    <li class="ms-2
                        {{ request()->is('sms-dashboard') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('sms-dashboard.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            SMS Dashboard</a>
                    </li>
                    @endif
                    @endif

                    @if(Auth::user()->can('sms-template-list'))
                    @if(Auth::user()->role != 'superadmin')
                    <li class="ms-2
                        {{ request()->is('sms-template') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('sms-template.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            SMS Template</a>
                    </li>
                    @endif
                    @endif

                    @if(Auth::user()->can('notice-sms-list'))
                    @if(Auth::user()->role != 'superadmin')
                    <li class="ms-2
                        {{ request()->is('notice-sms') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('notice-sms.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Notice SMS</a>
                    </li>
                    @endif
                    @endif

                </ul>
            </li>
            @endif
        </div>
        @endif

        <div class="small-screen navbar-display">
            @if(Auth::user()->can('notice-sms-list') || Auth::user()->can('sms-template-list') || Auth::user()->can('sms-count-view-access'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('push-notification') ? 'custom-sidebar-menu-active' : '' }}

                    " onclick="sidebarCollapseMenu(7)">
                    <i class="material-symbols-outlined">stream_apps</i><span>Notification Module</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('push-notification') ? 'custom-sidebar-open' : '' }}

                    " id="sidebar_collapse_7">
                   
                    @if(Auth::user()->can('push-notification-list'))
                    <li class="ms-2
                        {{ request()->is('push-notification') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('push-notification.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Schedule Notification</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>

        <div class="small-screen navbar-display">
            @if(Auth::user()->can('expense-category-list') || Auth::user()->can('expense-category-create')
            || Auth::user()->can('expense-list') || Auth::user()->can('expense-create')
            || Auth::user()->can('expense-receipt-list') || Auth::user()->can('expense-receipt-create')
            || Auth::user()->can('payee-list') || Auth::user()->can('payee-create'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                            {{ request()->is('payee') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('active-payee-list') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('inactive-payee-list') ? 'custom-sidebar-menu-active' : '' }}

                            {{ request()->is('expense-category') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('active-expense-category-list') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('inactive-expense-category-list') ? 'custom-sidebar-menu-active' : '' }}

                            {{ request()->is('expense') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('active-expense-list') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('inactive-expense-list') ? 'custom-sidebar-menu-active' : '' }}

                            {{ request()->is('expense-receipt') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('expense-receipt/create') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('expense-receipt/*/edit') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('save-all-expense-receipt') ? 'custom-sidebar-menu-active' : '' }}
                            " onclick="sidebarCollapseMenu(1)">
                    <i class="material-symbols-outlined">local_mall</i><span>Expense Manage</span></a>
                <ul class="collapse list-unstyled menu
                            {{ request()->is('payee') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('active-payee-list') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('inactive-payee-list') ? 'custom-sidebar-open' : '' }}

                            {{ request()->is('expense-category') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('active-expense-category-list') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('inactive-expense-category-list') ? 'custom-sidebar-open' : '' }}

                            {{ request()->is('expense') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('active-expense-list') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('inactive-expense-list') ? 'custom-sidebar-open' : '' }}

                            {{ request()->is('expense-receipt') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('expense-receipt/create') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('expense-receipt/*/edit') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('save-all-expense-receipt') ? 'custom-sidebar-open' : '' }}
                            " id="sidebar_collapse_1">

                    @if(Auth::user()->can('payee-list'))
                    <li class="ms-2
                                {{ request()->is('payee') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('active-payee-list') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('inactive-payee-list') ? 'custom-sidebar-submenu-active' : '' }}
                                ">
                        <a href="{{ route('payee.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Payee List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('expense-category-list'))
                    <li class="ms-2
                                {{ request()->is('expense-category') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('active-expense-category-list') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('inactive-expense-category-list') ? 'custom-sidebar-submenu-active' : '' }}
                                ">
                        <a href="{{ route('expense-category.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Category List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('expense-list'))
                    <li class="ms-2
                                {{ request()->is('expense') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('active-expense-list') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('inactive-expense-list') ? 'custom-sidebar-submenu-active' : '' }}
                                ">
                        <a href="{{ route('expense.index') }}"><i class="fa-regular fa-circle sidebar-li"></i> Expense
                            List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('expense-receipt-list'))
                    <li class="ms-2
                                {{ request()->is('expense-receipt') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('expense-receipt/create') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('expense-receipt/*/edit') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('save-all-expense-receipt') ? 'custom-sidebar-submenu-active' : '' }}
                                ">
                        <a href="{{ route('expense-receipt.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Expense Receipt</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>

        <div class="small-screen navbar-display">
            @if(Auth::user()->can('income-category-list') || Auth::user()->can('income-category-create')
            || Auth::user()->can('income-list') || Auth::user()->can('income-create')
            || Auth::user()->can('income-receipt-list') || Auth::user()->can('income-receipt-create')
            || Auth::user()->can('receiver-list') || Auth::user()->can('receiver-create'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenuIncome" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                            {{ request()->is('receiver') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('active-receiver-list') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('inactive-receiver-list') ? 'custom-sidebar-menu-active' : '' }}

                            {{ request()->is('income-category') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('active-income-category-list') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('inactive-income-category-list') ? 'custom-sidebar-menu-active' : '' }}

                            {{ request()->is('income') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('active-income-list') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('inactive-income-list') ? 'custom-sidebar-menu-active' : '' }}

                            {{ request()->is('income-receipt') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('income-receipt/create') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('income-receipt/*/edit') ? 'custom-sidebar-menu-active' : '' }}
                            {{ request()->is('save-all-income-receipt') ? 'custom-sidebar-menu-active' : '' }}"
                    onclick="sidebarCollapseMenu(22)">
                    <i class="material-symbols-outlined">savings</i><span>Income Manage</span></a>
                <ul class="collapse list-unstyled menu
                            {{ request()->is('receiver') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('active-receiver-list') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('inactive-receiver-list') ? 'custom-sidebar-open' : '' }}

                            {{ request()->is('income-category') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('active-income-category-list') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('inactive-income-category-list') ? 'custom-sidebar-open' : '' }}

                            {{ request()->is('income') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('active-income-list') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('inactive-income-list') ? 'custom-sidebar-open' : '' }}

                            {{ request()->is('income-receipt') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('income-receipt/create') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('income-receipt/*/edit') ? 'custom-sidebar-open' : '' }}
                            {{ request()->is('save-all-income-receipt') ? 'custom-sidebar-open' : '' }}"
                    id="sidebar_collapse_22">

                    @if(Auth::user()->can('receiver-list'))
                    <li class="ms-2
                                {{ request()->is('receiver') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('active-receiver-list') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('inactive-receiver-list') ? 'custom-sidebar-submenu-active' : '' }}">
                        <a href="{{ route('receiver.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Receiver List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('income-category-list'))
                    <li class="ms-2
                                {{ request()->is('income-category') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('active-income-category-list') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('inactive-income-category-list') ? 'custom-sidebar-submenu-active' : '' }}">
                        <a href="{{ route('income-category.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Category List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('income-list'))
                    <li class="ms-2
                                {{ request()->is('income') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('active-income-list') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('inactive-income-list') ? 'custom-sidebar-submenu-active' : '' }}">
                        <a href="{{ route('income.index') }}"><i class="fa-regular fa-circle sidebar-li"></i> Income List</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('income-receipt-list'))
                    <li class="ms-2
                                {{ request()->is('income-receipt') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('income-receipt/create') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('income-receipt/*/edit') ? 'custom-sidebar-submenu-active' : '' }}
                                {{ request()->is('save-all-income-receipt') ? 'custom-sidebar-submenu-active' : '' }}">
                        <a href="{{ route('income-receipt.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Income Receipt</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>

        @if(Auth::user()->role == 'superadmin')
        <div class="small-screen navbar-display">
            @if(Auth::user()->can('blog-list') || Auth::user()->can('blog-category-list'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('blog-category') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('blog') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('blog/create') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('blog/*/edit') ? 'custom-sidebar-menu-active' : '' }}
                    " onclick="sidebarCollapseMenu(2)">
                    <i class="material-symbols-outlined">note_stack_add</i><span>Blogs</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('blog-category') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('blog') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('blog/create') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('blog/*/edit') ? 'custom-sidebar-open' : '' }}
                    " id="sidebar_collapse_2">

                    @if(Auth::user()->can('blog-category-list'))
                    <li class="ms-2
                        {{ request()->is('blog-category') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('blog-category.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Blog Cateogry</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('blog-list'))
                    <li class="ms-2
                        {{ request()->is('blog') ? 'custom-sidebar-submenu-active' : '' }}
                        {{ request()->is('blog/*/edit') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('blog.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Blog List</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>
        @endif

        <div class="small-screen navbar-display">
            @if(Auth::user()->can('ticket-support-list') || Auth::user()->can('notice-sms-list'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('ticket-support') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('ticket-support/create') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('ticket-support-details/*') ? 'custom-sidebar-menu-active' : '' }}

                    " onclick="sidebarCollapseMenu(24)">
                    <i class="material-symbols-outlined">shoppingmode</i><span>Supports Module</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('ticket-support') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('ticket-support/create') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('ticket-support-details/*') ? 'custom-sidebar-open' : '' }}

                    " id="sidebar_collapse_24">

                    @if(Auth::user()->can('ticket-support-create'))
                    <li class="ms-2
                        {{ request()->is('ticket-support/create') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('ticket-support.create') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Open New Ticket</a>
                    </li>
                    @endif
                    
                    @if(Auth::user()->can('ticket-support-list'))
                    <li class="ms-2
                        {{ request()->is('ticket-support') ? 'custom-sidebar-submenu-active' : '' }}
                        {{ request()->is('ticket-support-details/*') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('ticket-support.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Ticket List</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>

         @if(Auth::user()->role == 'superadmin')
        <div class="small-screen navbar-display">
            @if(Auth::user()->can('documentation-category-list') || Auth::user()->can('documentation-tag-list')
            || Auth::user()->can('documentation-list'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false"
                    class="dropdown-toggle
                    {{ request()->is('documentation-category') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('documentation-tag') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('documentation') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('documentation/create') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('documentation/*/edit') ? 'custom-sidebar-menu-active' : '' }}

                    " onclick="sidebarCollapseMenu(23)">
                    <i class="material-symbols-outlined">article</i><span>Documentation</span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('documentation-category') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('documentation-tag') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('documentation') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('documentation/create') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('documentation/*/edit') ? 'custom-sidebar-open' : '' }}

                    " id="sidebar_collapse_23">
                    
                    @if(Auth::user()->can('documentation-category-list'))
                    <li class="ms-2
                        {{ request()->is('documentation-category') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('documentation-category.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Category</a>
                    </li>
                    @endif
                    
                    @if(Auth::user()->can('documentation-tag-list'))
                    <li class="ms-2
                        {{ request()->is('documentation-tag') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('documentation-tag.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Tags</a>
                    </li>
                    @endif
                    
                    @if(Auth::user()->can('documentation-list'))
                    <li class="ms-2
                        {{ request()->is('documentation') ? 'custom-sidebar-submenu-active' : '' }}
                        {{ request()->is('documentation/create') ? 'custom-sidebar-submenu-active' : '' }}
                        {{ request()->is('documentation/*/edit') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('documentation.index') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            Documentation</a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>
        @endif

        <div class="small-screen navbar-display">
            @if(Auth::user()->can('zone-list'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse"
                    aria-expanded="false" class="dropdown-toggle
                    {{ request()->is('division-list') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('district-list') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('upozila-list') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('zone') ? 'custom-sidebar-menu-active' : '' }}
                    " onclick="sidebarCollapseMenu(8)">
                    <i class="material-symbols-outlined">location_on</i><span> Locations</span></span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('division-list') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('district-list') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('upozila-list') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('zone') ? 'custom-sidebar-open' : '' }}
                " id="sidebar_collapse_8">

                    @if (Auth::user()->can('zone-list') || Auth::user()->can('zone-create'))
                    <li class="submenu-item
                        {{ request()->is('division-list') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('division-list') }}" class="submenu-link">
                            <i class="fa-regular fa-circle"></i>
                            <span>Division</span>
                        </a>
                    </li>
                    @endif
                    
                    @if (Auth::user()->can('zone-list') || Auth::user()->can('zone-create'))
                    <li class="submenu-item
                        {{ request()->is('district-list') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('district-list') }}" class="submenu-link">
                            <i class="fa-regular fa-circle"></i>
                            <span>District</span>
                        </a>
                    </li>
                    @endif
                    
                    @if (Auth::user()->can('zone-list') || Auth::user()->can('zone-create'))
                    <li class="submenu-item
                        {{ request()->is('upozila-list') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('upozila-list') }}" class="submenu-link">
                            <i class="fa-regular fa-circle"></i>
                            <span>Upozila</span>
                        </a>
                    </li>
                    @endif
                    
                    @if (Auth::user()->can('zone-list') || Auth::user()->can('zone-create'))
                    <li class="submenu-item
                        {{ request()->is('zone') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('zone.index') }}" class="submenu-link">
                            <i class="fa-regular fa-circle"></i>
                            <span>Zone</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </li>
            @endif
        </div>
        
        <div class="small-screen navbar-display">
            @if(Auth::user()->can('user-list') || Auth::user()->can('role-list')
            || Auth::user()->can('logo-setting-access') || Auth::user()->can('pohto-gallery-access'))
            <li class="dropdown">
                <a href="javascript:void(0)" data-bs-target="#homeSubmenu0" data-bs-toggle="collapse"
                    aria-expanded="false" class="dropdown-toggle
                    {{ request()->is('setting') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('user-activity') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('pohto-gallery') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('roles') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('roles/create') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('roles/*/edit') ? 'custom-sidebar-menu-active' : '' }}
                    {{ request()->is('users') ? 'custom-sidebar-menu-active' : '' }}
                    " onclick="sidebarCollapseMenu(3)">
                    <i class="material-symbols-outlined">settings</i><span> Settings</span></span></a>
                <ul class="collapse list-unstyled menu
                    {{ request()->is('setting') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('user-activity') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('pohto-gallery') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('roles') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('roles/create') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('roles/*/edit') ? 'custom-sidebar-open' : '' }}
                    {{ request()->is('users') ? 'custom-sidebar-open' : '' }}
                " id="sidebar_collapse_3">

                    @if (Auth::user()->can('logo-setting-access'))
                    <li class="ms-2
                    {{ request()->is('setting') ? 'custom-sidebar-submenu-active' : '' }}
                    ">
                        <a href="{{ route('setting') }}"><i class="fa-regular fa-circle sidebar-li"></i> General
                            Setting</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('general-setting-access'))
                    <li class="ms-2
                        {{ request()->is('user-activity') ? 'custom-sidebar-submenu-active' : '' }}
                        ">
                        <a href="{{ route('user-activity') }}"><i class="fa-regular fa-circle sidebar-li"></i>
                            User Activity</a>
                    </li>
                    @endif
                    
                    @if (Auth::user()->can('photo-gallery-access'))
                    <li class="ms-2
                    {{ request()->is('gallery') ? 'custom-sidebar-submenu-active' : '' }}
                    ">
                        <a href="{{ route('gallery.index') }}"><i class="fa-regular fa-circle sidebar-li"></i> Photo Gallery</a>
                    </li>
                    @endif

                    @if(Auth::user()->can('role-list') || Auth::user()->can('role-create') ||
                    Auth::user()->can('role-edit') || Auth::user()->can('role-delete'))
                    <li class="ms-2
                    {{ request()->is('roles') ? 'custom-sidebar-submenu-active' : '' }}
                    {{ request()->is('roles/create') ? 'custom-sidebar-submenu-active' : '' }}
                    {{ request()->is('roles/*/edit') ? 'custom-sidebar-submenu-active' : '' }}
                    ">
                        <a href="{{ route('roles.index') }}"><i class="fa-regular fa-circle sidebar-li"></i> Role </a>
                    </li>
                    @endif

                    @if(Auth::user()->can('user-list'))
                    <li class="ms-2
                    {{ request()->is('users') ? 'custom-sidebar-submenu-active' : '' }}
                    ">
                        <a href="{{ route('users.index') }}"><i class="fa-regular fa-circle sidebar-li"></i> User </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
        </div>

        @endif
    </ul>
</nav>
