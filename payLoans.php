
<?php

if (isset($_POST['LonCler'])) {

    $lid2 = $mysqli->real_escape_string(filter_var($_POST['lid2'], FILTER_SANITIZE_NUMBER_INT));
    $username = $mysqli->real_escape_string(htmlspecialchars($_POST['username']));
    $date = $mysqli->real_escape_string(htmlspecialchars($_POST['date']));
    $phone = $mysqli->real_escape_string(filter_var($_POST['phone'], FILTER_SANITIZE_NUMBER_INT));
    $uid3 = $mysqli->real_escape_string(filter_var($_POST['uid3'], FILTER_SANITIZE_NUMBER_INT));
    $totlon = $mysqli->real_escape_string(filter_var($_POST['totlon'], FILTER_SANITIZE_NUMBER_INT));
    $clerAmt = $mysqli->real_escape_string(filter_var($_POST['clerAmt'], FILTER_SANITIZE_NUMBER_INT));
    $balancerem = $mysqli->real_escape_string(filter_var($totlon - $clerAmt, FILTER_SANITIZE_NUMBER_INT));
    $ranCode = $mysqli->real_escape_string(filter_var($_POST['ranCode'], FILTER_SANITIZE_NUMBER_INT));
    $adminidcl = $mysqli->real_escape_string(filter_var($_SESSION['id_admin'], FILTER_SANITIZE_NUMBER_INT));
    if ($clerAmt > $totlon) {
        die("<script>alert('Amount entered (" . number_format($clerAmt) . ") is larger than the loan amount (" . number_format($totlon) . ") ')
            document.location.href = 'active members.php'
            </script>");
    }



    // loans api
    $message = "Thank you our dear customer, $username  for depositing $clerAmt . the total loan was $totlon and  the balance now is $balancerem";

    $message = "Dear customer, $username, we appreciate your prompt loan payment of $clerAmt with payment Id ?><h1> $ranCode</h1> <?php . The total loan amount was $totlon, and the remaining balance is now $balancerem. Thank you for your cooperation with ?><h1>Openbrook</h1> <?php ";


    $url = 'https://www.easypay.co.ug/api/';
    $payload = array(
        'username' => 'e8b0e1195c51181a',
        'password' => 'c9e6f5c21c43f97a',
        'action' => 'Paying Back Loans',
        'clientName' =>  $username  ,   
        'data' =>  $date  , 
        'phone' => $phone ,
        'randomCode' => $ranCode ,
        'totalloan' => $totlon ,
        'clearAmount' => $clerAmt ,        
        'balance' => $balancerem,
        'currency' => 'UGX',
        'reference' => $ranCode,
        'reason' => 'Pay Loans ',
        'message' =>  $message

    );



     //open connection 
 $ch = curl_init(); 
  
 //set the url, number of POST vars, POST data 
 curl_setopt($ch,CURLOPT_URL, $url); 
 curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($payload)); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,15); 
 curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
 //execute post 
 $result = curl_exec($ch); 
  
 //close connection 
 curl_close($ch); 
 print_r(json_decode($result)); 

    // if($balancerem <= 0) {
    //     $balancerem = $mysqli->real_escape_string(filter_var(0,FILTER_SANITIZE_NUMBER_INT));
    // }
    // $dateF = $mysqli->real_escape_string(htmlspecialchars(date("d")."-".date("M")."-".date("Y")));
    // $sqlCler = $mysqli->prepare("INSERT INTO loan_balances(user_id,loan_id,total_loan_amount,amount_received,balance_remaining,dateNormal,date_format,random_code,adminid) VALUES (?,?,?,?,?,?,?,?,?)");
    // $sqlCler->bind_param("iiiiissii",$uid3,$lid2,$totlon,$clerAmt,$balancerem,$date,$dateF,$ranCode,$adminidcl);
    // if(!$sqlCler->execute()) {
    //     die("Error clearing loan");
    // }
    // else {
    //     printf("<script>alert('Loan clearence of (".number_format($clerAmt).") successfully received, balances: (".number_format($balancerem).") ')
    //         document.location.href = 'approved_loans.php'
    //         </script>");
    // }
}
?>