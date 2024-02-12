<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="{{ route('home') }}" class="simple-text logo-mini"><img src="{{ asset('favicon.png') }}"></a>
            <a href="{{ route('home') }}" class="simple-text logo-normal">{{ _('BILLING') }}</a>
        </div>
        <ul class="nav">

            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ _('Dashboard') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'clients') class="active " @endif>
                <a href="{{ route('clients') }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ _('Clients') }}</p>
                </a>
            </li>
            {{-- <li>
                <a data-toggle="collapse" href="#navbar-clients" aria-expanded="false">
                    <i class="tim-icons icon-single-02"></i>
                    <span class="nav-link-text">{{ __('Clients') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="navbar-clients">
                    <ul class="nav pl-4">

                        <li @if ($pageSlug == 'clients/create') class="active " @endif>
                            <a href="{{ route('clients-create') }}">
                                <i class="tim-icons icon-simple-add"></i>
                                <p>{{ _('New') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li> --}}

            <li>
                <a data-toggle="collapse" href="#navbar-accounts" aria-expanded="false">
                    <i class="tim-icons icon-bank"></i>
                    <span class="nav-link-text">{{ __('Accounts') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="navbar-accounts">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'accounts') class="active " @endif>
                            <a href="{{ route('accounts') }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ _('List') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'accounts/create') class="active " @endif>
                            <a href="{{ route('accounts-create') }}">
                                <i class="tim-icons icon-simple-add"></i>
                                <p>{{ _('New') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li @if ($pageSlug == 'quotations') class="active " @endif>
                <a href="{{ route('quotations') }}">
                    <i class="tim-icons icon-notes"></i>
                    <p>{{ _('Quotations') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'invoices') class="active " @endif>
                <a href="{{ route('invoices') }}">
                    <i class="tim-icons icon-paper"></i>
                    <p>{{ _('Invoices') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#navbar-messages" aria-expanded="false">
                    <i class="tim-icons icon-chat-33"></i>
                    <span class="nav-link-text">{{ __('Messages') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="navbar-messages">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'messages') class="active " @endif>
                            <a href="{{ route('messages') }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ _('List') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'messages/create') class="active " @endif>
                            <a href="{{ route('messages-create') }}">
                                <i class="tim-icons icon-email-85"></i>
                                <p>{{ _('Create Message') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li @if ($pageSlug == 'products') class="active " @endif>
                <a href="{{ route('products') }}">
                    <i class="tim-icons icon-bag-16"></i>
                    <p>{{ _('Products') }}</p>
                </a>
            </li>

            <li @if ($pageSlug == 'domains') class="active " @endif>
                <a href="{{ route('domains') }}">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ _('Domains') }}</p>
                </a>
            </li>

            <li @if ($pageSlug == 'setup') class="active " @endif>
                <a href="{{ route('setup') }}">
                    <i class="tim-icons icon-settings"></i>
                    <p>{{ _('Setup') }}</p>
                </a>
            </li>

        </ul>
    </div>
</div>
