<?php
include_once 'config/config.inc.php';

  if (isset($_GET['redirect'])) {
      $redirect = urldecode($_GET['redirect']);
  }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RBAC Login Portal</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<main>
    <article>
        <header>
            <h1>Welkom bij de login portaal voor de RBAC Demonstratie</h1>
        </header>
        <section>
            <form action="login.php" method="post">
                <label for="username">Username</label>
                <input id="username" name="username" maxlength="30" required>
                <br>
                <label for="password">Password</label>
                <input type="password" required maxlength="30" id="password" name="password">
                <br>
                <?php if (isset($redirect)) {
                    echo "<input type='hidden' name='redirect' value='$redirect'>";
                } ?>

                <button type="submit" aria-label="submit">Login</button>
            </form>
        </section>
    </article>
    <footer>
        <?php
        echo <<< EOF_FOOTER
        <p>
            <span>Last Commit: <a href="mailto:$config_git_email">$config_git_email</a></span>
            <span>@ </span>
            <span>$config_git_date</span>
        </p>
    </footer>
EOF_FOOTER
    ?>
    </main>

</body>
</html>