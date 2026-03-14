<?php
session_start();
require_once('../../database/db_connect.php');
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/Exception.php";
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require __DIR__ . "/../../vendor/phpmailer/phpmailer/src/SMTP.php";
 
$successMsg = "";
$errorMsg = "";
 
function getPost($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : "";
}
 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = getPost("name");
    $email   = getPost("email");
    $subject = getPost("subject");
    $message = getPost("message");
 
    if ($name === "" || $email === "" || $subject === "" || $message === "") {
        $errorMsg = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Please enter a valid email address.";
    } else {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'fjonasula28@gmail.com';
            $mail->Password   = 'fauw hphl bbxh vacb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('fjonasula28@gmail.com', 'Wine Exchange');
            $mail->addAddress('fjonasula28@gmail.com');
            $mail->addReplyTo($email, $name);
            $mail->Subject = "Wine Exchange Contact Form: $subject";
            $mail->Body    =
                "Name: $name\n" .
                "Email: $email\n\n" .
                "Message:\n$message";
            $mail->send();
            $successMsg = "Your message has been sent successfully!";
            $name = $email = $subject = $message = "";
        } catch (Exception $e) {
            $errorMsg = "Email could not be sent. Error: " . $mail->ErrorInfo;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | Wine Exchange</title>
  <link rel="icon" type="image/x-icon" href="../../images/icon.png">
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="info">

  <div class="wishlist-overlay" id="wishlistOverlay"></div>
  <div class="wishlist-sidebar" id="wishlistSidebar">
    <div class="close-wishlist" id="closeWishlist"><i class="fa fa-times"></i></div>
    <h3>Your Wishlist</h3>
    <div id="wishlist-items"><p>Your wishlist is empty.</p></div>
  </div>

  <!-- Navbar -->
  <div class="navbar">
    <img src="../../images/icon.png" alt="Wine Exchange Logo">
    <div class="navbar-links">
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="search.php">Wines</a>
      <a href="basket.php">Basket</a>
      <a href="contact-us.php">Contact Us</a>
    </div>
    <div class="navbar-right">
      <form method="POST" action="search.php">
        <input type="text" name="search" placeholder="Search">
        <input type="hidden" name="submitted" value="true"/>
      </form>
      <a href="log-in.php">Login</a>
      <a href="signup.php">Sign up</a>
      <a href="account.php">Account</a>
      <button id="dark-mode" class="dark-mode-button">
        <img src="../../images/darkmode.png" alt="Dark Mode" />
      </button>
      <button id="wishlist-toggle" class="wishlist-nav-button">
        <i class="fas fa-heart"></i>
        <span id="wishlist-count" class="wishlist-count">0</span>
      </button>
    </div>
  </div>

  <!-- Hero Banner -->
  <div class="contact-hero">
    <div class="contact-hero-inner">
      <span class="contact-eyebrow">Get in touch</span>
      <h1>Contact Us</h1>
      <p>Questions about an order, a wine recommendation, or just want to say hello? We'd love to hear from you.</p>
    </div>
  </div>

  <!-- Info strip -->
  <div class="contact-info-strip">
    <div class="contact-info-strip-inner">
      <div class="info-strip-item">
        <i class="fas fa-map-marker-alt"></i>
        <span>123 Vineyard Lane, London, UK</span>
      </div>
      <div class="info-strip-divider"></div>
      <div class="info-strip-item">
        <i class="fas fa-phone"></i>
        <span>+44 1234 567890</span>
      </div>
      <div class="info-strip-divider"></div>
      <div class="info-strip-item">
        <i class="fas fa-envelope"></i>
        <span>contactwinexchange@gmail.com</span>
      </div>
      <div class="info-strip-divider"></div>
      <div class="info-strip-item">
        <i class="fas fa-clock"></i>
        <span>Mon–Fri, 9am–6pm</span>
      </div>
    </div>
  </div>

  <!-- Main content: form + map -->
  <main class="contact-main">

    <!-- Form card -->
    <div class="contact-form-card">
      <div class="form-card-header">
        <h2>Send a message</h2>
        <p>Fill in the form below and we'll get back to you as soon as possible.</p>
      </div>

      <?php if ($errorMsg !== ""): ?>
        <div class="contact-alert contact-alert--error">
          <i class="fas fa-exclamation-circle"></i>
          <?php echo htmlspecialchars($errorMsg); ?>
        </div>
      <?php endif; ?>

      <?php if ($successMsg !== ""): ?>
        <div class="contact-alert contact-alert--success">
          <i class="fas fa-check-circle"></i>
          <?php echo htmlspecialchars($successMsg); ?>
        </div>
      <?php endif; ?>

      <form method="post" action="" class="contact-form">
        <div class="form-row">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name"
              value="<?php echo htmlspecialchars(isset($name) ? $name : ""); ?>"
              placeholder="Your full name" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
              value="<?php echo htmlspecialchars(isset($email) ? $email : ""); ?>"
              placeholder="you@example.com" required>
          </div>
        </div>

        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" id="subject" name="subject"
            value="<?php echo htmlspecialchars(isset($subject) ? $subject : ""); ?>"
            placeholder="How can we help?" required>
        </div>

        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="6"
            placeholder="Tell us more…" required><?php echo htmlspecialchars(isset($message) ? $message : ""); ?></textarea>
        </div>

        <button type="submit" class="contact-submit">
          Send Message <i class="fas fa-paper-plane"></i>
        </button>
      </form>
    </div>

    <!-- Map card -->
    <div class="contact-map-card">
      <div class="map-embed-wrapper">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d158858.47340002525!2d-0.24168120642735063!3d51.52855824164916!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47d8a00baf21de75%3A0x52963a5addd52a99!2sLondon%2C%20UK!5e0!3m2!1sen!2sus!4v1710000000000!5m2!1sen!2sus"
          width="100%" height="100%"
          style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          title="Wine Exchange Location">
        </iframe>
      </div>
      <div class="map-card-footer">
        <h3>Find Us</h3>
        <p>We're based in the heart of London. Pop in during opening hours or reach us through the form.</p>
      </div>
    </div>

  </main>

  <!-- Dark mode script -->
  <script>
    const darkButton = document.getElementById("dark-mode");
    if (localStorage.getItem("dark_mode") === "on") {
      document.documentElement.classList.add("darkmode");
    }
    darkButton.addEventListener("click", () => {
      document.documentElement.classList.toggle("darkmode");
      localStorage.setItem("dark_mode",
        document.documentElement.classList.contains("darkmode") ? "on" : "off"
      );
    });
  </script>

<style>

/* ──  Banner ── */
.contact-hero {
  padding: 60px 20px 40px;
  text-align: center;
}

.contact-hero-inner {
  background: linear-gradient(135deg, #4a0e24 0%, #7b1e3a 50%, #9e2d4f 100%);
  position: relative;
  overflow: hidden;

  width: 100%;
  max-width: 1100px;

  margin: 0 auto;

  padding: 70px 40px;

  border-radius: 16px;
}

.contact-hero-inner::before {
  content: '';
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 20% 80%, rgba(255,255,255,0.04) 0%, transparent 50%),
    radial-gradient(circle at 80% 20%, rgba(255,255,255,0.06) 0%, transparent 50%);
}

.contact-eyebrow {
  display: inline-block;
  font-size: 11px;
  font-weight: 600;
  letter-spacing: 2.5px;
  text-transform: uppercase;
  color: rgba(255,255,255,0.6);
  margin-bottom: 14px;
  position: relative;
  z-index: 1;
}

.contact-hero h1 {
  font-size: 28px;
  font-weight: 700;
  color: #ffffff;
  margin: 0 0 12px;
  letter-spacing: -0.5px;
  line-height: 1.1;
  position: relative;
  z-index: 1;
}

.contact-hero p {
  font-size: 13px;
  color: rgba(255,255,255,0.75);
  line-height: 1.6;
  margin: 0;
  position: relative;
  z-index: 1;
}

/* ── Info Strip ── */
.contact-info-strip {
  background: #ffffff;
  border-bottom: 1px solid #ece8e9;
  padding: 0 20px;
}

.contact-info-strip-inner {
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 0;
}

.info-strip-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 18px 24px;
  font-size: 13.5px;
  color: #555;
  flex: 1 1 auto;
}

