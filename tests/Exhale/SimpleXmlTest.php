<?php declare (strict_types=1);

namespace Exhale;

/**
 * Simple test for the XML output
 */
class SimpleXmlTest extends \PHPUnit_Framework_TestCase {
    function setUp() {
        $this->stack = new Exporter( [ 'key' => 'value' ] );
    }
    function testXmlOutput() {
        // trim because whitespace doesn't matter
        $this->assertEquals( trim( $this->stack->to_xml() ), "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<key>value</key>" );
    }

    function testXmlOutputWithAddedKeys() {
        // trim because whitespace doesn't matter
        $this->stack->push_item( 'something', 'anything' );
        $this->assertEquals( trim( $this->stack->to_xml() ), "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<key>value</key>\n<something>anything</something>" );
    }

    function testXmlOutputWithAddedInnerKeys() {
        // trim because whitespace doesn't matter
        $this->stack->push_item( 'something', array( 'anything' => 'test' ) );
        $this->assertEquals( trim( $this->stack->to_xml() ), "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<key>value</key>\n<something>\n <anything>test</anything>\n</something>" );
    }
}
