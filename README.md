# PHP_Laravel11_Tall_Toasts


## Project Description

PHP_Laravel11_Tall_Toasts is a simple Laravel 11 project demonstrating modern toast notifications using the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire).
This project allows users to trigger success, error, and info messages displayed in a beautiful modern card on the welcome page.

The project is ideal for learning TALL stack notifications or integrating modern toasts in your Laravel application.


## Features

- Modern card-based UI with responsive design.

- Success, Error, and Info toast notifications inside the card.

- Fully powered by the TALL stack for real-time updates.

- Easy to extend with additional toast types or custom messages.

- Clean and minimalistic UI for beginner-friendly demonstration.

- Compatible with Laravel 11.



## Technologies Used

1. Laravel 11 – Backend framework.

2. Livewire 3.x – Real-time reactive components.

3. Tailwind CSS – Modern utility-first CSS framework.

4. Alpine.js – Minimal JavaScript framework for interactivity.

5. Tall Toasts Package – usernotnull/tall-toasts for modern toast notifications.

6. PHP 8.2+ – Required for Laravel 11.
---



## Installation Steps


---


## STEP 1: Create Laravel 11 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel11_Tall_Toasts "11.*"

```

### Go inside project:

```
cd PHP_Laravel11_Tall_Toasts

```

#### Explanation:

This installs a fresh Laravel 11 project and navigates into the project directory.




## STEP 2: Install Livewire

### Tall-toasts works with the TALL stack.

```
composer require livewire/livewire:^3.7 -W

```

#### Explanation:

Tall Toasts requires the TALL stack (Tailwind + Alpine + Laravel + Livewire). This installs Livewire.




## STEP 3: Install Tall Toast Package

### Install the package:

```
composer require usernotnull/tall-toasts

```

### Install:

```
npm install alpinejs

npm run dev

```

### Open : app.JS

```
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

```



#### Explanation:

Installs the Tall Toast package to show modern toast notifications in your Laravel app.


## STEP 4: Add Facade Alias for Toast

### Open: config/app.php

#### Add the alias inside 'aliases' array:

```
'aliases' => [
    // other aliases...
    'Toast' => Usernotnull\TallToasts\Facades\Toast::class,
],

```

#### Explanation:

This allows you to use the Toast facade instead of manually passing session data.





## STEP 5: (Optional) Reinstall Tall Toasts on Windows

### If installation fails on Windows:

```
rmdir /s /q vendor
del composer.lock
composer install
composer require usernotnull/tall-toasts

```

#### Explanation:

Cleans the vendor folder and lock file, then reinstalls all packages fresh.



## STEP 6: Clear Laravel caches

### Always clear caches after installation:

```
php artisan config:clear
php artisan cache:clear
php artisan view:clear
composer dump-autoload

```

#### Explanation:

Ensures Laravel recognizes new packages, facades, and configuration changes.



## STEP 7: Publish Package Assets

### Run:

```
php artisan vendor:publish --tag=tall-toasts-config

```

#### Explanation:

Publishes the package configuration to config/tall-toasts.php, allowing you to customize toast behavior.




## STEP 8: Create Controller

### Run:

```
php artisan make:controller ToastController

```

### Open: app/Http/Controllers/ToastController.php

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ToastController extends Controller
{
    // Show success toast
    public function success()
    {
        return redirect('/')->with('toast', [
            'type' => 'success',
            'message' => 'Data saved successfully!'
        ]);
    }

    // Show error toast
    public function error()
    {
        return redirect('/')->with('toast', [
            'type' => 'error',
            'message' => 'Something went wrong!'
        ]);
    }

    // Show info toast
    public function info()
    {
        return redirect('/')->with('toast', [
            'type' => 'info',
            'message' => 'This is an info message!'
        ]);
    }
}

```

#### Explanation:

Controller contains three methods to show success, error, and info toast messages.




## STEP 9: Create Routes

### Open: routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToastController;

// Main welcome page
Route::get('/', function () {
    return view('welcome');
});

// Toast test routes
Route::get('/success', [ToastController::class, 'success']);
Route::get('/error', [ToastController::class, 'error']);
Route::get('/info', [ToastController::class, 'info']);

