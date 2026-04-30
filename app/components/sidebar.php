<?php
if (!function_exists('renderSidebar')) {
    function renderSidebar(string $active, string $customContent = ''): void
    {
        $links = [
            'files' => ['href' => 'file-browser.php', 'label' => '📁 Files'],
            'games' => ['href' => 'game-library.php', 'label' => '🎮 Game Library'],
            'chat' => ['href' => 'chat.php', 'label' => '💬 Chat'],
            'tools' => ['href' => 'tools.php', 'label' => '🧰 Tools'],
        ];

        echo '<aside class="sidebar">';
        echo '<a href="index.php" class="brand" style="margin-bottom:14px"><img src="../media/LocalLoot_logo.png" class="brand-logo" alt="LocalLoot Logo"><div><h1 style="font-size:1.2rem">LocalLoot</h1><div class="brand-subtitle" style="color:#6d7f9d;">Local Tools for LAN-Parties</div></div></a>';
        echo '<div style="margin-top:16px;color:#7e8eaa;font-size:.85rem;">MODULES</div>';

        foreach ($links as $key => $link) {
            $activeClass = $key === $active ? ' active' : '';
            echo '<a class="side-link' . $activeClass . '" href="' . $link['href'] . '">' . $link['label'] . '</a>';
        }

        if ($customContent !== '') {
            echo '<div class="side-tools">' . $customContent . '</div>';
        }

        echo '</aside>';
    }
}
