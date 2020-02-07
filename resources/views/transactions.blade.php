    @extends('layouts.header')

    @section('content')
    <div class="container-fluid">
            <h3>Transactions</h3>
            <div class="row">

                <div class="col-md-12 m-b-20">

                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>From / To </th>
                                <th>Transaction ID</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $details)
                            <tr>
                             
                                <td>{{$details['address'] }}</td>
                                
                                <td>

                                    @if(Auth::user()->network == 'BTC')

                                    <a href="https://www.blockchain.com/btc/tx/{{$details['txid']}}" target="_blank">{{$details['txid']}}</a>
     

                                    @elseif(Auth::user()->network == 'LTC')
                                    <a href="https://insight.litecore.io/tx/{{$details['txid']}}" target="_blank">{{$details['txid']}}</a>


                                    @elseif(Auth::user()->network == 'ETH')

                                    <a href="https://etherscan.io/tx/{{$details['txid']}}" target="_blank">{{$details['txid']}}</a>

                                    @else

                                    <a href="#" target="_blank">{{$details['txid']}}</a>

                                    @endif



                                </td>
                                <td>{{$details['amount'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
            </div>
    @endsection

    @section('scripts')   
    <script type="text/javascript">
        $(document).ready(function() {
        $('#example').DataTable({
            "iDisplayLength": 5,
            "aLengthMenu": [
                [5, 10,],
                [5, 10,  "All"]
            ]
        });
    });

    </script>
    @endsection
