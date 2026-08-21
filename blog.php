<?php
declare(strict_types=1);


require_once __DIR__ . '/config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    http_response_code(500);
    die('Impossible de se connecter à la base de données.');
}

$conn->set_charset('utf8mb4');

$error = '';
$username = isset($_COOKIE['username']) ? trim((string) $_COOKIE['username']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $content = trim((string) ($_POST['content'] ?? ''));

    if ($username === '' || $content === '') {
        $error = 'Merci de renseigner votre nom et votre message.';
    } elseif (mb_strlen($username) > 80) {
        $error = 'Le nom est trop long.';
    } elseif (mb_strlen($content) > 3000) {
        $error = 'Le message est trop long.';
    } else {
        setcookie('username', $username, [
            'expires' => time() + (86400 * 30),
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        $stmt = $conn->prepare(
            "INSERT INTO messages (username, content) VALUES (?, ?)"
        );

        if ($stmt) {
            $stmt->bind_param('ss', $username, $content);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();

                header('Location: blog.php');
                exit;
            }

            $stmt->close();
        }

        $error = 'Une erreur est survenue lors de la publication.';
    }
}

$result = $conn->query(
    "SELECT id, username, content FROM messages ORDER BY id DESC"
);

$messageCount = $result ? $result->num_rows : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Blog — Photos Noir et Blanc</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,500;0,9..144,600;1,9..144,400;1,9..144,500&family=Inter:wght@400;500;600&family=Space+Mono&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body class="page-blog">

<header>
  <a href="index.html" class="wordmark">
    Photos <em>Noir & Blanc</em>
  </a>

  <button
    class="menu-toggle"
    type="button"
    aria-label="Ouvrir le menu"
    aria-expanded="false"
  >
    <span></span>
    <span></span>
  </button>

  <nav>
    <a href="galerie.html">Galerie</a>
    <a href="services.html">Services</a>
    <a href="tarifs.html">Tarifs</a>
    <a href="evenement.html">Événements</a>
    <a href="blog.php" aria-current="page">Blog</a>
    <a href="contact.html">Contact</a>
  </nav>
</header>

<main>

  <section class="blog-hero">
    <div class="tag mono">Journal / communauté</div>

    <h1>
      Des mots laissés <em>après les images.</em>
    </h1>

    <p>
      Partagez votre expérience, un souvenir, une impression ou quelques mots
      sur le travail photographique. Chaque message rejoint cette archive collective.
    </p>
  </section>

  <section class="blog-layout">

    <aside class="blog-form-wrap">

      <div class="section-label mono">Écrire</div>
      <h2>Laissez une trace.</h2>

      <?php if ($error !== ''): ?>
        <div class="blog-alert error">
          <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <form action="blog.php" method="post">

        <div class="blog-field">
          <label for="username">Votre nom</label>

          <input
            id="username"
            type="text"
            name="username"
            maxlength="80"
            value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
            autocomplete="name"
            required
          >
        </div>

        <div class="blog-field">
          <label for="content">Votre message</label>

          <textarea
            id="content"
            name="content"
            maxlength="3000"
            placeholder="Écrivez quelques mots…"
            required
          ></textarea>
        </div>

        <button class="blog-submit" type="submit">
          Publier
        </button>

      </form>
    </aside>

    <section class="blog-messages">

      <div class="blog-messages-head">
        <div>
          <div class="section-label mono">Archives</div>
          <h2>Messages</h2>
        </div>

        <span class="blog-count mono">
          <?= str_pad((string) $messageCount, 2, '0', STR_PAD_LEFT) ?>
          message<?= $messageCount > 1 ? 's' : '' ?>
        </span>
      </div>

      <?php if ($result && $messageCount > 0): ?>

        <?php
        $index = 1;

        while ($row = $result->fetch_assoc()):
        ?>

          <article class="blog-message">

            <div class="blog-message-meta">

              <div class="blog-message-author">
                <?= htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8') ?>
              </div>

              <span class="blog-message-number mono">
                N°<?= str_pad((string) $index, 2, '0', STR_PAD_LEFT) ?>
              </span>

            </div>

            <p><?= htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8') ?></p>

          </article>

        <?php
          $index++;
        endwhile;
        ?>

      <?php else: ?>

        <div class="blog-empty">
          Aucun message pour le moment. Soyez la première personne à laisser quelques mots.
        </div>

      <?php endif; ?>

    </section>

  </section>

</main>

<footer>

  <div class="foot-top">

    <div class="wordmark2">
      Photos Noir & Blanc
    </div>

    <div class="foot-links">

      <div>
        <h4>Navigation</h4>
        <a href="galerie.html">Galerie</a>
        <a href="services.html">Services</a>
        <a href="tarifs.html">Tarifs</a>
        <a href="evenement.html">Événements</a>
        <a href="blog.php">Blog</a>
      </div>

      <div>
        <h4>Contact</h4>
        <a
          href="https://wa.me/41797965654"
          target="_blank"
          rel="noopener"
        >
          WhatsApp
        </a>
        <a href="contact.html">Formulaire</a>
      </div>

    </div>

  </div>

  <div class="foot-bottom">
    <span>© 2026 Photos Noir & Blanc</span>
    <span>Mentions légales</span>
  </div>

</footer>

<script>
const menuToggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('header nav');

if (menuToggle && nav) {
  menuToggle.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('active');

    menuToggle.classList.toggle('active', isOpen);
    menuToggle.setAttribute('aria-expanded', String(isOpen));
    menuToggle.setAttribute(
      'aria-label',
      isOpen ? 'Fermer le menu' : 'Ouvrir le menu'
    );

    document.body.classList.toggle('menu-open', isOpen);
  });

  nav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      nav.classList.remove('active');
      menuToggle.classList.remove('active');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Ouvrir le menu');
      document.body.classList.remove('menu-open');
    });
  });

  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && nav.classList.contains('active')) {
      nav.classList.remove('active');
      menuToggle.classList.remove('active');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Ouvrir le menu');
      document.body.classList.remove('menu-open');
      menuToggle.focus();
    }
  });
}
</script>

</body>
</html>

<?php
if ($result) {
    $result->free();
}

$conn->close();
?>
