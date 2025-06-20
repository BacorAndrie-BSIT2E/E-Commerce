<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beans Appetite</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <style>
    html {
      scroll-behavior: smooth;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      background-image: url('bg.jpg.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: rgba(51, 51, 51, 0.5);
      color: white;
      padding: 10px 20px;
      z-index: 100;
      backdrop-filter: blur(6px);
      transition: background 0.3s;
    }

    .navbar .logo {
      font-size: 1.5em;
      font-weight: bold;
      letter-spacing: 2px;
    }

    .navbar nav a {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      cursor: pointer;
      padding: 8px 16px;
      border-radius: 4px;
      transition: background 0.3s, color 0.3s;
    }
    .navbar nav a.active,
    .navbar nav a:hover {
      background: #ff9800;
      color: #fff;
    }

    .navbar .signup {
      background-color: #ff9800;
      color: white;
      border: none;
      padding: 7px 18px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 1em;
      margin-left: 10px;
      transition: background 0.3s;
    }
    .navbar .signup:hover {
      background: #e07c00;
    }
    .navbar .nav-links {
      display: flex;
      gap: 8px;
      margin-left: auto;
      margin-right: 30px;
    }
    .navbar nav {
      display: flex;
    }

    .hero {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      background: rgba(244, 244, 244, 0.85);
      padding: 0 20px;
      margin-top: 0;
      position: relative;
      transition: background 0.5s;
      animation: fadeIn 1s;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-30px);}
      to { opacity: 1; transform: translateY(0);}
    }
    .hero-text h1 {
      font-size: 3.5em;
      margin-bottom: 20px;
      color: #2d2d2d;
      letter-spacing: 2px;
      text-shadow: 0 2px 8px #fff8;
      transition: color 0.3s;
    }
    .hero-text p {
      font-size: 1.5em;
      margin-bottom: 30px;
      color: #444;
      text-shadow: 0 1px 4px #fff7;
    }
    .hero .buy-btn {
      background-color: #ff9800;
      color: white;
      border: none;
      padding: 16px 40px;
      font-size: 1.3em;
      border-radius: 30px;
      cursor: pointer;
      box-shadow: 0 4px 16px 0 #ff980055;
      transition: background 0.3s, transform 0.2s;
    }
    .hero .buy-btn:hover {
      background: #e07c00;
      transform: scale(1.07);
    }
    .bean-icon {
      font-size: 4em;
      margin-top: 30px;
      color: #ff9800;
      opacity: 0.7;
      animation: bean-bounce 2s infinite;
    }
    @keyframes bean-bounce {
      0%, 100% { transform: translateY(0);}
      50% { transform: translateY(-18px);}
    }

    .coffee-lovers {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 0 20px;
      background: rgba(255,255,255,0.85);
      position: relative;
      animation: fadeIn .5s;
    }
    .coffee-lovers h2 {
      font-size: 2.7em;
      margin-bottom: 30px;
      color: #2d2d2d;
      letter-spacing: 1px;
      text-shadow: 0 2px 8px #fff8;
    }
    .product-gallery {
      display: flex;
      justify-content: center;
      gap: 30px;
      margin-bottom: 30px;
      flex-wrap: wrap;
      transition: gap 0.3s;
    }
    .product-img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border-radius: 16px;
      box-shadow: 0 4px 16px 0 #3CCFAD33;
      border: 2px solid #3CCFAD;
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
    }
    .product-img:hover {
      transform: scale(1.08) rotate(-2deg);
      box-shadow: 0 8px 32px 0 #ff980055;
    }
    .quote {
      font-size: 1.3em;
      color: #444;
      margin-top: 20px;
      font-style: italic;
      text-shadow: 0 1px 4px #fff7;
    }

    .services {
      text-align: center;
      padding: 50px 20px;
      background-color: rgba(244, 244, 244, 0.85);
    }

    .services-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-top: 20px;
    }

    .service-box {
      background-color: white;
      padding: 30px 20px;
      border-radius: 14px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
      font-size: 1.2em;
      transition: transform 0.3s, box-shadow 0.3s;
      cursor: pointer;
    }
    .service-box:hover {
      transform: translateY(-8px) scale(1.04);
      box-shadow: 0 8px 32px 0 #3CCFAD33;
    }

    @media (max-width: 900px) {
      .product-gallery { gap: 12px; }
      .product-img { width: 120px; height: 120px; }
      .services-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 600px) {
      .hero-text h1 { font-size: 2em; }
      .coffee-lovers h2 { font-size: 1.3em; }
      .product-img { width: 90px; height: 90px; }
      .service-box { font-size: 1em; }
    }
  </style>
</head>
<body>
  <header class="navbar">
    <div class="logo">☕ Beans Appetite</div>
    <div class="nav-links">
    <nav>
      <a href="#home">Home</a>
      <a href="#products">Products</a>
      <a href="#services">Services</a>
    </nav>
    <button class="signup" onclick="window.location='weblogin.php'">Sign Up</button>
  </div>
</header>

  <section id="home" class="hero">
    <div class="hero-text">
      <h1>WAKE UP & SMELL <br> THE COFFEE!</h1>
      <p>Sit back, sip, and enjoy the perfect blend of flavors in every cup.</p>
      <button class="buy-btn" onclick="document.getElementById('products').scrollIntoView({behavior:'smooth'})">Buy Now!</button>
    </div>
    <div class="bean-icon">☕</div>
  </section>

  <section id="products" class="coffee-lovers">
    <h2>Crafted for Coffee Lovers</h2>
    <div class="product-gallery">
      <img src="espresso.jpg" class="product-img" alt="Espresso">
      <img src="latte.jpg" class="product-img" alt="Latte">
      <img src="cappuccino.jpg" class="product-img" alt="Cappuccino">
      <img src="iced caramel macchiato.jpg" class="product-img" alt="Iced Coffee">
    </div>
    <p class="quote">“From bold espressos to smooth lattes, our coffee is brewed to perfection with the finest beans and expert craftsmanship.”</p>
  </section>

  <section id="services" class="services">
    <h2>Bringing the Best Brews to Your Doorstep</h2>
    <p>Enjoy handcrafted coffee and milk tea, fresh and fast—because every sip should be perfect.</p>
    <div class="services-grid">
      <div class="service-box">🚚<br><strong>Fast Delivery</strong><br>Delivered straight to your door—fresh, fast, and hassle-free.</div>
      <div class="service-box">💳<br><strong>Freshly Prepared</strong><br>Freshly Brewed to Finest Perfection.</div>
    </div>
  </section>
</body>
</html>