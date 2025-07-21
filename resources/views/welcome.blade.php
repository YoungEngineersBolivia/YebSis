<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite('resources/css/app.css')

    </head>
    <body>
    <!-- resources/views/users.blade.php -->
<div class="overflow-hidden rounded-lg border border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 border-b">
        <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">Users</h3>
            <p class="mt-1 text-sm text-gray-500">
                A list of all the users in your account including their name, title, email and role.
            </p>
        </div>
        <button class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500">
            Add user
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Lindsay Walton</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Front-end Developer</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">lindsay.walton@example.com</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Member</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Courtney Henry</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Designer</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">courtney.henry@example.com</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Admin</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Tom Cook</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Director of Product</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">tom.cook@example.com</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Member</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Whitney Francis</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Copywriter</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">whitney.francis@example.com</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Admin</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Leonard Krasner</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Senior Designer</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">leonard.krasner@example.com</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Owner</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
                <tr>
                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">Floyd Miles</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Principal Designer</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">floyd.miles@example.com</td>
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">Member</td>
                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                        <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


    

    </body>
</html>