.info-strip-item i {
  color: #7b1e3a;
  font-size: 14px;
  flex-shrink: 0;
}

.info-strip-divider {
  width: 1px;
  height: 28px;
  background: #e5dfe0;
  flex-shrink: 0;
}

/* ── Main Layout ── */
.contact-main {
  max-width: 1100px;
  margin: 52px auto;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  align-items: start;
}

/* ── Form Card ── */
.contact-form-card {
  background: #ffffff;
  border-radius: 16px;
  border: 1px solid #ece8e9;
  padding: 40px 40px 36px;
  box-shadow: 0 2px 24px rgba(123,30,58,0.06);
}

.form-card-header {
  margin-bottom: 28px;
}

.form-card-header h2 {
  font-size: 22px;
  font-weight: 700;
  color: #2a0a14;
  margin: 0 0 6px;
}

.form-card-header p {
  font-size: 14px;
  color: #888;
  margin: 0;
}

/* Alert banners */
.contact-alert {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 16px;
  border-radius: 10px;
  font-size: 14px;
  margin-bottom: 22px;
}

.contact-alert--error {
  background: #fef2f2;
  color: #b91c1c;
  border: 1px solid #fecaca;
}

.contact-alert--success {
  background: #f0fdf4;
  color: #15803d;
  border: 1px solid #bbf7d0;
}

