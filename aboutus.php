<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Beans Appetite</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: url('bg.jpg.png') no-repeat center center fixed;
            background-size: cover;
            color: #F5F5DC;
        }
        .navbar-fixed {
            background: rgba(44, 26, 23, 0.8);
            color: #F5F5DC;
            padding: 16px 0;
            box-shadow: 0 2px 12px rgba(30,30,50,0.16);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 10;
            text-align: center;
        }
        .navbar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .navbar-logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .navbar-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .navbar-link {
            color: #FFD700;
            text-decoration: none;
            font-size: 1.2em;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .navbar-link:hover {
            color: #FF4500;
        }
        .about-section {
            margin-top: 150px;
            text-align: center;
            padding: 40px 20px;
        }
        .about-section h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #FFD700;
        }
        .team-grid {
            display: grid;
            grid-template-rows: auto auto auto;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
            margin-top: 20px;
        }
        .team-grid .row-1 {
            grid-column: span 3;
        }
        .team-box {
            background: rgba(44, 26, 23, 0.93);
            border-radius: 12px;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.18);
            width: 250px;
            padding: 20px;
            color: #F5F5DC;
            text-align: center;
            transition: transform 0.25s, box-shadow 0.25s;
        }
        .team-box:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 16px 40px 0 rgba(123,63,0,0.4);
        }
        .team-photo {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
            border: 2px solid #FFD700;
        }
        .team-name {
            font-size: 1.5em;
            font-weight: bold;
            color: #FFD700;
            margin-bottom: 8px;
        }
        .team-profession {
            font-size: 1.2em;
            color: #F5F5DC;
        }
    </style>
</head>
<body>
    <header class="navbar-fixed">
        <div class="navbar-logo">
            <div class="logo">☕ Beans Appetite</div>
            <span style="font-size: 1.5em; font-weight: bold;">Beans Appetite</span>
        </div>
        <nav class="navbar-links">
            <a href="User.php" class="navbar-link">Home</a>
            <a href="aboutus.php" class="navbar-link">About Us</a>
            <a href="product_page.php" class="navbar-link">Product</a>
        </nav>
    </header>

    <div class="about-section">
        <h1>Meet Our Professional Team</h1>
        <div class="team-grid">
            <div class="team-box row-1">
                <img src="andrie.jpg" alt="Team Member 1" class="team-photo">
                <div class="team-name">Andrie Bacor</div>
                <div class="team-profession">Project Leader</div>
            </div>
            <div class="team-box">
                <img src="prince.jpg" alt="Team Member 2" class="team-photo">
                <div class="team-name">Prince Bacor</div>
                <div class="team-profession">Presentation Specialist</div>
            </div>
            <div class="team-box">
                <img src="harry.jpg" alt="Team Member 3" class="team-photo">
                <div class="team-name">Harry De Guzman</div>
                <div class="team-profession">Web Developer</div>
            </div>
            <div class="team-box">
                <img src="lj.jpg" alt="Team Member 4" class="team-photo">
                <div class="team-name">Lorenz Justin Sarte</div>
                <div class="team-profession">Graphic Designer</div>
            </div>
            <div class="team-box">
                <img src="pat.jpg" alt="Team Member 5" class="team-photo">
                <div class="team-name">Patrick Lim</div>
                <div class="team-profession">Content Writer</div>
            </div>
            <div class="team-box">
                <img src="hero.jpg" alt="Team Member 6" class="team-photo">
                <div class="team-name">Hero Caloza</div>
                <div class="team-profession">Research Analysis</div>
            </div>
            <div class="team-box">
                <img src="jp.jpg" alt="Team Member 7" class="team-photo">
                <div class="team-name">Jogn Paul Dela Torre</div>
                <div class="team-profession">Visual Designer</div>
            </div>
        </div>
    </div>
</body>
</html>