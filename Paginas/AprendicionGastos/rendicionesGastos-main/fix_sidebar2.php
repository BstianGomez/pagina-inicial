<?php
$file = 'resources/views/components/layouts/app.blade.php';
$content = file_get_contents($file);

$inicio_link = <<<HTML
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Inicio">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="nav-label">Inicio</span>
                </a>
HTML;

$analytics_link = <<<HTML
                <a href="{{ route('analytics.index') }}" class="nav-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}" title="Estadísticas">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="nav-label">Estadísticas</span>
                </a>
HTML;

$rendiciones_link = <<<HTML
                <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.index') || request()->routeIs('reports.show') ? 'active' : '' }}" title="Rendiciones">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="nav-label">Rendiciones</span>
                </a>
HTML;

$gastos_link = <<<HTML
                <a href="{{ route('expenses.index') }}" class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}" title="Gastos">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="nav-label">Gastos</span>
                </a>
HTML;

// Remove all of them first based on distinct string matching to rebuild it cleanly
$content = str_replace($inicio_link . "\n", '', $content);
$content = str_replace($analytics_link . "\n", '', $content);
$content = str_replace($rendiciones_link . "\n", '', $content);
$content = str_replace($gastos_link . "\n", '', $content);

// Clean up any stray that might be left due to formatting
$content = str_replace("\n\n\n", "\n\n", $content);

$nav_start = '<nav class="sidebar-nav">';

$new_links = $nav_start . "\n" . 
$inicio_link . "\n\n" . 
$rendiciones_link . "\n\n" . 
$gastos_link . "\n\n" . 
$analytics_link;

$content = str_replace($nav_start, $new_links, $content);

file_put_contents($file, $content);
