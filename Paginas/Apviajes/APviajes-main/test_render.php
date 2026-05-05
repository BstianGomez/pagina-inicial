<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::login(\App\Models\User::first());
echo route('reports.show', 201) . "\n";
$html = app('router')->dispatch(Request::create('/informes/201', 'GET'))->getContent();
file_put_contents('output_html.txt', $html);
