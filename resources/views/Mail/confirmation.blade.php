<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>{{$sujet}}</title>
    <style>
        /* Styles pour le corps de l'e-mail */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        /* Styles pour l'en-tête */
        header {
            background-color: #5fb8ac;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        /* Styles pour le contenu de l'e-mail */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Styles pour les liens */
        a {
            color: #007bff;
            text-decoration: none;
        }

        /* Styles pour le pied de page */
        footer {
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <?php $baseUrl = url('/');?>
    <header>
        <h1>Nouveau compte</h1>
    </header>
    <div class="container">
        <p>Bonjour,</p>
        <p>Votre compte a été créer avec succès. Voici votre mot de passe :</p>
        <p style="font-size: 24px; font-weight: bold;">{{$code}}</p>
        <p style="color: red;">Veuillez penser à le changer après votre connexion</p>
    </div>
    <footer>
        © 2023 Artec | <a href="{{$baseUrl}}">{{$baseUrl}}</a>
    </footer>
</body>
</html>
