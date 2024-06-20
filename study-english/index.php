<?php
session_start();

// Перевіряємо, чи користувач має активну сесію
if (!isset($_SESSION['user_id'])) {
    // Якщо сесія відсутня, перенаправляємо на сторінку логіну
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn English with Us</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="index.css">
    <script src="scripts.js" defer></script>
</head>

<body>
    <?php include 'menu.php'; ?>
    <section id="home" class="hero-section">
        <div class="hero-content">
            <h1>Welcome to the Best Place to Learn English</h1>
            <p>Improve your English skills with our interactive and engaging lessons.</p>
            <a href="#features" class="btn">Discover More</a>
        </div>
        <div class="hero-image">
            <img src="images/news_shutterstock_112421051.jpg" alt="Learning English">
        </div>
    </section>

    <section id="about" class="about-section">
    <div class="about-content">
        <h2>About Us</h2>
        <p>We provide a unique platform to learn English with personalized lessons, expert tutors, and engaging content.</p>
        <p>Our mission is to make learning English accessible and enjoyable for everyone. Whether you are a beginner or looking to refine your advanced skills, our platform is designed to cater to all levels of proficiency. We believe in a holistic approach to language learning, combining traditional methods with innovative techniques to enhance your experience.</p>
        <p>Our platform offers a wide range of study materials, including interactive exercises, video tutorials, and comprehensive articles. These resources are curated by experienced educators and linguists to ensure you receive the highest quality education. With our adaptive learning system, you can choose your level and progress at your own pace, ensuring that your learning journey is both effective and enjoyable.</p>
        <p>In addition to self-paced learning, we provide opportunities for live interaction with expert tutors. Our tutors are highly qualified professionals who are passionate about teaching and helping you achieve your language goals. Whether you need help with grammar, vocabulary, or conversation skills, our tutors are here to guide you every step of the way.</p>
        <p>We also understand the importance of tracking your progress. Our platform includes robust progress tracking tools that allow you to monitor your improvement over time. You can review your results, set goals, and stay motivated as you advance through the levels.</p>
        <p>At our core, we are committed to creating an engaging and supportive learning environment. Our user-friendly interface is designed to be attractive and adaptive, ensuring a seamless experience across all devices. Join us today and take the first step towards mastering the English language with confidence and ease.</p>
    </div>
</section>


    <section id="features" class="features-section">
    <h2>Our Features</h2>
    <div class="features-container">
        <div class="feature-box">
            <img src="images/daian-gan-8_d05sj9JVc-unsplash.jpg" alt="Study Materials">
            <h3>Study Materials</h3>
            <p>Access a wide range of educational materials tailored to your learning needs.</p>
        </div>
        <div class="feature-box">
            <img src="images/d90oztv-10819a32-a097-4e96-83bb-a3eddadb7e0a.jpg" alt="Choose Level">
            <h3>Choose Your Level</h3>
            <p>Select your learning level and progress at your own pace.</p>
        </div>
        <div class="feature-box">
            <img src="images/campaign-creators-pypeCEaJeZY-unsplash.jpg" alt="Assignments">
            <h3>Assignments</h3>
            <p>Complete tasks and exercises designed to enhance your skills.</p>
        </div>
        <div class="feature-box">
            <img src="images/isaac-smith-AT77Q0Njnt0-unsplash.jpg" alt="Progress Tracking">
            <h3>Progress Tracking</h3>
            <p>Monitor your progress and see how you improve over time.</p>
        </div>
        <div class="feature-box">
            <img src="images/windows-p74ndnYWRY4-unsplash.jpg" alt="Results">
            <h3>View Results</h3>
            <p>Review your results and gain insights into your learning journey.</p>
        </div>
        <div class="feature-box">
            <img src="images/domenico-loia-hGV2TfOh0ns-unsplash.jpg" alt="Responsive Design">
            <h3>Responsive Design</h3>
            <p>Enjoy an attractive and adaptive interface that works seamlessly on all devices.</p>
        </div>
    </div>
</section>


<section id="contact" class="contact-section">
    <div class="container">
        <h2>Contact Us</h2>
        <p>Have questions? Get in touch with us!</p>
        <form action="save_contact.php" method="post">
            <div class="form-group">
                <input type="text" name="name" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="form-group">
                <textarea name="message" placeholder="Your Message" required></textarea>
            </div>
            <button type="submit" class="btn">Send Message</button>
        </form>
    </div>
</section>


    <footer>
        <p>&copy; 2024 StudyEnglish. All rights reserved.</p>
    </footer>
</body>

</html>