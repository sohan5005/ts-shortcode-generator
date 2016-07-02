<?php
/**
 * Sample shortcode generator template array
 * 
 * Follow the structure to make your own
 */

return array(
	array(
		'name' => 'tab_1',
		'title' => __( 'Tab 1', 'textdomain' ),
		'desc' => __( 'This is a sample tab of shortcodes.', 'textdomain' ),
		'shortcodes' => array(
			array(
				'tag' => 'shortcode_sample_1',
				'title' => __( 'Sample shortcode 1', 'textdomain' ),
				'desc' => __( 'This is a shortcode sample.', 'textdomain' ),
				'selftag' => true, // if the shortcode should not contain any content
//                'icon' => 'path/to/icon',
				'icon' => 'fa-flag', // Font-awesome can be used
				'atts' => array(
					'toggle_attribute' => array(
						'title' => __( 'Toggle field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'toggle',
						'default' => true,
					),
					'color' => array(
						'title' => __( 'Color field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'color',
						'default' => true,
					),
					'select_attribute' => array(
						'title' => __( 'Select field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'select',
						'default' => true,
						'vals' => array(
							array(
								'val' => 'value_1',
								'label' => __( 'Value 1', 'textdomain' ),
							),
							array(
								'val' => 'value_2',
								'label' => __( 'Value 2', 'textdomain' ),
							),
							array(
								'val' => 'value_3',
								'label' => __( 'Value 3', 'textdomain' ),
							),
						),
					),
				),
			),
			array(
				'tag' => 'shortcode_sample_2',
				'title' => __( 'Sample shortcode 2', 'textdomain' ),
				'desc' => __( 'This is a shortcode sample.', 'textdomain' ),
				'content' => 'richedit', // Accepted content as WP Editor
				'icon' => 'fa-th-large', // Font-awesome can be used
				'atts' => array(
					'slider_attribute' => array(
						'title' => __( 'slider field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'slider',
						'default' => 1,
						'min' => 1,
						'max' => 10,
						'step' => 1,
					),
					'multiselect_attribute' => array(
						'title' => __( 'Multiselect field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'multiselect',
						'default' => true,
						'vals' => array(
							array(
								'val' => 'value_1',
								'label' => __( 'Value 1', 'textdomain' ),
							),
							array(
								'val' => 'value_2',
								'label' => __( 'Value 2', 'textdomain' ),
							),
							array(
								'val' => 'value_3',
								'label' => __( 'Value 3', 'textdomain' ),
							),
						),
					),
				),
			),
		),
	),
	array(
		'name' => 'tab_2',
		'title' => __( 'Repeatable shortcode sample', 'textdomain' ),
		'desc' => __( 'This is a sample tab of shortcodes.', 'textdomain' ),
		'shortcodes' => array(
			array(
				'tag' => 'repeatable_parent',
				'title' => __( 'Repeatable short', 'textdomain' ),
				'desc' => __( 'Here we will use the content as another shortcode.', 'textdomain' ),
				'icon' => 'fa-flag', // Font-awesome can be used
				'atts' => array(
					'toggle_attributr' => array(
						'title' => __( 'Toggle field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'toggle',
						'default' => true,
					),
					'select_attribute' => array(
						'title' => __( 'Select field', 'textdomain' ),
						'desc' => __( 'Description', 'textdomain' ),
						'type' => 'select',
						'default' => true,
						'vals' => array(
							array(
								'val' => 'value_1',
								'label' => __( 'Value 1', 'textdomain' ),
							),
							array(
								'val' => 'value_2',
								'label' => __( 'Value 2', 'textdomain' ),
							),
							array(
								'val' => 'value_3',
								'label' => __( 'Value 3', 'textdomain' ),
							),
						),
					),
				),
				'content_title' => __( 'Add/Remove childs', 'ts_core' ),
				'content_desc' => __( 'You can add/remove or rearrange shortcodes from here', 'ts_core' ),
				'content' => array(
					'tag' => 'repeatble_child',
					'title' => __( 'Child', 'textdomain' ),
					'desc' => __( 'This is a shortcode sample.', 'textdomain' ),
					'content' => 'richedit', // Accepted content as WP Editor
					'sortable' => true, // Allow user to sort the elements or not
					'repeatable' => true, // Repeate this shortcode or not
					'atts' => array(
						'icon_attribute' => array(
							'title' => __( 'Icon', 'textdomain' ),
							'desc' => __( 'Description', 'textdomain' ),
							'type' => 'icon',
						),
					),
				),
			),
		),
	),
);