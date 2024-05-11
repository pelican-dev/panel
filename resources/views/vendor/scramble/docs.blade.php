<!doctype html>
<html lang="en" data-theme="{{ config('scramble.theme', 'dark') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pelican - {{ str($api ?? 'all')->title() }} API Docs</title>

    <script src="https://unpkg.com/@stoplight/elements/web-components.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/@stoplight/elements/styles.min.css">
</head>
<body style="height: 100vh; overflow-y: hidden">
    <elements-api
        apiDescriptionUrl="{{ route('scramble.docs.' . $api ?? 'all')  }}"
        tryItCredentialsPolicy="{{ config('scramble.ui.try_it_credentials_policy', 'include') }}"
        router="hash"
        @if(config('scramble.ui.hide_try_it')) hideTryIt="true" @endif
        logo="/pelican.svg"
    />
</body>
</html>
