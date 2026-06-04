<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = User::where('username', 'adminjeje')->first();
Auth::login($user);

// Share empty errors bag
view()->share('errors', new \Illuminate\Support\ViewErrorBag);

$html = view('profile.settings')->render();
echo "Contains search-box form? " . (strpos($html, 'class="search-box"') !== false ? 'YES' : 'NO') . "\n";
echo "Contains Search books? " . (strpos($html, 'placeholder="Search books"') !== false ? 'YES' : 'NO') . "\n";
