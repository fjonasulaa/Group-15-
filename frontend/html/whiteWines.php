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

    header("Location: whiteWines.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>White Wines | Wine Exchange</title>
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
          <a href="account.php">Account</a>
          <button id="dark-mode" class="dark-mode-button">
            <img src="../../images/darkmode.png" alt="Dark Mode" />
          </button>
        </div>
      </div>

    <div class="cards-cycle">
        <div class="wines-list">
            <div class="wine">
                <img src="../../images/whiteWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">WHITE WINE</div>
                    <div class="name">SOAVE CLASSICO</div>
                    <div class="description">Soave Classico is a fresh, elegant Italian white wine from the historic
                        hillside zone of Veneto, made mainly from Garganega grapes and known for its bright acidity,
                        subtle citrus and stone-fruit notes, and a signature hint of almond on the finish. Its volcanic
                        hillside soils add a clean mineral edge that enhances its clarity and finesse, making it a
                        versatile and thoroughly food-friendly wine.</div>
                    <div class="price">Price: £37</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=5">
                        <button class="button">SEE MORE INFO</button>
                        </a>
                        <a href="whiteWines.php?add=5"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/whiteWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">WHITE WINE</div>
                    <div class="name">SAUVIGON BLANC</div>
                    <div class="description">Sauvignon Blanc is a vibrant, aromatic white wine celebrated for its zesty
                        acidity, crisp texture, and expressive notes of citrus, herbs, and tropical fruit. Often grown
                        in cool-climate regions like the Loire Valley, New Zealand, and coastal California, it shows a
                        lively, refreshing character with occasional hints of minerality, making it exceptionally
                        food-friendly and versatile.</div>
                    <div class="price">Price: £51</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=6">
                        <button class="button">SEE MORE INFO</button>
                        </a>
                        <a href="whiteWines.php?add=6"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/whiteWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">WHITE WINE</div>
                    <div class="name">PINOT GRIGIO</div>
                    <div class="description">Pinot Grigio is a light, clean, and refreshing white wine known for its
                        bright acidity and subtle flavors of lemon, green apple, and pear, often with a faint floral or
                        mineral touch. Popular in northern Italy and widely produced around the world, it offers an
                        easy-drinking, crisp profile that pairs beautifully with seafood, salads, and simple, delicate
                        dishes.</div>
                    <div class="price">Price: £35</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=7">
                        <button class="button">SEE MORE INFO</button>
                        </a>
                        <a href="whiteWines.php?add=7"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/whiteWinesBG.jpg">
                <div class="information">
                    <div class="wine-type">WHITE WINE</div>
                    <div class="name">CHABLIS PREMIER</div>
                    <div class="description">Chablis Premier is an elegant Burgundian Chardonnay distinguished by its
                        purity, tension, and hallmark minerality derived from the region’s limestone-rich Kimmeridgian
                        soils. Known for its refined citrus, green apple, and flinty notes, it balances freshness with
                        subtle complexity, offering a precise style that showcases the classic character of high-quality
                        Chablis.</div>
                    <div class="price">Price: £62</div>
                    <div class="buttons">
                        <a href="wineinfo.php?id=8">
                        <button class="button">SEE MORE INFO</button>
                        </a>
                        <a href="whiteWines.php?add=8"><button class="button">ADD TO BASKET</button></a>
                    </div>
                </div>
            </div>
        </div>
        <style>
                .cards-cycle .wines-list .wine .name {
                    color: #EAD3A5;
                }
        </style>
        <div class="cover">
            <div class="wine">
                <img src="../../images/blanc.jpg">
                <div class="information">
                    <div class="name">Sauvignon Blanc</div>
                    <div class="description">Herbaceous white with freshness</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Grigio.avif">
                <div class="information">
                    <div class="name">Pinot Grigio</div>
                    <div class="description">Refreshing italian-style white wine</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Chablis.jpg">
                <div class="information">
                    <div class="name">Chablis Premier</div>
                    <div class="description">Chardonnay from Burgundy</div>
                </div>
            </div>
            <div class="wine">
                <img src="../../images/Classico.jpg">
                <div class="information">
                    <div class="name">Soave Classico</div>
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
        <li><a href="contact-us.html">Contact</a></li>
      </ul>
      <a href="contact-us.html" class="footer-button">Contact Us</a>
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
