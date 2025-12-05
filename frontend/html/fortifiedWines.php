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

    header("Location: fortifiedWines.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fortified Wines | Wine Exchange</title>
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
                <img src="../../images/fortifiedWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">FORTIFIED WINE</div>
                    <div class="name">DOW'S 2011</div>
                    <div class="description">Dow’s 2011 Vintage Port is a powerful, structured wine showing dense black fruit, floral lift, and the house’s signature slightly drier edge, giving it impressive definition and refinement. It offers firm tannins and vibrant acidity that promise long life as it continues to develop even greater depth and complexity with age.</div>
                    <div class="price">Price: £195</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=21"><button class="button">SEE MORE INFO</button></a>
                        <a href="fortifiedWines.php?add=21"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/fortifiedWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">FORTIFIED WINE</div>
                    <div class="name">FONSECA 1985</div>
                    <div class="description">Fonseca’s 1985 Vintage Port is a mature, richly layered wine displaying ripe berries, dried fruits, chocolate, and warm spice, all wrapped in the producer’s hallmark opulence and warmth. Its beautifully softened tannins create a smooth, harmonious profile that still retains remarkable presence and expressive character on the palate.</div>
                    <div class="price">Price: £105</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=22"><button class="button">SEE MORE INFO</button></a>
                        <a href="fortifiedWines.php?add=22"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/fortifiedWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">FORTIFIED WINE</div>
                    <div class="name">GRAHAM'S 1994</div>
                    <div class="description">Graham’s 1994 Vintage Port is lush and aromatic, bursting with blackberry, plum, cassis, and cocoa tones, enhanced by the house’s signature richness and velvety sweetness. Its combination of ripe fruit, concentration, and polished structure has cemented its reputation as one of the standout, most collectible Ports of the era.</div>
                    <div class="price">Price: £125</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=23"><button class="button">SEE MORE INFO</button></a>
                        <a href="fortifiedWines.php?add=23"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/fortifiedWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">FORTIFIED WINE</div>
                    <div class="name">QUINTA DO VESUVIO</div>
                    <div class="description">Quinta do Vesuvio’s 2022 Vintage Port is an elegant, expressive young wine offering concentrated dark fruit, violets, and refined tannins with a beautifully pure estate character and natural freshness. Though youthful, its structure and clarity suggest it will evolve into a deeply layered, long-lived classic with remarkable charm.</div>
                    <div class="price">Price: £476</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=24"><button class="button">SEE MORE INFO</button></a>
                        <a href="fortifiedWines.php?add=24"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
        </div>
        <style>
                .cards-cycle .wines-list .wine .name {
                    color: #718289;
                }
        </style>
        <div class="cover">
            <div class="wine">
                <img src="../../images/Fonseca.jpg">
                <div class="information">
                    <div class="name">Fonseca 1985 Vintage Port</div>
                    <div class="description">Herbaceous white with freshness</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Grahams.jpg">
                <div class="information">
                    <div class="name">Graham’s 1994 Vintage Port</div>
                    <div class="description">Refreshing italian-style white wine</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Quinta.jpg">
                <div class="information">
                    <div class="name">Quinta do Vesuvio 2022 Vintage Port</div>
                    <div class="description">Chardonnay from Burgundy</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Dow.jpg">
                <div class="information">
                    <div class="name">Dow's 2011 Vintage Port</div>
                    <div class="description">Italian white from volcanic hills</div>
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
