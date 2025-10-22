<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>404 - Page non trouvée</title>

    <!-- Font Awesome + SB Admin 2 CSS -->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body {
            background-color: #fff;
        }
        .error-page {
            min-height: 100vh;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 800;
        }
        .icon-large {
            font-size: 5rem;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center error-page text-center">

    <div>
        <div class="mb-4">
            <i class="fas fa-search text-primary icon-large"></i>
        </div>

        <h1 class="error-code text-primary">404</h1>
        <h2 class="h4 text-dark mb-3">Page non trouvée</h2>

        <p class="text-muted mb-4" style="max-width: 600px; margin:auto;">
            Oups ! La page que vous recherchez n’existe pas ou a été déplacée.
        </p>

        <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-home mr-2"></i> Retour à l'accueil
        </a>

        <p class="text-muted small mt-4">
            <i class="fas fa-info-circle mr-1"></i> Vérifiez l’URL ou contactez l’administrateur si le problème persiste.
        </p>
    </div>

    <!-- JS -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
