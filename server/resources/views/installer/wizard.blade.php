<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installer</title>
    @vite('resources/js/installer/wizard.js')
</head>
<body class="antialiased">
<div id="installer" class="p-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Installation</h1>
    <div data-step="check" class="mb-6">
        <h2 class="text-lg font-medium mb-2">Environment Check</h2>
        <button type="button" data-action="check" class="px-4 py-2 bg-blue-600 text-white rounded">Run Check</button>
        <div class="mt-3" data-result></div>
    </div>
    <div data-step="database" class="mb-6 hidden">
        <h2 class="text-lg font-medium mb-2">Database</h2>
        <form data-form="database" method="post" action="{{ route('install.database') }}">
            @csrf
            <label class="block mb-2">App URL</label>
            <input type="url" name="app_url" class="w-full border rounded px-3 py-2 mb-3" placeholder="https://example.com">
            <label class="block mb-2">Host</label>
            <input type="text" name="host" class="w-full border rounded px-3 py-2 mb-3" value="127.0.0.1">
            <label class="block mb-2">Port</label>
            <input type="number" name="port" class="w-full border rounded px-3 py-2 mb-3" value="3306">
            <label class="block mb-2">Database</label>
            <input type="text" name="database" class="w-full border rounded px-3 py-2 mb-3">
            <label class="block mb-2">Username</label>
            <input type="text" name="username" class="w-full border rounded px-3 py-2 mb-3">
            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2 mb-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
        </form>
        <div class="mt-3" data-db-result></div>
    </div>
    <div data-step="migrate" class="mb-6 hidden">
        <h2 class="text-lg font-medium mb-2">Migrations</h2>
        <form data-form="migrate" method="post" action="{{ route('install.migrate') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Run Migrations</button>
        </form>
        <div class="mt-3" data-migrate-result></div>
    </div>
    <div data-step="admin" class="mb-6 hidden">
        <h2 class="text-lg font-medium mb-2">Admin Account</h2>
        <form data-form="admin" method="post" action="{{ route('install.admin') }}">
            @csrf
            <label class="block mb-2">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2 mb-3">
            <label class="block mb-2">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2 mb-3">
            <label class="block mb-2">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2 mb-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create Admin</button>
        </form>
        <div class="mt-3" data-admin-result></div>
    </div>
    <div data-step="finish" class="mb-6 hidden">
        <h2 class="text-lg font-medium mb-2">Finish</h2>
        <a href="{{ route('install.finish') }}" class="px-4 py-2 bg-green-600 text-white rounded">Go to Login</a>
    </div>
</div>
</body>
</html>
