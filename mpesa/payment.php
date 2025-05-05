<?php
date_default_timezone_set('Africa/Nairobi');

# access token
$consumerKey = 'T2Rt3zLgO20r6pTuWDRSThC1YM311NPN';
$consumerSecret = 'NX6xgyPjQbQ3a1vC';

# define the variables
$BusinessShortCode = '174379';
$Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';

$PartyA = isset($_POST['phone']) ? $_POST['phone'] : '';
$Amount = isset($_POST['amount']) ? $_POST['amount'] : '1';
$AccountReference = 'waste management';
$TransactionDesc = 'Waste collection Payment';
$CallBackURL = 'https://callback.kenova.co';

if ($PartyA && $Amount) {
    $Timestamp = date('YmdHis');
    $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

    $headers = ['Content-Type:application/json; charset=utf8'];
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
    $result = curl_exec($curl);
    $result = json_decode($result);
    $access_token = $result->access_token;
    curl_close($curl);

    $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);

    $curl_post_data = [
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $PartyA,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    ];

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);

    $response_data = json_decode($curl_response, true);
    if (isset($response_data['ResponseCode']) && $response_data['ResponseCode'] === '0') {
        echo '<div class="alert alert-success" role="alert">Payment prompt sent successfully</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">' . $curl_response . '</div>'; // Output the full response for other cases
    }
    curl_close($curl);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-Pesa Payment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding: 20px; }
        .payment-form { max-width: 400px; margin: auto; border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        h2 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-form">
            <h2>Lipa na M-Pesa</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone" required placeholder="2547XXXXXXXX" class="form-control">
                </div>
                <div class="form-group">
                    <label for="amount">Amount:</label>
                    <input type="number" id="amount" name="amount" required placeholder="Amount in KES" class="form-control">
                </div>
                <button type="submit" class="btn btn-success btn-block">Pay</button>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>