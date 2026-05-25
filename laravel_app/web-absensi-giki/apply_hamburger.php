<?php
$base_dir = 'd:/Semester 5/giki/Giki/laravel_app/web-absensi-giki';
$views_dir = $base_dir . '/resources/views';

$hamburger_html = '<button class="hamburger d-md-none" aria-label="Toggle navigation">
    <span></span>
    <span></span>
    <span></span>
</button>
';

// Add hamburger to views
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($views_dir));
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        $modified = false;
        
        // Inject Hamburger Button
        if (str_contains($content, 'class="page-header') && !str_contains($content, 'class="hamburger')) {
            $content = preg_replace(
                '/(<div class="page-header[^>]*>)/i', 
                '$1' . "\n                " . $hamburger_html, 
                $content
            );
            $modified = true;
        } elseif (str_contains($content, 'class="topbar"') && !str_contains($content, 'class="hamburger')) {
            $content = preg_replace(
                '/(<header class="topbar"[^>]*>)/i', 
                '$1' . "\n                " . $hamburger_html, 
                $content
            );
            $modified = true;
        }

        // Inject JS before </body>
        $js_snippet = <<<JS
<!-- Hamburger Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', function(e) {
            e.stopPropagation();
            hamburger.classList.toggle('active');
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(event) {
            if (sidebar.classList.contains('active') && !event.target.closest('.sidebar') && !event.target.closest('.hamburger')) {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            }
        });
        
        // Close sidebar on link click
        const sidebarLinks = sidebar.querySelectorAll('a:not(.disabled)');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                sidebar.classList.remove('active');
            });
        });
    }
});
</script>
JS;
        if (str_contains($content, '</body>') && !str_contains($content, '<!-- Hamburger Script -->')) {
            $content = str_replace('</body>', "\n    " . $js_snippet . "\n</body>", $content);
            $modified = true;
        }
        
        // Ensure tables can scroll horizontally
        if (preg_match('/<table/i', $content) && !str_contains($content, 'table-responsive')) {
            $content = preg_replace('/(<table[^>]*>[\s\S]*?<\/table>)/i', '<div class="table-responsive">$1</div>', $content);
            $modified = true;
        }

        if ($modified) {
            file_put_contents($file->getPathname(), $content);
            echo "Modified view: " . $file->getFilename() . "\n";
        }
    }
}

// Modify CSS files
$css_files = ['styletu.css', 'stylesiswa.css', 'styleguruu.css'];
$css_content = <<<CSS

/* ==================== HAMBURGER MENU & SIDEBAR OVERRIDE ==================== */
.hamburger {
    display: none;
    width: 40px;
    height: 40px;
    background: none;
    border: none;
    cursor: pointer;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 0;
    z-index: 1000;
    margin-right: 12px;
}

.hamburger span {
    width: 24px;
    height: 2px;
    background: currentColor;
    margin: 4px 0;
    transition: 0.3s;
    display: block;
    border-radius: 2px;
}

.hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}
.hamburger.active span:nth-child(2) {
    opacity: 0;
}
.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -6px);
}

@media (max-width: 768px) {
    .hamburger {
        display: flex;
        color: var(--text-primary, var(--ink));
    }
    
    .sidebar {
        position: fixed !important;
        top: 0 !important;
        left: -100% !important;
        width: 260px !important;
        height: 100vh !important;
        flex-direction: column !important;
        justify-content: flex-start !important;
        align-items: flex-start !important;
        transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        z-index: 9999 !important;
        box-shadow: 4px 0 24px rgba(0,0,0,0.15) !important;
        padding: 20px 15px !important;
        background: var(--bg-sidebar, #fff) !important;
        overflow-y: auto !important;
    }

    [data-theme="dark"] .sidebar {
        box-shadow: 4px 0 24px rgba(0,0,0,0.5) !important;
        background: var(--bg-sidebar, #1e293b) !important;
    }

    .sidebar.active {
        left: 0 !important;
    }
    
    .sidebar .btn-icon {
        width: 100% !important;
        justify-content: flex-start !important;
        padding-left: 15px !important;
        border-radius: 12px !important;
        margin: 5px 0 !important;
        height: 48px !important;
    }
    
    .sidebar .btn-icon i {
        margin-right: 15px;
        width: 20px;
        text-align: center;
    }
    
    /* Add text labels to sidebar buttons on mobile since they have space */
    .sidebar .btn-icon::after {
        content: attr(title);
        font-size: 0.95rem;
        font-weight: 500;
        display: block;
    }
    
    .sidebar-footer {
        width: 100%;
        flex-direction: column !important;
        gap: 0 !important;
        margin-top: auto !important;
        padding-top: 20px !important;
        border-top: 1px solid rgba(128,128,128,0.2) !important;
    }

    /* Page Header adjustments to align hamburger */
    .page-header, .topbar {
        display: flex;
        align-items: center;
        flex-wrap: nowrap !important;
    }
    
    /* Ensure tables can be scrolled horizontally */
    .table-responsive, .panel-body, .status-box, .mapel-table-container {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        width: 100% !important;
    }
}
CSS;

foreach ($css_files as $cf) {
    $path = $base_dir . '/css/' . $cf;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (!str_contains($content, 'HAMBURGER MENU & SIDEBAR OVERRIDE')) {
            // Also we need to disable the horizontal sidebar CSS in 1024px and 768px max-widths,
            // but the `!important` tags above will override them!
            file_put_contents($path, $content . "\n" . $css_content);
            echo "Modified CSS: $cf\n";
        }
    }
}

echo "Done!\n";
?>
