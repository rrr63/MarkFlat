<?php

namespace App\Component;

class GalleryComponent implements MarkdownComponentInterface
{
    public function getPattern(): string
    {
        return '/\[GALLERY\]\s*\n(.*?)\n\[\/GALLERY\]/s';
    }

    public function process(string $content, array $theme): array
    {
        $config = json_decode(trim($content), true);
        if (!$config || !isset($config['images'])) {
            return [
                'html' => '<div class="' . ($theme['error'] ?? 'text-red-500') . '">Error: Invalid gallery configuration</div>',
                'js' => ''
            ];
        }

        $galleryId = 'gallery-' . uniqid();
        $html = sprintf(
            '<div class="%s p-4 rounded-lg" id="%s">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">',
            $theme['container'] ?? 'bg-white dark:bg-gray-800',
            $galleryId
        );

        foreach ($config['images'] as $image) {
            if (!isset($image['src'])) continue;

            $caption = $image['caption'] ?? '';
            $alt = $image['alt'] ?? $caption;
            
            $html .= sprintf(
                '<div class="relative group cursor-pointer" onclick="openLightbox(\'%s\', \'%s\')">
                    <img src="%s" alt="%s" class="w-full h-48 object-cover rounded-lg transition-transform duration-200 group-hover:scale-105">
                    %s
                </div>',
                $galleryId,
                htmlspecialchars($image['src']),
                htmlspecialchars($image['src']),
                htmlspecialchars($alt),
                $caption ? sprintf(
                    '<div class="absolute bottom-0 left-0 right-0 p-2 bg-black bg-opacity-50 text-white rounded-b-lg">
                        <p class="text-sm">%s</p>
                    </div>',
                    htmlspecialchars($caption)
                ) : ''
            );
        }

        $html .= '</div></div>';

        // Lightbox modal
        $html .= sprintf(
            '<div id="%s-modal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center">
                <button onclick="closeLightbox(\'%s\')" class="absolute top-4 right-4 text-white text-xl">&times;</button>
                <img id="%s-image" src="" alt="" class="max-h-[90vh] max-w-[90vw] object-contain">
            </div>',
            $galleryId,
            $galleryId,
            $galleryId
        );

        $js = <<<JS
            function openLightbox(galleryId, imageSrc) {
                const modal = document.getElementById(`\${galleryId}-modal`);
                const image = document.getElementById(`\${galleryId}-image`);
                image.src = imageSrc;
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox(galleryId) {
                const modal = document.getElementById(`\${galleryId}-modal`);
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Close on click outside image
            document.addEventListener('DOMContentLoaded', function() {
                const modals = document.querySelectorAll('[id$="-modal"]');
                modals.forEach(modal => {
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            const galleryId = modal.id.replace('-modal', '');
                            closeLightbox(galleryId);
                        }
                    });
                });
            });
        JS;

        return [
            'html' => $html,
            'js' => $js
        ];
    }

    public function getName(): string
    {
        return 'gallery';
    }
}
