<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Réinitialiser votre mot de passe</title>
</head>

<body>
    <h1>Réinitialisation du mot de passe</h1>
    <p>Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous :</p>
    <p>
        <a href="{{ route('reset-password') }}?token={{ $token }}&user={{ $model }}">
            Réinitialiser mon mot de passe
        </a>
    </p>
    <p>Si vous n'avez pas demandé ce changement, ignorez cet email.</p>
</body>

</html>
