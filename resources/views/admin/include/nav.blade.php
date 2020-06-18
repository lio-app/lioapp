<div class="site-sidebar">
	<div class="custom-scroll custom-scroll-light">
		<ul class="sidebar-menu">
			<li class="menu-title">@lang('admin.include.dashboard')</li>
			<li>
				<a href="{{ route('admin.home') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-anchor"></i></span>
					<span class="s-text">@lang('admin.include.dashboard')</span>
				</a>
			</li>
			<li class="menu-title">@lang('admin.include.users')</li>
			<li>
				<a href="{{ route('admin.user.index') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-anchor"></i></span>
					<span class="s-text">@lang('admin.include.list_users')</span>
				</a>
			</li>

			<!-- <li class="menu-title">@lang('admin.include.withDraw_details')</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">@lang('admin.include.transactions')</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.pendingwithdraw') }}">@lang('admin.include.pending_transaction')</a></li>
					<li><a href="{{ route('admin.allwithdraw') }}">@lang('admin.include.all_transaction')</a></li>

				</ul>
			</li> -->

			<!-- <li class="menu-title">@lang('admin.include.kyc_doc')</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">@lang('admin.include.doc')</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.document.index') }}">@lang('admin.include.list_doc')</a></li>
					<li><a href="{{ route('admin.document.create') }}">@lang('admin.include.add_doc')</a></li>
				</ul>
			</li> -->


			<!-- <li class="menu-title">@lang('admin.History')</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">@lang('admin.include.transactions')</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.history') }}">@lang('admin.include.list_history')</a></li>
					<li><a href="{{ route('admin.fiatHistory') }}">@lang('admin.include.fiat_history')</a></li>

				</ul>
			</li> -->

			<!-- <li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-layout-tab"></i></span>
					<span class="s-text">@lang('admin.include.promocodes')</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.promocode.index') }}">@lang('admin.include.list_promocodes')</a></li>
					<li><a href="{{ route('admin.promocode.create') }}">
					@lang('admin.include.add_new_promocode')</a></li>
				</ul>
			</li> -->

			<!-- <li class="menu-title">@lang('admin.include.payment_details')</li>
			<li>
				<a href="{{ route('admin.settings.payment') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-money"></i></span>
					<span class="s-text">@lang('admin.include.payment_setting')</span>
				</a>
			</li> -->
			<li class="menu-title">@lang('admin.include.setting')</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">@lang('admin.include.setting')</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.settings.index') }}"> @lang('admin.include.site_setting') </a></li>

				</ul>
			</li>

			<li class="menu-title">@lang('admin.include.news')</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">@lang('admin.include.news')</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.news.index') }}"> @lang('admin.include.lstnews') </a></li>

				</ul>
			</li>

			<li class="menu-title">Coins</li>

			<li class="with-sub">
				<a href="#" class="waves-effect  waves-light">
					<span class="s-caret"><i class="fa fa-angle-down"></i></span>
					<span class="s-icon"><i class="ti-view-grid"></i></span>
					<span class="s-text">Coin Type</span>
				</a>
				<ul>
					<li><a href="{{ route('admin.cointype.index') }}">List Coin Type</a></li>
					<li><a href="{{ route('admin.cointype.create') }}">Add Coin Type</a></li>
				</ul>
			</li>


			<li class="menu-title">Currency</li>
			<li>
				<a href="{{ route('admin.currency.index') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-money"></i></span>
					<span class="s-text">Currency</span>
				</a>
			</li>


			<li class="menu-title">@lang('admin.include.others')</li>
			<!-- <li>
				<a href="{{ route('admin.privacy') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-help"></i></span>
					<span class="s-text">@lang('admin.include.privacy_policy')</span>
				</a>
			</li> -->
			<li>
				<a href="{{ route('admin.terms') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-help"></i></span>
					<span class="s-text">@lang('admin.include.terms')</span>
				</a>
			</li>
			<!-- <li>
				<a href="{{route('admin.translation') }}" class="waves-effect waves-light">
					<span class="s-icon"><i class="ti-smallcap"></i></span>
					<span class="s-text">@lang('admin.include.translations')</span>
				</a>
			</li> -->


			<li class="menu-title">@lang('admin.include.acc')</li>
			<li>
				<a href="{{ route('admin.profile') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-user"></i></span>
					<span class="s-text">@lang('admin.include.acc_setting')</span>
				</a>
			</li>
			<li>
				<a href="{{ route('admin.password') }}" class="waves-effect  waves-light">
					<span class="s-icon"><i class="ti-exchange-vertical"></i></span>
					<span class="s-text">@lang('user.profiles.change_password')</span>
				</a>
			</li>

			<li class="compact-hide">


				<a href="{{ route('logout') }}"
				onclick="event.preventDefault();
				document.getElementById('logout-form').submit();">
				<span class="s-icon"><i class="ti-power-off"></i></span>@lang('user.profiles.logout')
			</a>

			<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>


		</li>

	</ul>
</div>
</div>