/* Form fields */
.contact-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #3a1020;
  letter-spacing: 0.2px;
}

.form-group input,
.form-group textarea {
  width: 100%;
  box-sizing: border-box;
  padding: 11px 14px;
  border: 1.5px solid #e0d8da;
  border-radius: 10px;
  font-size: 14.5px;
  color: #2a0a14;
  background: #faf9f9;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  outline: none;
  resize: vertical;
}

.form-group input::placeholder,
.form-group textarea::placeholder {
  color: #b8a8ad;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #7b1e3a;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(123,30,58,0.08);
}

/* Submit button */
.contact-submit {
  display: inline-flex;
  align-items: center;
  gap: 9px;
  background: #7b1e3a;
  color: #ffffff;
  border: none;
  border-radius: 10px;
  padding: 13px 28px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  align-self: flex-start;
  letter-spacing: 0.1px;
  box-shadow: 0 4px 14px rgba(123,30,58,0.25);
}

.contact-submit:hover {
  background: #5e152c;
  box-shadow: 0 6px 20px rgba(123,30,58,0.35);
  transform: translateY(-1px);
}

.contact-submit:active {
  transform: translateY(0);
}

/* ── Map Card ── */
.contact-map-card {
  border-radius: 16px;
  overflow: hidden;
  border: 1px solid #ece8e9;
  box-shadow: 0 2px 24px rgba(123,30,58,0.06);
  display: flex;
  flex-direction: column;
}

.map-embed-wrapper {
  width: 100%;
  height: 380px;
  flex-shrink: 0;
}

.map-embed-wrapper iframe {
  display: block;
  width: 100%;
  height: 100%;
  border: 0;
}

.map-card-footer {
  background: #ffffff;
  padding: 24px 28px;
  border-top: 1px solid #ece8e9;
}

.map-card-footer h3 {
  font-size: 17px;
  font-weight: 700;
  color: #2a0a14;
  margin: 0 0 6px;
}

.map-card-footer p {
  font-size: 13.5px;
  color: #888;
  margin: 0;
  line-height: 1.55;
}

/* ── Responsive ── */
@media (max-width: 880px) {
  .contact-main {
    grid-template-columns: 1fr;
  }

  .form-row {
    grid-template-columns: 1fr;
  }

  .contact-hero h1 {
    font-size: 22px;
  }

  .info-strip-divider {
    display: none;
  }

  .contact-info-strip-inner {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }

  .info-strip-item {
    padding: 10px 0;
  }
}

/* ── Dark Mode ── */
html.darkmode .contact-info-strip {
  background: #1a1a1a;
  border-bottom-color: #333;
}

html.darkmode .info-strip-item {
  color: #bbb;
}

html.darkmode .info-strip-item i {
  color: #e88ca0;
}

html.darkmode .info-strip-divider {
  background: #3a3a3a;
}

html.darkmode .contact-form-card,
html.darkmode .map-card-footer {
  background: #1e1e1e;
  border-color: #333;
  box-shadow: 0 2px 24px rgba(0,0,0,0.3);
}

html.darkmode .form-card-header h2 {
  color: #f5e8ec;
}

html.darkmode .form-card-header p {
  color: #888;
}

html.darkmode .form-group label {
  color: #e0c8d0;
}

