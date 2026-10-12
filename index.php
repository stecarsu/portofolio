<?php
$nama = "dwi";
$role = "ALL ROLE";

$skills = [
    "Web Developer",
    "PHP",
    "HTML",
    "CSS",
    "JavaScript",
    "MySQL",
    "Python",
    "Services Laptop",
    "Services And Setting cctv",
    "Make up Artist",
    "Hair Styling",
    "Salon Treatment"
];

/* =========================
   PENGATURAN LOKASI
========================= */

// Lokasi fisik folder uploads relatif terhadap index.php
$upload_dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

// Alamat URL index.php yang sedang dibuka
$script_url = str_replace(
    '\\',
    '/',
    $_SERVER['SCRIPT_NAME'] ?? '/index.php'
);

if ($script_url === '' || $script_url[0] !== '/') {
    $script_url = '/' . ltrim($script_url, '/');
}

// Folder URL tempat index.php berada
$script_dir = rtrim(dirname($script_url), '/');

if ($script_dir === '.' || $script_dir === '/') {
    $script_dir = '';
}

// URL folder uploads mengikuti lokasi index.php
$upload_url = $script_dir . '/uploads/';

// Buat folder uploads apabila belum ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

/* =========================
   PROSES UPLOAD FOTO
========================= */

if (
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' &&
    isset($_FILES['profile_photo'])
) {
    $file = $_FILES['profile_photo'];

    if (
        isset($file['error']) &&
        $file['error'] === UPLOAD_ERR_OK
    ) {
        $file_extension = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        $allowed_extensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
            'gif'
        ];

        if (in_array($file_extension, $allowed_extensions, true)) {
            $new_filename = 'profile_' .
                time() . '_' .
                bin2hex(random_bytes(3)) . '.' .
                $file_extension;

            $target_file = $upload_dir . $new_filename;

            if (move_uploaded_file(
                $file['tmp_name'],
                $target_file
            )) {
                // Kembali ke halaman index.php yang sama
               header('Location: /portofolio/index.php', true, 303);
                exit;
            }
        }
    }
}

/* =========================
   AMBIL FOTO TERBARU
========================= */

$current_photo = '';

$files = glob(
    $upload_dir . '*.{jpg,jpeg,png,webp,gif}',
    GLOB_BRACE
);

if (!empty($files)) {
    usort($files, function ($a, $b) {
        return filemtime($b) <=> filemtime($a);
    });

    // Gunakan URL publik, bukan path fisik server
    $current_photo = $upload_url .
        rawurlencode(basename($files[0]));
}

/* =========================
   DATA PROJECT
========================= */

