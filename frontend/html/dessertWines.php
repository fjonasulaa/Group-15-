<?php
session_start();
if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}

if (isset($_GET['add'])) {
    $wineId = intval($_GET['add']);

    if (!isset($_SESSION['basket'][$wineId])) {
        $_SESSION['basket'][$wineId] = 1;
    } else {
        $_SESSION['basket'][$wineId]++;
    }

    header("Location: dessertWines.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dessert Wines | Wine Exchange</title>
    <link rel="icon" type="image/x-icon" href="../../images/icon.png">
    <link rel="stylesheet" href="../css/styles.css" />
</head>

<body>
    <div class="navbar">
        <img src="../../images/icon.png" alt="Wine Exchange Logo">
        <div class="navbar-links">
          <a href="index.html">Home</a>
          <a href="about.html">About Us</a>
          <a href="wines.html">Wines</a>
          <a href="basket.php">Basket</a>
          <a href="contact-us.html">Contact</a>
        </div>
    
        <div class="navbar-right">
          <form method= "POST" action = "search.php">
            <input type="text" name="search" placeholder="Search">

            <input type= "hidden" name= "submitted" value= "true"/>
          </form>
          <a href="login.html">Login</a>
          <a href="signup.html">Sign up</a>
          <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
          </button>
        </div>
      </div>

    <div class="cards-cycle">
        <div class="wines-list">
            <div class="wine">
                <img src="../../images/dessertWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">DESSERT WINE</div>
                    <div class="name">ROYAL TOKAJI</div>
                    <div class="description">Royal Tokaji Essencia is one of the rarest and most luxurious sweet wines in the world, made only in exceptional years from the free-run nectar of botrytised grapes in Hungary’s historic Tokaj region. With extraordinary concentration, incredibly high natural sugar, and astonishing aging potential, Essencia is prized for its richness, purity, and rarity, making it a true collector’s treasure.</div>
                    <div class="price">Price: £390</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=13"><button class="button">SEE MORE INFO</button></a>
                        <a href="dessertWines.php?add=13"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/dessertWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">DESSERT WINE</div>
                    <div class="name">CONSTANTIA</div>
                    <div class="description">Constantia Vin de Constance is a legendary South African dessert wine with centuries of prestige, famed for its luscious texture, vibrant acidity, and aromas of apricot, citrus peel, honey, and spice. Produced from naturally raisined Muscat de Frontignan grapes, it offers a balanced, expressive sweetness that made it a favorite of European nobility and continues to rank among the world’s greatest sweet wines.</div>
                    <div class="price">Price: £115</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=14"><button class="button">SEE MORE INFO</button></a>
                        <a href="dessertWines.php?add=14"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/dessertWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">DESSERT WINE</div>
                    <div class="name">AVIGNONESI OCCHIO</div>
                    <div class="description">Avignonesi Occhio di Pernice is a rare, opulent Vin Santo di Montepulciano crafted from Sangiovese grapes slowly air-dried and aged for years in small casks, resulting in exceptional richness and depth. Intensely flavored with notes of dried fruit, caramel, spice, and roasted nuts, it is considered one of Italy’s most prestigious sweet wines, celebrated for its complexity and long-lived elegance.</div>
                    <div class="price">Price: £237</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=15"><button class="button">SEE MORE INFO</button></a>
                        <a href="dessertWines.php?add=15"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/dessertWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">DESSERT WINE</div>
                    <div class="name">CHÂTEAU d'YQUEM</div>
                    <div class="description">Château d’Yquem 1811 is one of the most iconic and historically significant dessert wines ever produced, revered for its incredible longevity, concentration, and the near-mythic status of this particular vintage. With its extraordinary balance of sweetness, acidity, and botrytised intensity, it has become a symbol of ultimate luxury in wine collecting, commanding some of the highest prices ever paid for a sweet wine.</div>
                    <div class="price">Price: £12000</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=16"><button class="button">SEE MORE INFO</button></a>
                        <a href="dessertWines.php?add=16"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
        </div>
        <style>
                .cards-cycle .wines-list .wine .name {
                    color: #E8C2AB;
                }
            </style>
        <div class="cover">
            <div class="wine">
                <img src="../../images/Constance.jpg">
                <div class="information">
                    <div class="name">Constantia Vin de Constance</div>
                    <div class="description">Historic south african wine</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Avignonesi.jpg">
                <div class="information">
                    <div class="name">Avignonesi Occhio di Pernice</div>
                    <div class="description">Iconic italian vin santo</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Yquem.jpg">
                <div class="information">
                    <div class="name">Château d’Yquem 1811 Wine</div>
                    <div class="description">Legendary sauternes vintage</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Tokaji.jpeg">
                <div class="information">
                    <div class="name">Royal Tokaji Essencia</div>
                    <div class="description">Ultra-rare hungarian nectar</div>
                </div>
            </div>
        </div>
        <div class="arrow">
            <button id='next' class="next">></button>
        </div>
    </div>

    <script>
        let nextDom = document.querySelector('#next');
        let cycleDom = document.querySelector('.cards-cycle');
        let wineListDom = document.querySelector('.cards-cycle .wines-list');
        let coverDom = document.querySelector('.cards-cycle .cover');

        nextDom.onclick = function () {
            showSlider('next');
        }

        let timeRunning = 3000;
        let runTimeOut;

        function showSlider(type) {

            let wineSlider = document.querySelectorAll('.cards-cycle .wines-list .wine');
            let wineCover = document.querySelectorAll('.cards-cycle .cover .wine');

            if (type === 'next') {
                wineListDom.appendChild(wineSlider[0]);
                coverDom.appendChild(wineCover[0]);
                coverDom.classList.add('next');
            }

            clearTimeout(runTimeOut);
            runTimeOut = setTimeout(() => {
                coverDom.classList.remove('next');
            }, timeRunning)
        }

        const button = document.getElementById("dark-mode");

        if (localStorage.getItem("dark_mode") === "on") {
            document.documentElement.classList.add("darkmode");
        }

        button.addEventListener("click", () => {
            document.documentElement.classList.toggle("darkmode");

            if (document.documentElement.classList.contains("darkmode")) {
                localStorage.setItem("dark_mode", "on");
            } else {
                localStorage.setItem("dark_mode", "off");
            }
        });
    </script>

</body>
<style>
/* Footer styling */
.footer {
  background-color: #f4f4f4;
  padding: 30px 10%;
  color: #333;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
}

.footer-section {
  flex: 1 1 250px;
  margin: 10px;
}

.footer-section h3 {
  margin-bottom: 10px;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin: 5px 0;
}

.footer-links a {
  text-decoration: none;
  color: inherit;
}

.footer-links a:hover {
  text-decoration: underline;
}

/* Contact button */
.footer-button {
  display: inline-block;
  margin-top: 10px;
  padding: 8px 15px;
  background-color: #4CAF50;
  color: white;
  border-radius: 4px;
  text-decoration: none;
}

.footer-button:hover {
  opacity: 0.9;
}

/* Footer bottom bar */
.footer-bottom {
  text-align: center;
  margin-top: 20px;
  padding-top: 10px;
  border-top: 1px solid #ccc;
  font-size: 14px;
}

/* DARK MODE SUPPORT */
.darkmode .footer {
  background-color: #1e1e1e;
  color: #eee;
}

.darkmode .footer-bottom {
  border-top: 1px solid #555;
}

.darkmode .footer-links a {
  color: #ddd;
}
</style>

<footer class="footer">
  <div class="footer-container">

    <div class="footer-section">
      <h3>Wine Exchange</h3>
      <p>123 Vineyard Lane<br>London, UK</p>
      <p>Phone: +44 1234 567890</p>
      <p>Email: <a href="mailto:contactwinexchange@gmail.com">contactwinexchange@gmail.com</a></p>
      <p>Open: Mon–Fri, 9am–6pm</p>
    </div>

    <div class="footer-section">
      <h3>Quick Links</h3>
      <ul class="footer-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="wines.html">Wines</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <a href="contact.php" class="footer-button">Contact Us</a>
    </div>

    <div class="footer-section">
      <h3>Follow Us</h3>
      <ul class="footer-links">
        <li><a href="#">Instagram</a></li>
        <li><a href="#">Facebook</a></li>
        <li><a href="#">Twitter</a></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    © 2024 Wine Exchange. All rights reserved.
  </div>
</footer>
