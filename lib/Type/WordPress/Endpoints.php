<?php declare(strict_types=1);

namespace Exhale\Type\Wordpress;

/**
 * Serve endpoints in WordPress
 */
interface Endpoints {
    public function create_wp_endpoint( string $path, string $type );
    public function serve_wp_endpoint();
}
