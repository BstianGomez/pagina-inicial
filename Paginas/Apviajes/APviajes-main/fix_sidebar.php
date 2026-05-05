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

$content = str_replace($inicio_link, '', $content);

$config_link_end = <<<HTML
                        </svg>
                        <span class="nav-label">Configuracion</span>
                    </a>
                @endhasanyrole
HTML;

$new_config_link_end = $config_link_end . "\n\n" . rtrim($inicio_link);

$content = str_replace($config_link_end, $new_config_link_end, $content);

file_put_contents($file, $content);
