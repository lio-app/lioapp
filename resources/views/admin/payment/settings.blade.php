@extends('admin.layout.base')

@section('title', 'Payment Settings ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <form action="{{route('admin.settings.payment.store')}}" method="POST">
                {{csrf_field()}}
                <h5>@lang('admin.payment_mode')</h5>
                <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-cc-stripe pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="stripe_secret_key" class="col-form-label">
                                    @lang('admin.card_payments')
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CARD') == 1) checked  @endif  name="CARD" id="stripe_check" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                        <div id="card_field" @if(Setting::get('CARD') == 0) style="display: none;" @endif>
                            <div class="form-group row">
                                <label for="stripe_secret_key" class="col-xs-4 col-form-label">@lang('admin.sk')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('stripe_secret_key', '') }}" name="stripe_secret_key" id="stripe_secret_key"  placeholder="Stripe Secret key">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stripe_publishable_key" class="col-xs-4 col-form-label">@lang('admin.pk')</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('stripe_publishable_key', '') }}" name="stripe_publishable_key" id="stripe_publishable_key"  placeholder="Stripe Publishable key">
                                </div>
                            </div>
                        </div>
                    </blockquote>
                </div>

             <!--    <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-cc-paypal pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="paypal_client_id" class="col-form-label">
                                    Paypal ( Payments)
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('PAYPAL') == 1) checked  @endif  name="PAYPAL" id="paypal_check" onchange="paypalselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                        <div id="paypal_field" @if(Setting::get('PAYPAL') == 0) style="display: none;" @endif>
                            <div class="form-group row">
                                <label for="paypal_client_id" class="col-xs-4 col-form-label">Paypal Client ID</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('paypal_client_id', '') }}" name="paypal_client_id" id="paypal_client_id"  placeholder="Paypal Client ID">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="paypal_secret_key" class="col-xs-4 col-form-label">Paypal Secret Key</label>
                                <div class="col-xs-8">
                                    <input class="form-control" type="text" value="{{Setting::get('paypal_secret_key', '') }}" name="paypal_secret_key" id="paypal_secret_key"  placeholder="Paypal Secret Key">
                                </div>
                            </div>
                        </div>
                    </blockquote>
                </div>
 -->

              <!--   <div class="card card-block card-inverse card-primary">
                    <blockquote class="card-blockquote">
                        <i class="fa fa-3x fa-money pull-right"></i>
                        <div class="form-group row">
                            <div class="col-xs-4">
                                <label for="cash-payments" class="col-form-label">
                                   Cash Payments
                                </label>
                            </div>
                            <div class="col-xs-6">
                                <input @if(Setting::get('CASH') == 1) checked  @endif name="CASH" id="cash-payments" onchange="cardselect()" type="checkbox" class="js-switch" data-color="#43b968">
                            </div>
                        </div>
                    </blockquote>
                </div> -->
                <h5>@lang('admin.include.payment_setting')</h5>

                        <div class="form-group row">
                            <label for="base_price" class="col-xs-4 col-form-label">@lang('user.currency')
                                 ( <strong>{{ Setting::get('currency', '$')  }} </strong>)
                            </label>
                            <div class="col-xs-8">
                                <select name="currency" class="form-control" required>
                                    <option @if(Setting::get('currency') == "$") selected @endif value="$">US Dollar (USD)</option>
                                    <option @if(Setting::get('currency') == "₹") selected @endif value="₹"> Indian Rupee (INR)</option>
                                    <option @if(Setting::get('currency') == "د.ك") selected @endif value="د.ك">Kuwaiti Dinar (KWD)</option>
                                    <option @if(Setting::get('currency') == "د.ب") selected @endif value="د.ب">Bahraini Dinar (BHD)</option>
                                    <option @if(Setting::get('currency') == "﷼") selected @endif value="﷼">Omani Rial (OMR)</option>
                                    <option @if(Setting::get('currency') == "£") selected @endif value="£">British Pound (GBP)</option>
                                    <option @if(Setting::get('currency') == "€") selected @endif value="€">Euro (EUR)</option>
                                    <option @if(Setting::get('currency') == "CHF") selected @endif value="CHF">Swiss Franc (CHF)</option>
                                    <option @if(Setting::get('currency') == "ل.د") selected @endif value="ل.د">Libyan Dinar (LYD)</option>
                                    <option @if(Setting::get('currency') == "B$") selected @endif value="B$">Bruneian Dollar (BND)</option>
                                    <option @if(Setting::get('currency') == "S$") selected @endif value="S$">Singapore Dollar (SGD)</option>
                                    <option @if(Setting::get('currency') == "AU$") selected @endif value="AU$"> Australian Dollar (AUD)</option>
                                </select>
                            </div>
                        </div>

                     <!--    <div class="form-group row">
                            <label for="e_wallet" class="col-xs-4 col-form-label">
                               Enterprises User Fiat Transaction Limit 
                            </label>
                            <div class="col-xs-8">
                                <input class="form-control" type="number" value="{{Setting::get('e_wallet','0') }}" name="e_wallet" id="e_wallet"  placeholder="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="p_wallet" class="col-xs-4 col-form-label">
                               Personal User Fiat Transaction Limit 
                            </label>
                            <div class="col-xs-8">
                                <input class="form-control" type="number" value="{{Setting::get('p_wallet','0') }}" name="p_wallet" id="p_wallet"  placeholder="">
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="referral" class="col-xs-4 col-form-label">
                               @lang('admin.referral')  ( <strong>{{ Setting::get('currency', '$')  }} </strong>)
                            </label>
                            <div class="col-xs-8">
                                <input class="form-control" type="number" value="{{Setting::get('referral','0') }}" name="referral" id="referral"  placeholder="">
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="tax_percentage" class="col-xs-4 col-form-label">@lang('admin.crypto_inc')</label>
                            <div class="col-xs-8">
                                <input class="form-control" type="text" value="{{ Setting::get('increase_percentage', 10) }}" name="increase_percentage" id="increase_percentage" placeholder="increase percentage">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="withdraw_time" class="col-xs-4 col-form-label">@lang('admin.wait_time')</label>
                            <div class="col-xs-8">
                                <input class="form-control" type="text" value="{{ Setting::get('withdraw_time', ' 48 hours') }}" name="withdraw_time" id="withdraw_time" placeholder="Withdraw Process Time">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="withdraw_comission" class="col-xs-4 col-form-label">@lang('admin.withdraw_comission')</label>
                            <div class="col-xs-8">
                                <input class="form-control" type="number" value="{{ Setting::get('withdraw_comission', 10) }}" name="withdraw_comission" id="withdraw_comission" placeholder="Withdraw Commission">
                            </div>
                        </div>

                    </blockquote>
                </div>

                <div class="form-group row">
                    <div class="offset-xs-4 col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block">@lang('admin.update_setting')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function cardselect()
    {
        if($('#stripe_check').is(":checked")) {
            $("#card_field").fadeIn(700);
        } else {
            $("#card_field").fadeOut(700);
        }
    }
    function paypalselect()
    {
        if($('#paypal_check').is(":checked")) {
            $("#paypal_field").fadeIn(700);
        } else {
            $("#paypal_field").fadeOut(700);
        }
    }
</script>
@endsection