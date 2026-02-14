<?php
session_start();
if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}

if (isset($_GET['add'])) {
  $wineId = intval($_GET['add']);
  require_once('../../database/db_connect.php');
  $stmt = $conn->prepare("SELECT * FROM wines WHERE wineId = ?");
if (!$stmt) {
    echo "Database error (prepare failed).";
    exit;
}
$stmt->bind_param("i", $wineId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Wine not found.";
    exit;
}

$wine = $result->fetch_assoc();
  $addMessage = '';
  $quantity = 1;
    if ($quantity < 1) $quantity = 1;
    if ($quantity > $wine['stock']) {
        $addMessage = "Only {$wine['stock']} in stock. You tried to add {$quantity}.";
    } else {

    if (isset($_SESSION['basket'][$wineId])) {
        $_SESSION['basket'][$wineId] += $quantity;
    } else {
        $_SESSION['basket'][$wineId] = $quantity;
    }
    

    $addMessage = "Added {$quantity} × {$wine['wineName']} to your basket.";
    
    }
    echo "<script>
            alert(".json_encode($addMessage).");
          </script>";


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rosé Wines | Wine Exchange</title>
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
      <a href="contact-us.php">Contact</a>
    </div>
    
        <div class="navbar-right">
          <input type="text" placeholder="Search">
          <a href="log-in.php">Login</a>
          <a href="signup.php">Sign up</a>
          <a href="account.php">Account</a>
          <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
          </button>
        </div>
      </div>

    <div class="cards-cycle">
        <div class="wines-list">
            <div class="wine">
                <img src="../../images/roseWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">ROSÉ WINE</div>
                    <div class="name">ROCK ANGEL ROSÉ</div>
                    <div class="description">Rock Angel Rosé is a stylish, premium rosé from Château d’Esclans in Provence, known for its fuller body, refined minerality, and delicate notes of peach, citrus, and soft red berries. Made with high-quality Grenache and Rolle, it offers more depth and structure than typical rosé, making it a favourite for those who enjoy a drier, more sophisticated style.</div>
                    <div class="price">Price: £35</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=9"><button class="winebutton">SEE MORE INFO</button></a>
                        <a href="roséWines.php?add=9"><button class="winebutton">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/roseWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">ROSÉ WINE</div>
                    <div class="name">CLOS MIREILLE</div>
                    <div class="description">Clos Mireille Rosé from Domaines Ott is an elegant, coastal Provençal rosé prized for its purity, finesse, and saline minerality influenced by its seaside vineyards. With subtle flavors of white peach, citrus blossom, and delicate red fruit, it demonstrates the luxurious, restrained style that has made Ott one of the most respected names in rosé.</div>
                    <div class="price">Price: £48</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=10"><button class="winebutton">SEE MORE INFO</button></a>
                        <a href="roséWines.php?add=10"><button class="winebutton">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/roseWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">ROSÉ WINE</div>
                    <div class="name">WHISPERING ANGEL</div>
                    <div class="description">Whispering Angel Rosé is a globally famous Provence rosé known for its effortless drinkability and clean, crisp profile. Offering gentle notes of strawberry, peach, and citrus with a soft, dry finish, it delivers a polished and approachable style that helped redefine rosé as a modern premium wine category.</div>
                    <div class="price">Price: £99</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=11"><button class="winebutton">SEE MORE INFO</button></a>
                        <a href="roséWines.php?add=11"><button class="winebutton">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/roseWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">ROSÉ WINE</div>
                    <div class="name">CHÂTEAU MINUTY</div>
                    <div class="description">Château Minuty Rosé represents classic high-quality Provence winemaking, admired for its bright acidity, fresh red-berry character, and elegant, aromatic style. Produced from hand-harvested Grenache, Cinsault, and Syrah, Minuty delivers a refined, refreshing rosé that balances delicacy with vibrant Mediterranean charm.</div>
                    <div class="price">Price: £33</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=12"><button class="winebutton">SEE MORE INFO</button></a>
                        <a href="roséWines.php?add=12"><button class="winebutton">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
        </div>
        <style>
                .cards-cycle .wines-list .wine .name {
                    color: #E4C4C7;
                }
        </style>
        <div class="cover">
            <div class="wine">
                <img src="../../images/Mireille.jpg">
                <div class="information">
                    <div class="name">Clos Mireille</div>
                    <div class="description">Iconic coastal provençal rosé</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/whisper.avif">
                <div class="information">
                    <div class="name">Whispering Angel</div>
                    <div class="description">Famous modern style provence rosé</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Minuty.webp">
                <div class="information">
                    <div class="name">Château Minuty</div>
                    <div class="description">Vibrant renowned provence rosé</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/angel.avif">
                <div class="information">
                    <div class="name">Rock Angel Rosé</div>
                    <div class="description">Premium provence rosé with depth</div>
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
        <li><a href="contact-us.php">Contact</a></li>
      </ul>
      <a href="contact-us.php" class="footer-button">Contact Us</a>
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
