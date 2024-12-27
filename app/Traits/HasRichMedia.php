<?php

/* 
/**
 * EXPERIMENTAL
 * 
 * This class aims to provide a simple way to manage database interaction which helps
 * to reduce boilerplate code and standardize database operations.
 * Not recommended for production use without proper testing and validation.
 * 
 * The goal is to simplify adding attachements to filament's rich text editor
 * through spatie media library, while maintaining a flexible way to customize it and
 * keeping the codebase DRY (Don't Repeat Yourself).
 * 
 * @experimental This is an experimental feature and may change in future versions
 * @see Database For core database functionality
 * @author Your Name <your.email@example.com>
 * @version 0.1.0
 */


namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use DOMDocument;
use DOMElement;

trait HasRichMedia
{
    public static $richFields = ['content'];

    public static function bootHasRichMedia()
    {
        static::creating(function ($model) {
            Log::debug('HasRichMedia: Starting create hook', [
                'model' => get_class($model),
                'fields' => self::$richFields
            ]);

            foreach (self::$richFields as $field) {
                if (!empty($model->{$field})) {
                    Log::debug('HasRichMedia: Processing field', [
                        'field' => $field,
                        'content_length' => strlen($model->{$field})
                    ]);
                    $model->{$field} = self::processMedia($model, $model->{$field});
                }
            }
        });
    }

    private static function processMedia($model, $content)
    {
        Log::debug('HasRichMedia: Starting processMedia', [
            'content_sample' => Str::limit($content, 100)
        ]);

        if (empty($content)) {
            Log::debug('HasRichMedia: Empty content, skipping');
            return $content;
        }

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);

        Log::debug('HasRichMedia: Loading HTML content');
        $dom->loadHTML(
            mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $figures = $dom->getElementsByTagName('figure');
        Log::debug('HasRichMedia: Found figures', [
            'count' => $figures->length
        ]);

        foreach ($figures as $figure) {
            if (!$figure instanceof DOMElement || !$figure->hasAttribute('data-trix-attachment')) {
                continue;
            }

            $attachmentData = json_decode($figure->getAttribute('data-trix-attachment'), true);
            Log::debug('HasRichMedia: Processing figure attachment', [
                'data' => $attachmentData
            ]);

            if (!$attachmentData || !isset($attachmentData['url'])) {
                Log::warning('HasRichMedia: Invalid attachment data');
                continue;
            }

            try {
                if (Str::startsWith($attachmentData['url'], 'data:image')) {
                    $media = self::processBase64Image($model, $attachmentData);

                    if ($media) {
                        Log::debug('HasRichMedia: Successfully processed base64 image', [
                            'media_url' => $media->getUrl()
                        ]);

                        self::replaceFigureWithImg($dom, $figure, $media, $attachmentData);
                    }
                }
            } catch (\Exception $e) {
                Log::error('HasRichMedia: Error processing media', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::debug('HasRichMedia: Completed processing media');
        return $dom->saveHTML();
    }

    private static function processBase64Image($model, $attachmentData)
    {
        Log::debug('HasRichMedia: Processing base64 image', [
            'filename' => $attachmentData['filename'] ?? 'unknown'
        ]);

        $base64Image = preg_replace('#^data:image/\w+;base64,#i', '', $attachmentData['url']);
        $tempFile = tempnam(sys_get_temp_dir(), 'rich_media_');

        Log::debug('HasRichMedia: Created temp file', [
            'path' => $tempFile
        ]);

        file_put_contents($tempFile, base64_decode($base64Image));

        try {
            $media = $model->addMedia($tempFile)
                ->usingName(pathinfo($attachmentData['filename'], PATHINFO_FILENAME))
                ->withCustomProperties([
                    'original_name' => $attachmentData['filename'],
                    'content_type' => $attachmentData['contentType'] ?? null
                ])
                ->toMediaCollection('rich-content');

            Log::debug('HasRichMedia: Media added to library', [
                'media_id' => $media->id,
                'url' => $media->getUrl()
            ]);

            return $media;
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
                Log::debug('HasRichMedia: Cleaned up temp file');
            }
        }
    }

    private static function replaceFigureWithImg($dom, $figure, $media, $attachmentData)
    {
        $newImg = $dom->createElement('img');
        $newImg->setAttribute('src', $media->getUrl());

        if (isset($attachmentData['width'])) {
            $newImg->setAttribute('width', $attachmentData['width']);
        }
        if (isset($attachmentData['height'])) {
            $newImg->setAttribute('height', $attachmentData['height']);
        }

        $figure->parentNode->replaceChild($newImg, $figure);

        Log::debug('HasRichMedia: Replaced figure with img', [
            'src' => $media->getUrl()
        ]);
    }
}
