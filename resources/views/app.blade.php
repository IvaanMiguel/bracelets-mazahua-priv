<!DOCTYPE html>
<html
  class="h-svh"
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>

<head>
  <meta charset="utf-8">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1"
  >

  <title inertia>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link
    href="https://fonts.bunny.net"
    rel="preconnect"
  >
  <link
    href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
    rel="stylesheet"
  />
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:FILL@0..1"
    rel="stylesheet"
  >
  <link
    href="https://fonts.googleapis.com"
    rel="preconnect"
  >
  <link
    href="https://fonts.gstatic.com"
    rel="preconnect"
    crossorigin
  >
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet"
  >
  <!-- Scripts -->
  @routes
  @vite(['resources/js/app.ts', "resources/js/Pages/{$page['component']}.vue"])
  @inertiaHead
</head>

<body class='h-svh bg-light-background dark:bg-dark-background font-sans antialiased overflow-hidden'>
  @inertia
</body>

</html>
