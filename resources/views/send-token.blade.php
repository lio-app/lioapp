<html>
<head>

		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
   <!--  <title>{{ Setting::get('site_title') }}</title> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link rel="stylesheet" type="text/css" href="{{asset('landing/css/styles.css')}}">

    <style type="text/css">
    .verify-sec {
        max-width: 550px;
        margin: 100px auto;
        border: 0px;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, .18);
        padding: 20px;
        background-color:#fff;
    }
    body {
        background: rgb(246,246,246);
    }

    .verify-sec h3, p {
        font-weight: normal;
        color: #AFA391;
    }

    .verify-sec h3 {
        margin-bottom:20px;
        text-align: center;
    }

    .verify-sec p {
        margin:3px 0px;  
        line-height:24px;
    }

    .verify-sec ul {
        color:#AFA391;
        margin-top:0px;
        padding-left:15px;
    }

    .verify-guides {
        border-top:1px solid #ffd086;
        margin-top: 20px;
    }

    .email{
        position:relative;
        text-align:center;
        background-color:#fff;
        z-index:1;
    }

    .email span {
        display: table!important;
        margin: auto;
        background: #fff;
        padding: 0 10px;
    }

    .email::before {
        position:absolute;
        content:"";
        height:1px;
        width:60%;
        top:10px;
        left:0;
        margin: 0px auto;
        right: 0;
        background-color:#ffd086;
        z-index:-2;
    }

    .verify-logo img {
        height:58px;
    }

    .verify-logo  {
        text-align:center;
        margin-bottom:20px;
    }
    .m-t-20 {
        margin-top:20px !important;
    }
    </style>


  	<script src="{{ asset('assets/js/jquery.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/web.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/ethereumjs-tx.js')}}"></script>
	<script>
		//var web3 = new Web3(new Web3.providers.HttpProvider('https://ropsten.infura.io/KNNQ7NoZEhNQ5zpDSZzH'));
		 var web3 = new Web3(new Web3.providers.HttpProvider('https://mainnet.infura.io/KNNQ7NoZEhNQ5zpDSZzH'));

		// set token source, destination and amount
		var myAddress = "{{$from}}";
		//jaya var toAddress = "0xaa597b7e8aaffe9f2a187bedb472ef3455957560";
		var toAddress = "{{$to}}";
			
		var amount = web3.toHex(1e16);

		// get transaction count, later will used as nonce
		var count = web3.eth.getTransactionCount(myAddress);
			

		// set your private key here, we'll sign the transaction below
		var privateKey = new EthJS.Buffer.Buffer('{{$key}}', 'hex');
		// Get abi array here https://etherscan.io/address/0x86fa049857e0209aa7d9e616f7eb3b3b78ecfdb0#code
		var abiArray = [{"constant":true,"inputs":[],"name":"name","outputs":[{"name":"","type":"bytes32"}],"payable":false,"type":"function"},{"constant":false,"inputs":[],"name":"stop","outputs":[],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"guy","type":"address"},{"name":"wad","type":"uint256"}],"name":"approve","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"owner_","type":"address"}],"name":"setOwner","outputs":[],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"name":"","type":"uint256"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"src","type":"address"},{"name":"dst","type":"address"},{"name":"wad","type":"uint256"}],"name":"transferFrom","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint256"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"dst","type":"address"},{"name":"wad","type":"uint128"}],"name":"push","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"name_","type":"bytes32"}],"name":"setName","outputs":[],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"wad","type":"uint128"}],"name":"mint","outputs":[],"payable":false,"type":"function"},{"constant":true,"inputs":[{"name":"src","type":"address"}],"name":"balanceOf","outputs":[{"name":"","type":"uint256"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"stopped","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"authority_","type":"address"}],"name":"setAuthority","outputs":[],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"src","type":"address"},{"name":"wad","type":"uint128"}],"name":"pull","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"name":"","type":"address"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"wad","type":"uint128"}],"name":"burn","outputs":[],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"name":"","type":"bytes32"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"dst","type":"address"},{"name":"wad","type":"uint256"}],"name":"transfer","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[],"name":"start","outputs":[],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"authority","outputs":[{"name":"","type":"address"}],"payable":false,"type":"function"},{"constant":true,"inputs":[{"name":"src","type":"address"},{"name":"guy","type":"address"}],"name":"allowance","outputs":[{"name":"","type":"uint256"}],"payable":false,"type":"function"},{"inputs":[{"name":"symbol_","type":"bytes32"}],"payable":false,"type":"constructor"},{"anonymous":true,"inputs":[{"indexed":true,"name":"sig","type":"bytes4"},{"indexed":true,"name":"guy","type":"address"},{"indexed":true,"name":"foo","type":"bytes32"},{"indexed":true,"name":"bar","type":"bytes32"},{"indexed":false,"name":"wad","type":"uint256"},{"indexed":false,"name":"fax","type":"bytes"}],"name":"LogNote","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"authority","type":"address"}],"name":"LogSetAuthority","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"owner","type":"address"}],"name":"LogSetOwner","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"from","type":"address"},{"indexed":true,"name":"to","type":"address"},{"indexed":false,"name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"name":"owner","type":"address"},{"indexed":true,"name":"spender","type":"address"},{"indexed":false,"name":"value","type":"uint256"}],"name":"Approval","type":"event"}];

		var contractAddress = '{{$contract}}'; // CTC
		//var contract = new web3.eth.Contract(abiArray, contractAddress, {from: myAddress});
		var contract = web3.eth.contract(abiArray).at(contractAddress);
			
		var coincount = {{$coin}};
        var dec = {{$decimal}};
		var transferamount = coincount *10**dec; // Last "10" or "dec" represents the Decimals

		var rawTransaction = {"from":myAddress, "gasPrice":web3.toHex({{Setting::get('gas',3)}} * 1e9),"gasLimit":web3.toHex(210000),"to":contractAddress,"value":"0x0","data":contract.transfer.getData(toAddress, transferamount, {from: myAddress}),"nonce":web3.toHex(count)};
			
		var transaction = new EthJS.Tx(rawTransaction);
		transaction.sign(privateKey);
		var id = web3.eth.sendRawTransaction('0x' + transaction.serialize().toString('hex'));
		//console.log(id);
        alert("Your Transaction Hash : "+ id);
        if(id){
            window.location.href = '{{url("/home")}}';

        }

		

	</script>
</head>
<body class="fixed-header " >
    <div class="register-container full-height sm-p-t-30">
        <div class="d-flex justify-content-center flex-column full-height ">
           
          
            <div class="verify-sec text-center">

                <div class="verify-logo">
                    <img src="{{ asset('img/logo.png') }}" alt="logo">
                </div>
                <h3 class="email"><span>Transaction processing</span></h3>
                	<p>Please wait while the transaction is processing, Don't referesh or press back button !</p>
              
            </div>
            
        </div>
    </div>
</body>
</html>

