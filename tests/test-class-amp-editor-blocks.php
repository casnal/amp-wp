<?php
/**
 * Tests for AMP_Editor_Blocks class.
 *
 * @package AMP
 */

/**
 * Tests for AMP_Editor_Blocks class.
 *
 * @covers AMP_Editor_Blocks
 */
class Test_AMP_Editor_Blocks extends \WP_UnitTestCase {

	/**
	 * The tested instance.
	 *
	 * @var AMP_Editor_Blocks
	 */
	public $instance;

	/**
	 * Instantiates the tested class.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		$this->instance = new AMP_Editor_Blocks();
	}

	/**
	 * Test init.
	 *
	 * @covers \AMP_Editor_Blocks::init()
	 */
	public function test_init() {
		$this->instance->init();
		if ( function_exists( 'register_block_type' ) ) {
			$this->assertEquals( 10, has_filter( 'wp_kses_allowed_html', array( $this->instance, 'whitelist_block_atts_in_wp_kses_allowed_html' ) ) );

			// Because amp_is_canonical() is false, these should not be hooked.
			$this->assertFalse( has_filter( 'the_content', array( $this->instance, 'tally_content_requiring_amp_scripts' ) ) );
			$this->assertFalse( has_action( 'wp_print_footer_scripts', array( $this->instance, 'print_dirty_amp_scripts' ) ) );

			add_theme_support( 'amp' );
			$this->instance->init();

			// Now that amp_is_canonical() is true, these action hooks should be added.
			$this->assertEquals( 10, has_filter( 'the_content', array( $this->instance, 'tally_content_requiring_amp_scripts' ) ) );
			$this->assertEquals( 10, has_action( 'wp_print_footer_scripts', array( $this->instance, 'print_dirty_amp_scripts' ) ) );
			remove_theme_support( 'amp' );
		}
	}
}
