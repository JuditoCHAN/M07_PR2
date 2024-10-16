<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    //DECLARACIÓN DE FUNCIONES ---------------------------------------------------------------------------------
    function setBankAccount(&$bankAccount, $balance, $overdraft, $limit = 0) { //si overdraft es false y no pasan limit como parametro, se asigna 0 por defecto
        $bankAccount["balance"] = $balance;
        $bankAccount["closed"] = false;
        $bankAccount["overdraft"] = $overdraft;
        $bankAccount["limit"] = $limit;
    }

    function outputBalance($bankAccount) {
        echo "My balance: " . $bankAccount["balance"] . "<br>";
    }

    function closeAccount(&$bankAccount) {
        if(!$bankAccount["closed"]) {
            $bankAccount["closed"] = true;
            echo "My account is now closed.<br>";
        } else {
            echo "Error: Account is already closed.<br>";
        }
    }

    function openAccount(&$bankAccount) {
        if($bankAccount["closed"]) {
            $bankAccount["closed"] = false;
            echo "My account is now reopened.<br>";
        } else {
            echo "Error: Account is already open.<br>";
        }

    }

    function performDeposit(&$bankAccount, $amount) {
        echo "Doing transaction deposit (+" . $amount . ") with current balance " . $bankAccount["balance"] . "<br>";
        $bankAccount["balance"] += $amount;
    }

    function performWithdrawal(&$bankAccount, $amount) { //tiene en cuenta el overdraft
        echo "Doing transaction withdrawal (-" . $amount . ") with current balance " . $bankAccount["balance"] . "<br>";
        if(($bankAccount["balance"] - $amount < 0) && !$bankAccount["overdraft"]) {
            echo "Error transaction: Insufficient balance to complete the withdrawal.<br>";
        } else {
            if($bankAccount["overdraft"] && (($bankAccount["balance"] - $amount) < (0 - $bankAccount["limit"]))) {
                echo "Error transaction: Withdrawal exceeds overdraft limit.<br>";
            } else { //en todos los otros casos funciona withdrawal
                $bankAccount["balance"] -= $amount;
            }
        }
    }

    function showUpdatedBalance(&$bankAccount, $depositOrWithdrawal, $amount) {
        echo "My new balance after " . $depositOrWithdrawal . "(" . ($depositOrWithdrawal=="deposit" ? "+" : "-") . $amount . ") with current balance : " . $bankAccount["balance"] . "<br>";
    }


//CUENTA BANCARIA 1 ----------------------------------------------------------------------------------------------
    $bankAccount1 = [];
    setBankAccount($bankAccount1, 400, false); //no hace falta pasar argumento limit
    outputBalance($bankAccount1);

    closeAccount($bankAccount1);
    openAccount($bankAccount1);

    performDeposit($bankAccount1, 150);
    showUpdatedBalance($bankAccount1, "deposit", 150);

    performWithdrawal($bankAccount1, 25);
    showUpdatedBalance($bankAccount1, "withdrawal", 25);

    //Intento de hacer withdrawal de 600, que dará error
    performWithdrawal($bankAccount1, 600);

    outputBalance($bankAccount1);
    closeAccount($bankAccount1);


//CUENTA BANCARIA 2 -----------------------------------------------------------------------------------------------
    echo "<br><br>";
    $bankAccount2 = [];
    setBankAccount($bankAccount2, 200, true, 100); //overdraft con limite de 100
    outputBalance($bankAccount2);
    
    performDeposit($bankAccount2, 100);
    showUpdatedBalance($bankAccount2,"deposit", 100);
    
    performWithdrawal($bankAccount2,300);
    showUpdatedBalance($bankAccount2,"withdrawal", 300);

    performWithdrawal($bankAccount2, 50);
    showUpdatedBalance($bankAccount2, "withdrawal", 50);

    performWithdrawal($bankAccount2, 120);
    outputBalance($bankAccount2);

    performWithdrawal($bankAccount2, 20);
    showUpdatedBalance($bankAccount2,"withdrawal", 20);

    closeAccount($bankAccount2);
    closeAccount($bankAccount2); //imprimirá error al intentar volver a cerrarla
    ?>
</body>
</html>