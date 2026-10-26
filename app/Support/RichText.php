<?php

namespace App\Support;

/**
 * Transforme un texte brut saisi dans l'admin (bio, description de projet) en blocs mis en forme.
 *
 * - ligne courte commençant par un emoji (« 💻 Ce que je fais »)          → intertitre
 * - ligne courte sans ponctuation finale, suivie d'un paragraphe          → intertitre
 * - lignes courtes après une phrase finissant par « : »                   → liste à puces
 * - « Libellé : texte » avec un libellé court                             → ligne mise en valeur
 * - le reste                                                              → paragraphe
 */
class RichText
{
    /**
     * @return array<int, array{type: string, text: string, term?: string}>
     */
    public static function parse(?string $text): array
    {
        $lines = array_values(array_filter(
            array_map('trim', preg_split('/\R/u', (string) $text)),
            fn (string $line) => $line !== '',
        ));

        $blocks = [];
        $inList = false;

        foreach ($lines as $i => $line) {
            $isShort = mb_strlen($line) < 60;
            $hasNext = isset($lines[$i + 1]);

            if ($isShort && preg_match('/^[^\p{L}\p{N}]+\s*(.+)$/u', $line, $m)) {
                $inList = false;
                $blocks[] = ['type' => 'heading', 'text' => $m[1]];
                continue;
            }

            if ($inList && mb_strlen($line) < 90) {
                $blocks[] = ['type' => 'item', 'text' => $line];
                continue;
            }

            if ($isShort && $hasNext && !preg_match('/[.:!?;,…»)]$/u', $line)) {
                $inList = false;
                $blocks[] = ['type' => 'heading', 'text' => $line];
                continue;
            }

            $inList = str_ends_with($line, ':');

            if (preg_match('/^(.{2,30}?)\s:\s(.+)$/u', $line, $m)) {
                $blocks[] = ['type' => 'term', 'term' => $m[1], 'text' => $m[2]];
                continue;
            }

            $blocks[] = ['type' => 'text', 'text' => $line];
        }

        return $blocks;
    }
}