html.darkmode .form-group input,
html.darkmode .form-group textarea {
  background: #2a1a1f;
  border-color: #4a2a35;
  color: #f0e0e5;
}

html.darkmode .form-group input::placeholder,
html.darkmode .form-group textarea::placeholder {
  color: #6a4555;
}

html.darkmode .form-group input:focus,
html.darkmode .form-group textarea:focus {
  border-color: #e88ca0;
  background: #321520;
  box-shadow: 0 0 0 3px rgba(232,140,160,0.1);
}

html.darkmode .contact-submit {
  background: #9e2d4f;
  box-shadow: 0 4px 14px rgba(0,0,0,0.4);
}

html.darkmode .contact-submit:hover {
  background: #c03a60;
}

html.darkmode .contact-map-card {
  border-color: #333;
  box-shadow: 0 2px 24px rgba(0,0,0,0.3);
}

html.darkmode .map-card-footer h3 {
  color: #f5e8ec;
}

html.darkmode .map-card-footer p {
  color: #888;
}

html.darkmode .map-card-footer {
  border-top-color: #333;
}

html.darkmode .contact-alert--error {
  background: #2a1010;
  color: #f87171;
  border-color: #7f1d1d;
}

html.darkmode .contact-alert--success {
  background: #0a1f10;
  color: #4ade80;
  border-color: #14532d;
}

