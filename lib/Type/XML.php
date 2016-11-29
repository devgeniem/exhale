<?php declare(strict_types=1);

namespace Exhale\Type;

/**
 * Extendable interface for creating exporters easily
 */
interface XML {
    public function xml_namespaces() : array;
    public function xml_root_element() : array;
    public function to_xml() : string;
}
