<?php
session_start();

if (isset($_POST['submit'])) {
    $product = $_POST['stock'];
    $initial = $_POST['price'];
    $volatil = $_POST['volatility'];
    // Store the new entry in the session
    $_SESSION['stocks'][] = ['name' => $product, 'initialPrice' => $initial, 'volatility' => $volatil];
}

function monteCarloSimulation($initialPrice, $volatility, $days, $simulations)
{
    $dailyReturn = exp((0.02 * $volatility) - 0.5 * $volatility * $volatility);
    $pricePaths = [];

    for ($i = 0; $i < $simulations; $i++) {
        $price = $initialPrice;

        for ($day = 0; $day < $days; $day++) {
            $price *= $dailyReturn * exp($volatility * sqrt(1 / 252) * rand(-100, 100) / 100.0);
        }

        $pricePaths[] = $price;
    }

    return $pricePaths;
}

// Check if the "CLEAR" button is clicked
if (isset($_POST['clear'])) {
    // Clear the session data for stocks
    unset($_SESSION['stocks']);
}

// Retrieve the stocks from the session
$stocks = isset($_SESSION['stocks']) ? $_SESSION['stocks'] : [];

// Simulate and store results for each stock
$stockPrices = [];
foreach ($stocks as $stock) {
    $stockPrices[$stock['name']] = monteCarloSimulation($stock['initialPrice'], $stock['volatility'], 30, 100);
}

?>


<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="pico.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Document</title>
    <style>
        .minimalistic-border {
            width: auto;
            height: 100%;
            padding: 20px;
            padding-left: 0;
            padding-right: 0;
            border: 2px solid #333;
            box-sizing: border-box;
            text-align: center;
            border-radius: 5px;
        }

        p {
            margin: 0;
            font-family: 'Arial', sans-serif;
            color: white;
        }
        .icon-button {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .icon-button i {
            margin-right: 5px;
        }

        .icon-button.delete {
            background-color: #dc3545;
        }

        .icon-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body style="background-color: #1c1e21;">
  <main style="padding: 30px">
  <h1>Price Future Prediction</h1>
    <div class="grid" style="padding-left: 40px; padding-right: 40px;">
        <div>
        <form method="post">
        <label for="html">PRODUCT NAME</label><br>
        <input type="text" name="stock" id="" placeholder="SoyBeans" required></div>
        <div><label for="html">INITIAL PRICE</label><br>
        <input type="number" id="price" name="price" min="1" max="100000" placeholder="100" required></div>
        <div><label for="html">Volatility</label><br>
        <input type="number" id="volatility" name="volatility" min="0.0" max="10" placeholder="0.1" step="0.01" required></div>
        <div><label for="html">ACTION</label><br><input type="submit" name="submit" value="SUBMIT"></div>
        </form>
    </div>
  <div class="grid " style="padding-top: -40px;">
                <div class="grid" style="margin-bottom: -140px">
                <div> 
                    <article style="background-color:#1c1e21;padding-top: 20px;">
                        <h5 style="margin: 0; padding: 0;">SORTED STOCK</h5>
                            <div class="grid">
                                <div class="minimalistic-border grid">
                                    <table>
                                        <thead>
                                            <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Higher Stock Price</th>
                                            <th scope="col">Lower Stock Price</th>
                                            <th scope="col">Volatility</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($stockPrices as $stockName => $prices) {
                                                sort($prices);
                                                $lowerStock = $prices[0];
                                                $higherStock = end($prices);

                                            ?>
                                            <tr>
                                            <td><?php  echo $stockName ;?></td>
                                            <td style="color: greenyellow;"><?php  echo $higherStock ;?></td>
                                            <td  style="color: red;"><?php  echo $lowerStock ;?></td>
                                            <td  style="color: #CC54FD;"><?php  echo $stock['volatility'] ;?></td>
                                            </tr>
                                            <tr>
                                        <?php }?>
                                        </tbody>
                                        </table>
                                </div style="width:10px">
                                <div>
                                    <form action="" method="post">
                                    <input type="submit" name="clear" value="CLEAR">
                                    </form>
                                </div>

                            </div>
                            </div>
                        </div>
                    </div>
    </div>
  </div>
  <article style="background-color:#1c1e21;padding-top:50px;">
    <div><i><span><strong>Note:</strong></span>  This project uses a Monte Carlo algorithm to determine the product value. 
        This initiative, which is listed ascending, has a larger goal of assisting small stores in managing their inventory.</i>
    </div>
    <br>
    <div> 
        <p>Leader:</p>
        <kbd>Ivan R. Contrevida</kbd><br><br>
        <p>Members:</p>
        <kbd>Joebert Olvido</kbd><br><br>
        <kbd>Flory John Cartagena</kbd><br><br>
        <kbd>John Alfread Divino</kbd>
    </div>

  </article>
  </main>
</body>
</html>