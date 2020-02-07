@extends('admin.layout.base')

@section('title', 'Site Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
			<h5> @lang('admin.include.site_setting')</h5>

            <form class="form-horizontal" action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">@lang('admin.site_name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('site_title')  }}" name="site_title" required id="site_title" placeholder="Site Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_title" class="col-xs-2 col-form-label">@lang('admin.site_url')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('site_url') }}" name="site_url" required id="site_url" placeholder="@lang('admin.site_url')">
					</div>
				</div>

				<div class="form-group row">
					<label for="site_logo" class="col-xs-2 col-form-label">@lang('admin.site_logo')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_logo')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ img(Setting::get('site_logo', asset('logo-black.png'))) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_logo" class="dropify form-control-file" id="site_logo" aria-describedby="fileHelp">
					</div>
				</div>


				<div class="form-group row">
					<label for="site_icon" class="col-xs-2 col-form-label">@lang('admin.site_icon')</label>
					<div class="col-xs-10">
						@if(Setting::get('site_icon')!='')
	                    <img style="height: 90px; margin-bottom: 15px;" src="{{ img(Setting::get('site_icon')) }}">
	                    @endif
						<input type="file" accept="image/*" name="site_icon" class="dropify form-control-file" id="site_icon" aria-describedby="fileHelp">
					</div>
				</div>

                <div class="form-group row">
                    <label for="tax_percentage" class="col-xs-2 col-form-label">@lang('admin.copyright')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('site_copyright') }}" name="site_copyright" id="site_copyright" placeholder="Site Copyright" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_no" class="col-xs-2 col-form-label">@lang('admin.contact_no')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('contact_no') }}" name="contact_no" id="contact_no" placeholder="@lang('admin.contact_no')" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_email" class="col-xs-2 col-form-label">@lang('admin.contact_email')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="email" value="{{ Setting::get('contact_email') }}" name="contact_email" id="contact_email" placeholder="@lang('admin.contact_email')" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="contact_website" class="col-xs-2 col-form-label">@lang('admin.contact_website')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('contact_website') }}" name="contact_website" id="contact_website" placeholder="@lang('admin.contact_website')" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="currency_value" class="col-xs-2 col-form-label">Currency Value</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('currency_value') }}" name="coin_value" id="currency_value" placeholder="1 COIN = 1 USD" required>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="currency_symbol" class="col-xs-2 col-form-label">Currency Symbol</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ Setting::get('currency_symbol') }}" name="currency_symbol" id="currency_symbol" placeholder="Ex: LIO" required>
                    </div>
                </div>

              
                <!-- <div class="form-group row">
					<label for="stripe_secret_key" class="col-xs-2 col-form-label">@lang('admin.kyc_man') </label>
					<div class="col-xs-10">
						<div class="float-xs-left mr-1"><input @if(Setting::get('kyc_approval') == 1) checked  @endif  name="kyc_approval" type="checkbox" class="js-switch" data-color="#43b968"></div>
					</div>
				</div> -->

               

				<!-- <div class="form-group row">
					<label for="store_link_android" class="col-xs-2 col-form-label">Playstore link</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_android', '')  }}" name="store_link_android"  id="store_link_android" placeholder="Playstore link">
					</div>
				</div>

				<div class="form-group row">
					<label for="store_link_ios" class="col-xs-2 col-form-label">Appstore Link</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Setting::get('store_link_ios', '')  }}" name="store_link_ios"  id="store_link_ios" placeholder="Appstore link">
					</div>
				</div> -->				

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.update_setting')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>
@endsection