```

#### Explanation:

Routes are defined for the welcome page and toast triggers




## STEP 10: Create View

### Open: resources/views/welcome.blade.php

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Toast animations */
        @keyframes toastIn {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes toastOut {
            0% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen font-sans">

    <!-- Modern card -->
    <div class="bg-white shadow-2xl rounded-3xl p-8 md:p-12 flex flex-col items-center gap-6 w-full max-w-md relative">

        <!-- Welcome text -->
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 text-center">Welcome!</h1>
        <p class="text-gray-500 text-center md:text-lg">Select an action below to see toast notifications.</p>

        <!-- Toast message inside card -->
        @if(session('toast'))
            @php
                $type = session('toast')['type'];
                $message = session('toast')['message'];
                $color = match($type) {
                    'success' => 'green',
                    'error' => 'red',
                    'info' => 'blue',
                    default => 'gray',
                };
            @endphp

            <div id="toast"
                 class="w-full px-6 py-3 rounded-xl text-white font-medium text-center
                        bg-{{ $color }}-600 shadow-lg opacity-0 mb-4">
                {{ $message }}
            </div>

            <script>
                const toast = document.getElementById('toast');
                if(toast) {
                    toast.style.opacity = '1';
                    toast.style.animation = 'toastIn 0.4s forwards';

                    setTimeout(() => {
                        toast.style.animation = 'toastOut 0.4s forwards';
                    }, 3000);
                }
            </script>
        @endif

        <!-- Action buttons -->
        <div class="flex flex-wrap justify-center gap-4 w-full mt-2">
            <a href="/success" 
               class="flex-1 text-center px-6 py-3 bg-green-500 text-white rounded-xl shadow-md hover:bg-green-600 hover:shadow-lg transition-all duration-300">
               Success
            </a>
            <a href="/error" 
               class="flex-1 text-center px-6 py-3 bg-red-500 text-white rounded-xl shadow-md hover:bg-red-600 hover:shadow-lg transition-all duration-300">
               Error
            </a>
            <a href="/info" 
               class="flex-1 text-center px-6 py-3 bg-blue-500 text-white rounded-xl shadow-md hover:bg-blue-600 hover:shadow-lg transition-all duration-300">
               Info
            </a>
        </div>

    </div>

</body>
</html>

```

#### Explanation:

This view shows a modern card with a welcome message, buttons, and toast messages appear inside the card.




## STEP 11: Run Project

### Run:

```
php artisan serve

```

### Open

```
http://127.0.0.1:8000

```

#### Explanation:

Starts the Laravel development server and opens the welcome page.



## Expected Output:


### Welcome Page:


<img width="1905" height="951" alt="Screenshot 2026-03-05 131059" src="https://github.com/user-attachments/assets/522467cb-012e-42a6-9909-12f7b44e2d16" />


### Success Message:


<img width="1919" height="940" alt="Screenshot 2026-03-05 131114" src="https://github.com/user-attachments/assets/c2467073-de0c-45e1-a84f-78ae6519a803" />


### Error Message:


<img width="1919" height="961" alt="Screenshot 2026-03-05 131123" src="https://github.com/user-attachments/assets/6f3979aa-5e48-4316-a3b7-ab9d8fa39780" />


### Info Message:


<img width="1919" height="946" alt="Screenshot 2026-03-05 131134" src="https://github.com/user-attachments/assets/07111b45-1df6-4b65-95ff-c89213254513" />


---

# Project Folder Structure:

```
PHP_Laravel11_Tall_Toasts/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ToastController.php       <-- Your controller for toast actions
│   │   ├── Kernel.php
│   │   └── ...
│   ├── Models/
│   └── ...
├── bootstrap/
│   └── app.php
├── config/
│   └── tall-toasts.php                  <-- Config after publishing the package
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── index.php
├── resources/
│   ├── views/
│   │   └── welcome.blade.php            <-- Your main welcome page
│   └── css/
├── routes/
│   └── web.php                           <-- Routes for home + toast
├── storage/
├── tests/
├── vendor/                               <-- Composer packages (livewire, tall-toasts)
├── composer.json
├── artisan
└── README.md

```