/* ── Wishlist ── */
#wishlist-items{display:flex;flex-direction:column;gap:15px;margin-top:20px;}
.wishlist-item{display:flex;gap:12px;align-items:center;background:white;border-radius:10px;padding:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);position:relative;}
.wishlist-img{width:65px;height:65px;object-fit:cover;border-radius:8px;}
.wishlist-info{flex:1;}
.wishlist-name{font-weight:600;font-size:14px;margin-bottom:4px;}
.wishlist-price{color:#7b1e3a;font-weight:bold;margin-bottom:8px;}
.wishlist-actions{display:flex;gap:8px;}
.wishlist-view{padding:4px 10px;font-size:12px;border-radius:6px;background:#eee;text-decoration:none;color:#333;}
.wishlist-view:hover{background:#ddd;}
.wishlist-basket{border:none;background:#7b1e3a;color:white;padding:4px 8px;border-radius:6px;cursor:pointer;}
.wishlist-basket:hover{background:#5e152c;}
.remove-wishlist{position:absolute;top:6px;right:6px;border:none;background:none;font-size:14px;cursor:pointer;color:#999;}
.remove-wishlist:hover{color:red;}
.wishlist-nav-button{background:none;border:none;font-size:20px;cursor:pointer;color:#e63946;margin-left:10px;position:relative;}
.wishlist-count{position:absolute;top:-6px;right:-8px;background:#e63946;color:white;font-size:11px;font-weight:bold;padding:2px 6px;border-radius:50px;min-width:18px;text-align:center;}
.wishlist-sidebar{position:fixed;top:0;right:-420px;width:380px;height:100%;background:#f4f1f2;padding:30px;box-shadow:-5px 0 20px rgba(0,0,0,0.25);transition:right 0.4s ease;z-index:2000;overflow-y:auto;}
.wishlist-sidebar.active{right:0;}
.wishlist-overlay{position:fixed;inset:0;background:rgba(0,0,0,0.5);display:none;z-index:1500;}
.wishlist-overlay.active{display:block;}
.close-wishlist{font-size:22px;cursor:pointer;text-align:right;margin-bottom:15px;}
html.darkmode .wishlist-sidebar{background:#121212;color:#ffffff;}
html.darkmode .wishlist-item{background:#1e1e1e;border:1px solid #333;box-shadow:none;}
html.darkmode .wishlist-name{color:#ffffff;}
html.darkmode .wishlist-price{color:#ff6b6b;}
html.darkmode .wishlist-view{background:#2c2c2c;color:#ffffff;}
html.darkmode .wishlist-view:hover{background:#3a3a3a;}
html.darkmode .wishlist-basket{background:#e63946;}
html.darkmode .wishlist-basket:hover{background:#c92d3a;}
html.darkmode .remove-wishlist{color:#bbbbbb;}
html.darkmode .remove-wishlist:hover{color:#ff4d4d;}
html.darkmode #wishlist-items p{color:#cccccc;}

/* ── Footer ── */
.footer{background-color:#f4f4f4;padding:30px 10%;margin-top:40px;color:#333;}
.footer-container{display:flex;justify-content:space-between;flex-wrap:wrap;}
.footer-section{flex:1 1 250px;margin:10px;}
.footer-section h3{margin-bottom:10px;}
.footer-links{list-style:none;padding:0;}
.footer-links li{margin:5px 0;}
.footer-links a{text-decoration:none;color:inherit;}
.footer-links a:hover{text-decoration:underline;}
.footer-button{display:inline-block;margin-top:10px;padding:8px 15px;background-color:#4CAF50;color:white;border-radius:4px;text-decoration:none;}
.footer-button:hover{opacity:0.9;}
.footer-bottom{text-align:center;margin-top:20px;padding-top:10px;border-top:1px solid #ccc;font-size:14px;}
.darkmode .footer{background-color:#1e1e1e;color:#eee;}
.darkmode .footer-bottom{border-top:1px solid #555;}
.darkmode .footer-links a{color:#ddd;}
</style>

<script>
const loggedIn = <?php echo isset($_SESSION['customerID']) ? "true" : "false"; ?>;
const wishlistBtn = document.getElementById("wishlist-toggle");
const wishlistSidebar = document.getElementById("wishlistSidebar");
const closeWishlist = document.getElementById("closeWishlist");
const wishlistOverlay = document.getElementById("wishlistOverlay");

wishlistBtn.addEventListener("click", () => {
  wishlistSidebar.classList.add("active");
  wishlistOverlay.classList.add("active");
});
closeWishlist.addEventListener("click", () => {
  wishlistSidebar.classList.remove("active");
  wishlistOverlay.classList.remove("active");
});
wishlistOverlay.addEventListener("click", () => {
  wishlistSidebar.classList.remove("active");
  wishlistOverlay.classList.remove("active");
});

const wishlistContainer = document.getElementById("wishlist-items");
const wishlistCount = document.getElementById("wishlist-count");

function getGuestWishlist(){ return JSON.parse(localStorage.getItem("wishlist")) || []; }
function saveGuestWishlist(list){ localStorage.setItem("wishlist",JSON.stringify(list)); }

function loadWishlist(){
  if(loggedIn){
    fetch("get_wishlist.php").then(res => res.json()).then(data => { renderWishlist(data); });
  } else {
    renderWishlist(getGuestWishlist());
  }
}

function renderWishlist(list){
  wishlistContainer.innerHTML="";
  if(list.length===0){ wishlistContainer.innerHTML="<p>Your wishlist is empty.</p>"; wishlistCount.textContent=0; return; }
  wishlistCount.textContent=list.length;
  list.forEach((wine,index)=>{
    let image = loggedIn
      ? (wine.imageUrl ? "../../images/" + wine.imageUrl : "../../images/placeholder.jpg")
      : (wine.imageUrl || "../../images/placeholder.jpg");
    const item=document.createElement("div");
    item.className="wishlist-item";
    item.innerHTML=`
      <img src="${image}" class="wishlist-img">
      <div class="wishlist-info">
        <div class="wishlist-name">${wine.wineName || wine.name}</div>
        <div class="wishlist-price">£${wine.price}</div>
        <div class="wishlist-actions">
          <a href="wineinfo.php?id=${wine.id || wine.wineId}" class="wishlist-view">View</a>
        </div>
      </div>
      <button class="remove-wishlist" data-id="${wine.wineId || wine.id}" data-index="${index}">
        <i class="fas fa-times"></i>
      </button>`;
    wishlistContainer.appendChild(item);
  });
}

document.addEventListener("click",function(e){
  const removeBtn=e.target.closest(".remove-wishlist");
  if(!removeBtn) return;
  const wineId=removeBtn.dataset.id;
  const index=removeBtn.dataset.index;
  if(loggedIn){
    fetch("remove_from_wishlist.php",{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body:"wineId="+wineId})
    .then(()=> loadWishlist());
  } else {
    let list=getGuestWishlist();
    list.splice(index,1);
    saveGuestWishlist(list);
    renderWishlist(list);
  }
});

loadWishlist();
</script>

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
        <li><a href="index.php">Home</a></li>
        <li><a href="search.php">Wines</a></li>
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
    © 2026 Wine Exchange. All rights reserved.
  </div>
</footer>

</body>
</html>