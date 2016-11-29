<?php declare(strict_types=1);

namespace Exhale;

use Sabre\Xml\Service as XML_Service;

/**
 * Extendable interface for creating exporters easily
 */
class Exporter implements Type\XML, Type\WordPress\Endpoints {
    /*
     * Contains all the exportable data
     *
     * @var array
     */
    private $data;

    /*
     * Array of endpoints
     *
     * @var array
     */
    private $endpoints;

    /*
     * XML namespaces
     *
     * @var array
     */
    private $namespaces;

    /*
     * The root element of outputted data
     *
     * @var array
     */
    private $root;

    /*
     * The sabre xml service
     *
     * @var array
     */
    public $xml;

    /**
     * Constructor which can also set data
     */
    public function __construct( array $data = null ) {

        if ( $data !== null ) {
            $this->set_data( $data );
        } else {
            $this->set_data( [] );
        }

        $this->namespaces=[];
        $this->root=[];
        $this->endpoints=[];

        $this->xml = new XML_Service();
    }

    /**
     * Sets the internal associative array data
     */
    public function set_data( array $data ) {
        $this->data = $data;
    }

    /**
     * Sets the internal associative array data
     */
    public function get_data() : array {
        return $this->data;
    }

    /**
     * Push new element into exporter
     */
    public function push_item( string $key, $value, array $attributes = [] ) {
        $this->data[] = [
            'name' => $key,
            'attributes' => $attributes,
            'value' => $value
        ];
    }

    /**
     * Sets the internal associative array data
     */
    public function set_root_element( array $root_element ) {
        if ( ! isset($root_element['name']) ) {
            throw new ArgumentErrorException(" 'name' key was missing from {__FUNCTION__} arguments array ");
        }
        $this->root = $root_element;
    }

    /**
     * Returns root element
     */
    public function xml_root_element() : array {
        return $this->root;
    }

    /**
     * Sets the internal associative array data
     */
    public function add_xml_namespace( array $namespace ) {
        $this->namespaces = array_merge( $this->namespaces, $namespace );
    }

    /**
     * Returns root element
     */
    public function xml_namespaces() : array {
        return $this->namespaces;
    }

    /**
     * Prints the data as xml
     */
    public function to_xml() : string {

        // Use Sabre xml for outputting the data
        $xml_service = new XML_Service();

        // Create Sabre xml writer
        $writer = $this->xml->getWriter();
        $writer->openMemory();
        $writer->setIndent(true);

        // Add all namespaces to xml response
        $writer->namespaceMap = $this->namespaces;

        // Start document with explicit xml version and encoding just to be sure
        $writer->startDocument('1.0', 'UTF-8');

        // Add root element and attributes for root element
        if ( isset($this->root['name']) && ! empty($this->root['name']) ) {

            $writer->startElement($this->root['name']);

            if ( isset($this->root['attributes']) && ! empty($this->root['attributes']) ) {

                foreach ($this->root['attributes'] as $key => $value) {
                    $writer->writeAttribute( $key, $value);
                }
            }
        }

        // Write data from associative array to xml
        $writer->write( $this->data );

        // Close the root element
        if ( isset($this->root['name']) && ! empty($this->root['name']) ) {
            $writer->endElement();
        }

        // Write xml document
        return $writer->outputMemory();
    }

    /**
     * Creates endpoint by hooking into WordPress
     */
    public function create_wp_endpoint(string $url, string $type, string $wp_action = 'after_setup_theme') {

        // Add into endpoints which this class serves
        $path = parse_url( $url, PHP_URL_PATH );
        $this->endpoints[ $path ] = $type;

        if ( ! function_exists('add_action') ) {
            throw new Exception\WordPressFunctionsNotFound( "add_action() was not found", 1);
        }

        return add_action( $wp_action, array( $this, 'serve_wp_endpoint' ) );
    }

    /**
     * This serves the endpoint from WordPress hook
     */
    public function serve_wp_endpoint() {
        if ( isset( $this->endpoints[ $_SERVER['REQUEST_URI'] ] ) ) {

            $response_type = $this->endpoints[ $_SERVER['REQUEST_URI'] ];

            switch ( $response_type ) {
                case 'xml':

                    // Send headers
                    if (! headers_sent() ) {
                        header("Content-type: text/xml");
                    }

                    // Output xml
                    echo $this->to_xml();

                    // Stop immediately
                    die();

                    break;

                default:
                    throw new Exception\NotImplementedException("handler for type {$response_type} endpoints have not been implemented yet");
                    break;
            }
        }
    }

    /**
     * TODO: Prints the data as json
     */
    public function to_json() {
        throw new Exception\NotImplementedException( __FUNCTION__ . " has not been implemented");
    }
}
