<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
    <a href="#" class="brand-link text-center">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                @if(auth()->user()->user_role == "manager" || auth()->user()->user_role == "employee"|| auth()->user()->user_role == "accountant")
                <a href="{{ route('profile.'.$user->user_role, $user->employee_id ) }}" class="d-block">{{ ucfirst($user->user_role) }}</a>
                @endif
                @if(auth()->user()->user_role == "client")
                    <a href="{{ route('profile.edit.'.$user->user_role) }}" class="d-block">{{ ucfirst($user->user_role) }}</a>
                @endif
                    @if(auth()->user()->user_role == "admin")
                    <a href="#" class="d-block">Admin</a>
                @endif
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent text-sm" data-widget='treeview' role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('dashboard.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(auth()->user()->user_role == "client")
                    <li class="nav-item">
                        <a href="{{ route('subscription.client') }}" class="nav-link">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>
                                My Membership
                                @php
                                $client = \App\Models\Subscription::find(auth()->user()->client_id);
                                @endphp
                                @if((\Carbon\Carbon::now()->toDateString() ) > (\Carbon\Carbon::createFromTimeStamp(strtotime($client->next_payment_date))->format('Y-m-d')))
                                <span class="right badge badge-danger">DUE</span>
                                @endif
                                @if((\Carbon\Carbon::now()->toDateString() ) < (\Carbon\Carbon::createFromTimeStamp(strtotime($client->next_payment_date))->format('Y-m-d')))
                                <span class="right badge badge-success">PAID</span>
                                @endif
                            </p>
                        </a>
                    </li>
                @endif
                @admin()
                    <!-- User-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('user.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-users nav-icon"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>
                    <!-- Client-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('client.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-user friend nav-icon"></i>
                            <p>
                                Client
                            </p>
                        </a>
                    </li>
                    <!-- Setting-->
                    <li class="nav-item has-treeview">
                    <a href="{{ route('setting.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-cogs nav-icon"></i>
                        <p>
                            Setting
                        </p>
                    </a>
                </li>
                @endadmin


                @if(auth()->user()->user_role == "manager" || auth()->user()->user_role == "client")
                <!-- Employee-->
                <li class="nav-item has-treeview">
                    <a href="{{ route('employee.list.' . auth()->user()->user_role) }}" class="nav-link">
                        <i class="fas fa-user nav-icon"></i>
                        <p>
                            Employee
                        </p>
                    </a>
                </li>
                @endif

                @if(auth()->user()->user_role == "admin" || auth()->user()->user_role == "client")
                    <!-- Department-->
                    <li class="nav-item">
                        <a href="{{ route('department.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-building nav-icon"></i>
                            <p>
                                Department
                            </p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->user_role == "client" || auth()->user()->user_role == "manager")
                <!-- Product-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('product.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-boxes nav-icon"></i>
                            <p>
                                Product
                            </p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->user_role == "client")
                    <!-- Categories -->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('category.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-sitemap nav-icon"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-coins nav-icon"></i>
                                <p>
                                    Accounts & Finance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('ledger.index.'.auth()->user()->user_role ) }}" class="nav-link">
                                        <i class="fa fa-chart-line nav-icon"></i>
                                        <p>Ledger</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('balanceSheet.index.' . auth()->user()->user_role) }}" class="nav-link">
                                        <i class="fa fa-book nav-icon"></i>
                                        <p>Balance Sheet</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('incomeStatement.index.' . auth()->user()->user_role) }}" class="nav-link">
                                        <i class="fa fa-file-invoice-dollar nav-icon"></i>
                                        <p>Income Statement</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                @endif

                @if(auth()->user()->user_role == "client" || auth()->user()->user_role == "manager")
                    <!-- Sale-->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="fas fa-dolly nav-icon"></i>
                            <p>
                                Sale
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('sale.overview.' . auth()->user()->user_role) }}" class="nav-link">
                                    <i class="fa fa-chart-pie nav-icon"></i>
                                    <p>Overview Sale</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sale.list.' . auth()->user()->user_role) }}" class="nav-link">
                                    <i class="fa fa-eye nav-icon"></i>
                                    <p>View Sale</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Purchase-->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="fas fa-truck-loading nav-icon"></i>
                            <p> Purchase
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('purchase.overview.' . auth()->user()->user_role) }}" class="nav-link">
                                    <i class="fa fa-chart-pie nav-icon"></i>
                                    <p>Overview Purchase</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('purchase.list.' . auth()->user()->user_role) }}" class="nav-link">
                                    <i class="fa fa-eye nav-icon"></i>
                                    <p>View Purchase</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Quotation-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('quotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                            <p>
                                Quotation
                            </p>
                        </a>
                    </li>
                    <!-- Invoice-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('invoice.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-receipt nav-icon"></i>
                            <p>
                                Invoice
                            </p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->user_role == "accountant")
                <!-- Sale-->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="fas fa-coins nav-icon"></i>
                            <p>
                                Accounts & Finance
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ledger.index.'.auth()->user()->user_role ) }}" class="nav-link">
                                    <i class="fa fa-chart-line nav-icon"></i>
                                    <p>Ledger</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('balanceSheet.index.' . auth()->user()->user_role) }}" class="nav-link">
                                    <i class="fa fa-book nav-icon"></i>
                                    <p>Balance Sheet</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('incomeStatement.index.' . auth()->user()->user_role) }}" class="nav-link">
                                    <i class="fa fa-file-invoice-dollar nav-icon"></i>
                                    <p>Income Statement</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{ route('sale.index.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-dolly nav-icon"></i>
                            <p>
                                Sale
                            </p>
                        </a>
                    </li>
                    <!-- Purchase-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('purchase.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-truck-loading nav-icon"></i>
                            <p>
                                Purchase
                            </p>
                        </a>
                    </li>
                    <!-- Quotation-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('quotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                            <p>
                                Quotation
                            </p>
                        </a>
                    </li>
                    <!-- Invoice-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('invoice.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-receipt nav-icon"></i>
                            <p>
                                Invoice
                            </p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->user_role == "client")
                    <!-- Vendor-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('vendor.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-landmark nav-icon"></i>
                            <p>
                                Vendor
                            </p>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->user_role == "employee")
                    <!-- Sale-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('sale.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-dolly nav-icon"></i>
                            <p>
                                Sale
                            </p>
                        </a>
                    </li>
                    <!-- Purchase-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('purchase.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-truck-loading nav-icon"></i>
                            <p> Purchase
                            </p>
                        </a>
                    </li>
                    <!-- Quotation-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('quotation.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-file-invoice-dollar nav-icon"></i>
                            <p>
                                Quotation
                            </p>
                        </a>
                    </li>
                    <!-- Invoice-->
                    <li class="nav-item has-treeview">
                        <a href="{{ route('invoice.list.' . auth()->user()->user_role) }}" class="nav-link">
                            <i class="fas fa-receipt nav-icon"></i>
                            <p> Invoices
                            </p>
                        </a>
                    </li>
                @endif


                <li class="nav-item mt-3 mb-3" style="border-top:1px solid #4f5962;"></li>

                <li class="nav-item mb-5">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