$projects = [
    [
        "title" => "Web Developer",
        "category" => "PHP • MySQL • Database • Python • HTML • JavaScript",
        "description" => "a programmer focused on developing functional and interactive web based applications. experienced in designing a wide range of systems such as cashier applications, ticketing systems, warehouse inventory management, marketing management platforms, admin systems, 3D web visualizations, 3D electronic circuit simulators, games, calculator, as well as other custom web upon request."
    ],
    [
        "title" => "Engineer",
        "category" => "hardware repair • arduino • circuit design • cctv setup • troubleshooting • soldering",
        "description" => "specialized in hardware diagnostics, laptop repair, and professional CCTV setup. Experienced in assembling, soldering, and designing electronic circuits, as well as troubleshooting technical issues and working with arduino-based systems."
    ],
    [
        "title" => "Housekeeping",
        "category" => "towel art • room management • sanitization • bed making",
        "description" => "dedicated to exceptional hospitality standards with a strong focus on precise bed making and aesthetic towel art designs. expert in room organization, safety guidelines, and deep cleaning sanitization to create a pleasant and hygienic atmosphere for every guest."
    ],
    [
        "title" => "Beauty Styling",
        "category" => "cosmetology • hair styling • salon treatment • make up looks",
        "description" => "passionate about cosmetology and personal aesthetics, offering comprehensive skills in hair styling, professional salon treatments, and versatile makeup looks. dedicated to enhancing natural beauty and delivering tailored styling solutions for any occasion."
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($nama) ?> — Portfolio</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        :root {
            --bg: #0a0a0c;
            --card: #111114;
            --card2: #151519;
            --text: #f5f5f7;
            --muted: #9999a3;
            --line: #242429;
            --accent: #9b6cff;
            --accent2: #bca2ff;
        }

        body {
            font-family: Inter, Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .background {
            position: fixed;
            inset: 0;
            z-index: -2;
            background:
                radial-gradient(
                    circle at 50% 0%,
                    rgba(155, 108, 255, .09),
                    transparent 35%
                ),
                var(--bg);
        }

        .grid {
            display: none;
        }

        /* NAVIGASI */

        .top-nav {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
            display: flex;
            gap: 4px;
            padding: 5px;
            background: rgba(10, 10, 12, .9);
            border: 1px solid var(--line);
            border-radius: 12px;
        }

        .top-nav a {
            padding: 7px 12px;
            color: #92929b;
            font-size: 11px;
            font-weight: 700;
            border-radius: 8px;
            transition: .2s;
        }

        .top-nav a:hover {
            color: #fff;
            background: #1b1b20;
        }

        section {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 90px 7%;
        }

        .section-container {
            width: 100%;
            max-width: 1050px;
        }

        .section-title {
            margin-bottom: 30px;
        }

        .section-title small {
            display: block;
            color: var(--accent2);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .section-title h2 {
            font-size: 34px;
            letter-spacing: -1px;
        }

        /* HERO */

        .hero {
            min-height: 100vh;
            padding: 100px 7% 70px;
        }

        .hero-content {
            width: 100%;
            max-width: 1050px;
            display: grid;
            grid-template-columns: 1.15fr .85fr;
            gap: 60px;
            align-items: center;
        }

        .hero h1 {
            font-size: clamp(52px, 7vw, 82px);
            line-height: .92;
            letter-spacing: -4px;
            margin-bottom: 22px;
        }

        .hero h1 span {
            color: var(--accent);
        }

        .hero-description {
            max-width: 580px;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .hero-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 17px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border: 1px solid var(--accent);
        }

        .btn-primary:hover {
            background: #8758ed;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            color: #ddd;
            border: 1px solid var(--line);
        }

        .btn-secondary:hover {
            background: #151519;
            border-color: #3a3a42;
            transform: translateY(-2px);
        }

        /* KARTU PROFIL */

        .profile-card {
            min-height: 390px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 30px;
        }

        .profile-card::before {
            content: "";
            position: absolute;
            top: -80px;
            right: -80px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: var(--accent);
            filter: blur(80px);
            opacity: .1;
        }

        .profile-card::after {
            content: "PROFILE";
            position: absolute;
            top: 18px;
            right: 20px;
            color: #4e4e57;
            font-size: 9px;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .profile-settings-menu {
            display: none;
            position: absolute;
            top: 42px;
            z-index: 20;
            width: 235px;
            padding: 14px;
            background: #121216;
            border: 1px solid #33333a;
            border-radius: 10px;
            box-shadow: 0 15px 35px #000;
            text-align: left;
        }

        .profile-settings-menu.active {
            display: block;
            animation: fadeIn .2s;
        }

        .profile-settings-menu h4 {
            font-size: 12px;
            color: var(--accent2);
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }

        .profile-settings-menu h4 span {
            cursor: pointer;
            color: #aaa;
        }

        .profile-settings-menu label {
            font-size: 10px;
            color: #aaa;
            display: block;
            margin-bottom: 4px;
        }

        .file-upload-btn {
            display: block;
            width: 100%;
            padding: 8px;
            background: var(--accent);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 9px;
        }

        .profile-settings-menu select {
            width: 100%;
            padding: 6px;
            background: #1b1b20;
            border: 1px solid #333;
            color: #fff;
            border-radius: 6px;
            font-size: 11px;
        }

        .profile-inner {
            width: 205px;
            height: 205px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            cursor: pointer;
            background: #0a0a0d;
            border: 2px solid #8057dc;
            box-shadow: 0 0 30px rgba(155, 108, 255, .15);
        }

        .profile-inner:hover {
            border-color: #fff;
            transform: scale(1.02);
        }

        .profile-inner::after {
            content: "Klik untuk ganti foto";
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .72);
            font-size: 11px;
            font-weight: 700;
            opacity: 0;
            transition: .2s;
        }

        .profile-inner:hover::after {
            opacity: 1;
        }

        .profile-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-card.layout-square .profile-inner {
            border-radius: 18px;
        }

        .profile-label {
            margin-top: 17px;
            color: #9b9ba5;
            font-size: 11px;
            letter-spacing: 2px;
            font-weight: 700;
        }

        /* ABOUT */

        .about-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .about-card {
            padding: 25px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
        }

        .about-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .about-card p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        /* SKILLS */

        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .skill {
            padding: 8px 12px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 7px;
            color: #c8c8d0;
            font-size: 11px;
            transition: .2s;
        }

        .skill:hover {
            color: #fff;
            border-color: #6543a8;
            transform: translateY(-2px);
        }

        /* PROJECTS */

        .projects {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .project-card {
            min-height: 270px;
            padding: 23px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: .2s;
        }

        .project-card:hover {
            border-color: #4a3a66;
            transform: translateY(-3px);
        }

        .project-number {
            font-size: 10px;
            color: #606069;
            letter-spacing: 2px;
        }

        .project-card h3 {
            font-size: 21px;
            margin-top: 16px;
        }

        .project-category {
            font-size: 10px;
            color: var(--accent2);
            margin-top: 6px;
        }

        .project-card p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.75;
            margin-top: 12px;
        }

        .project-link {
            font-size: 11px;
            color: #ddd;
            font-weight: 700;
            margin-top: 18px;
        }

        .project-link:hover {
            color: var(--accent2);
        }

        /* CONTACT */

        .contact-box {
            width: 100%;
            padding: 42px 30px;
            text-align: center;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
        }

        .contact-box h2 {
            font-size: 31px;
            margin-bottom: 8px;
        }

        .contact-box p {
            max-width: 580px;
            margin: 0 auto 22px;
            color: var(--muted);
            font-size: 13px;
        }

        .contact-dropdown {
            display: none;
            max-width: 430px;
            margin: 20px auto 0;
            padding: 14px;
            background: #0d0d10;
            border: 1px solid var(--line);
            border-radius: 10px;
            text-align: left;
        }

        .contact-dropdown.active {
            display: block;
        }

        .contact-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 9px 11px;
            margin-bottom: 6px;
            background: #151519;
            border: 1px solid #222229;
            border-radius: 6px;
            font-size: 11px;
        }

        .contact-item:hover {
            border-color: #4b386d;
        }

        .contact-item span {
            color: var(--accent2);
            font-weight: 700;
        }

        footer {
            border-top: 1px solid var(--line);
            padding: 22px 7%;
            text-align: center;
            color: #55555e;
            font-size: 10px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (max-width: 850px) {
            .hero-content {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .projects,
            .about-box {
                grid-template-columns: 1fr;
            }

            .profile-card {
                min-height: 350px;
            }
        }

        @media (max-width: 600px) {
            .top-nav {
                top: 8px;
                max-width: calc(100% - 16px);
                overflow: auto;
            }

            .top-nav a {
                white-space: nowrap;
                padding: 7px 9px;
                font-size: 10px;
            }

            .hero {
                padding: 90px 5% 60px;
            }

            .hero h1 {
                font-size: 50px;
                letter-spacing: -3px;
            }

            section {
                padding: 65px 5%;
            }

            .section-title h2 {
                font-size: 29px;
            }

            .profile-inner {
                width: 180px;
                height: 180px;
            }

            .contact-box {
                padding: 32px 18px;
            }

            .contact-item {
                flex-direction: column;
                gap: 2px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVIGASI -->
    <nav class="top-nav" aria-label="Main navigation">
        <a href="#home">HOME</a>
        <a href="#about">ABOUT</a>
        <a href="#skills">SKILLS</a>
        <a href="#projects">PROJECTS</a>
        <a href="#contact">CONTACT</a>
    </nav>

    <div class="background"></div>
    <div class="grid"></div>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="hero-content">

            <div>
                <h1>
                    Hi, I'm<br>
                    <span><?= htmlspecialchars($nama) ?>.</span>
                </h1>

                <p class="hero-description">
                    I am a student developing skills in coding, English,
                    engineering, hospitality, and makeup artistry —
                    combining technical thinking with creative work.
                </p>

                <div class="hero-buttons">
                    <a href="#projects" class="btn btn-primary">
                        See Project
                    </a>

                    <a href="#contact" class="btn btn-secondary">
                        Contact Account
                    </a>
                </div>
            </div>

            <!-- KARTU PROFIL -->
            <div class="profile-card" id="profileCard">

                <form
                    action="<?= htmlspecialchars($script_url, ENT_QUOTES, 'UTF-8') ?>"
                    method="POST"
                    enctype="multipart/form-data"
                    id="uploadForm"
                >
                    <div
                        id="profileSettingsMenu"
                        class="profile-settings-menu"
                    >
                        <h4>
                            Atur Profil
                            <span onclick="toggleProfileSettings()">✕</span>
                        </h4>

                        <label for="fileInput">
                            Pilih Foto dari Galeri/File:
                        </label>

                        <label for="fileInput" class="file-upload-btn">
                            📁 Pilih Foto Baru...
                        </label>

                        <input
                            type="file"
                            id="fileInput"
                            name="profile_photo"
                            accept=".jpg,.jpeg,.png,.webp,.gif"
                            style="display: none;"
                            onchange="document.getElementById('uploadForm').submit()"
                        >

                        <label for="layoutSelect" style="margin-top: 8px;">
                            Bentuk Grid / Layout:
                        </label>

                        <select
                            id="layoutSelect"
                            onchange="changeLayout(this)"
                        >
                            <option value="circle">Lingkaran (Circle)</option>
                            <option value="square">Kotak Estetik (Square)</option>
                        </select>
                    </div>
                </form>

                <!-- AREA FOTO PROFIL -->
                <div
                    class="profile-inner"
                    id="profileInnerContainer"
                    onclick="toggleProfileSettings()"
                    title="Klik untuk mengganti foto profil"
                >
                    <?php if (!empty($current_photo)): ?>

                        <img
                            id="profilePhoto"
                            src="<?= htmlspecialchars($current_photo, ENT_QUOTES, 'UTF-8') ?>"
                            alt="Foto Profil"
                        >

                        <span id="profileInitial" style="display: none;">
                            D
                        </span>

                    <?php else: ?>

                        <img
                            id="profilePhoto"
                            src=""
                            alt="Foto Profil"
                            style="display: none;"
                        >

                        <span id="profileInitial">D</span>

                    <?php endif; ?>
                </div>

                <div class="profile-label">
                    <?= htmlspecialchars($role) ?>
                </div>
            </div>

        </div>
    </section>

    <!-- ABOUT -->
    <section id="about">
        <div class="section-container">

            <div class="section-title">
                <h2>ABOUT ME</h2>
            </div>

            <div class="about-box">

                <div class="about-card">
                    <h3>Who I Am</h3>

                    <p>
                        I am a student with a strong interest in technology
                        and engineering. I enjoy learning about, assembling,
                        repairing, and building electronic circuits. I also
                        possess skills in programming/coding (PHP, HTML,
                        Python, JavaScript) and website development.
                        Furthermore, I am highly competent and up-to-date in
                        the fields of beauty and salon services. I also have
                        skills in hospitality and customer service—roles that
                        require multilingual abilities—with my strongest
                        languages being English, Japanese, and Korean.
                        Additionally, I participate in various extracurricular
                        activities related to sports and athletics.
                    </p>
                </div>

                <div class="about-card">
                    <h3>What I Do</h3>

                    <p>
                        I create various projects including websites,
                        coding-based apps, and databases. I repair and make
                        simple electronic circuits. I do towel folding from
                        hospitality knowledge, and I also have makeup skills
                        with various looks (Korean, Igari, Baddies, Japanese,
                        Chinese).
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- SKILLS -->
    <section id="skills">
        <div class="section-container">

            <div class="section-title">
                <small>MY SKILLS</small>
                <h2>Teknologi, Tecnic, Hospitally, Salon</h2>
            </div>

            <div class="skills">
                <?php foreach ($skills as $skill): ?>
                    <div class="skill">
                        <?= htmlspecialchars($skill) ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- PROJECTS -->
    <section id="projects">
        <div class="section-container">

            <div class="section-title">
                <small>MY WORK</small>
                <h2>Projects</h2>
            </div>

            <div class="projects">
                <?php foreach ($projects as $index => $project): ?>

                    <div class="project-card">
                        <div>
                            <div class="project-number">
                                0<?= $index + 1 ?>
                            </div>

                            <h3>
                                <?= htmlspecialchars($project['title']) ?>
                            </h3>

                            <div class="project-category">
                                <?= htmlspecialchars($project['category']) ?>
                            </div>

                            <p>
                                <?= htmlspecialchars($project['description']) ?>
                            </p>
                        </div>

                        <a href="#" class="project-link">
                            View Project →
                        </a>
                    </div>

                <?php endforeach; ?>
            </div>

        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact">
        <div class="section-container">

            <div class="contact-box">
                <h2>Let's Connect</h2>

                <p>
                    got questions or want to check out my projects?
                    feel free to click the button below to connect with me.
                </p>

                <div>
                    <button
                        class="btn btn-primary"
                        onclick="toggleContact()"
                    >
                        Contact Me
                    </button>
                </div>

                <div id="contactDropdown" class="contact-dropdown">

                    <a href="mailto:dwii20084@gmail.com" class="contact-item">
                        <span>Email:</span>
                        dwii20084@gmail.com
                    </a>

                    <a href="https://instagram.com/_"
                       target="_blank"
                       class="contact-item">
                        <span>Instagram:</span>
                        @ddwieestc_
                    </a>

                    <a href="https://wa.me/6282230449733"
                       target="_blank"
                       class="contact-item">
                        <span>WhatsApp:</span>
                        +62 822-3044-9733
                    </a>

                    <a href="https://tiktok.com/@dvvi.444"
                       target="_blank"
                       class="contact-item">
                        <span>TikTok:</span>
                        @dvvi.444
                    </a>

                </div>
            </div>

        </div>
    </section>

    <!-- JAVASCRIPT -->
    <script>
        function toggleContact() {
            var dropdown = document.getElementById("contactDropdown");
            dropdown.classList.toggle("active");
        }

        function toggleProfileSettings() {
            var menu = document.getElementById("profileSettingsMenu");
            menu.classList.toggle("active");
        }

        function changeLayout(select) {
            var card = document.getElementById("profileCard");

            if (select.value === "square") {
                card.classList.add("layout-square");
            } else {
                card.classList.remove("layout-square");
            }
        }
    </script>

    <!-- FOOTER -->
    <footer>
        © <?= date('Y') ?>
        <?= htmlspecialchars($nama) ?>. All Rights Reserved.
    </footer>

</body>
</html>