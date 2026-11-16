<?php

namespace App\Support;

class ServiceCatalog
{
    /**
     * Each entry: slug => [label, group, icon, color]
     * "icon" refers to a key rendered by resources/views/dashboard/numbers/_icon.blade.php
     * (generic shapes only — not the platforms' actual logos/trademarks).
     */
    public static function all(): array
    {
        return [
            // AI training & data work platforms
            'ai-training' => ['label' => 'AI Training Services', 'group' => 'AI & Data Work', 'icon' => 'cpu', 'color' => '#4f72f0'],
            'data-annotation' => ['label' => 'Data Annotation', 'group' => 'AI & Data Work', 'icon' => 'tag', 'color' => '#7397f7'],
            'outlier-ai' => ['label' => 'Outlier AI', 'group' => 'AI & Data Work', 'icon' => 'target', 'color' => '#3750e4'],
            'handshake' => ['label' => 'Handshake', 'group' => 'AI & Data Work', 'icon' => 'briefcase', 'color' => '#2c3ecb'],
            'mercor' => ['label' => 'Mercor', 'group' => 'AI & Data Work', 'icon' => 'layers', 'color' => '#4f72f0'],
            'scale-ai' => ['label' => 'Scale AI', 'group' => 'AI & Data Work', 'icon' => 'scale', 'color' => '#191d4d'],
            'appen' => ['label' => 'Appen', 'group' => 'AI & Data Work', 'icon' => 'globe', 'color' => '#2934a3'],
            'lionbridge-ai' => ['label' => 'Lionbridge AI', 'group' => 'AI & Data Work', 'icon' => 'lion', 'color' => '#7397f7'],
            'telus-international' => ['label' => 'TELUS International', 'group' => 'AI & Data Work', 'icon' => 'headset', 'color' => '#3750e4'],
            'clickworker' => ['label' => 'Clickworker', 'group' => 'AI & Data Work', 'icon' => 'cursor', 'color' => '#4f72f0'],
            'remotask' => ['label' => 'Remotask', 'group' => 'AI & Data Work', 'icon' => 'task', 'color' => '#2c3ecb'],

            // Messaging & social
            'whatsapp' => ['label' => 'WhatsApp', 'group' => 'Messaging & Social', 'icon' => 'chat', 'color' => '#25D366'],
            'telegram' => ['label' => 'Telegram', 'group' => 'Messaging & Social', 'icon' => 'paper-plane', 'color' => '#229ED9'],
            'signal' => ['label' => 'Signal', 'group' => 'Messaging & Social', 'icon' => 'shield-chat', 'color' => '#3A76F0'],
        ];
    }

    public static function groups(): array
    {
        $groups = [];

        foreach (self::all() as $slug => $service) {
            $groups[$service['group']][$slug] = $service;
        }

        return $groups;
    }

    public static function label(string $slug): string
    {
        return self::all()[$slug]['label'] ?? $slug;
    }
}
