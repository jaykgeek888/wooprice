<?php
/*
 Plugin Name: WooPrice
 Plugin URI: https://marvelwp.com/wooprice
 Description: Simple plugin for displaying woocommerce product price by productID and shortcode.
 Version: 1.0
 Author: Jayson Supsup
 Author URI: https://marvelwp.com
 License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function mwp_wooprice_shortcode_callback( $atts ) { 
    $atts = shortcode_atts( array(
        'id' => null,
    ), $atts, 'product_price' );

    $html = '';

    if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ){
        // Get an instance of the WC_Product object
        $product = wc_get_product( intval( $atts['id'] ) );

        // Get the product prices
        $price = $product->get_price(); // Get the active price
        $regular_price = $product->get_regular_price(); // Get the regular price
        $sale_price = $product->get_sale_price(); // Get the sale price

        // Your price CSS styles
        $style1 = 'style="text-decoration:none;"';
        $style2 = 'style="text-decoration:none;"';

        // Formatting price settings (for the wc_price() function)
        $args = array(
            'ex_tax_label'       => false,
            'currency'           => 'EUR',
            'decimal_separator'  => '.',
            'thousand_separator' => ' ',
            'decimals'           => 2,
            'price_format'       => '%2$s&nbsp;%1$s',
        );

        // Formatting html output
        if( ! empty( $sale_price ) && $sale_price != 0 && $sale_price < $regular_price )
            $html = "<del $style2>" . wc_price( $regular_price, $args ) . "</del> <ins $style1>" . wc_price( $sale_price, $args ) . "</ins>"; // Sale price is set
        else
            $html = "<ins $style1>" . wc_price( $price, $args ) . "</ins>"; // No sale price set
    }
    return $html;
}
add_shortcode( 'wooprice', 'mwp_wooprice_shortcode_callback' );

function mwp_wooprice_vat_shortcode_callback( $atts ) { 
    $atts = shortcode_atts( array(
        'id' => null,
    ), $atts, 'product_price' );

    $vathtml = '';

    if( intval( $atts['id'] ) > 0 && function_exists( 'wc_get_product' ) ){
        // Get an instance of the WC_Product object
        $product = wc_get_product( intval( $atts['id'] ) );

        // Get the product prices
        $price = $product->get_price(); // Get the active price
        $regular_price = $product->get_regular_price(); // Get the regular price
        $sale_price = $product->get_sale_price(); // Get the sale price 
        $price_ex_vat = $product->get_price_excluding_tax(); 
        // Formatting price settings (for the wc_price() function)
        $args = array(
            'ex_tax_label'       => false,
            'currency'           => 'EUR',
            'decimal_separator'  => '.',
            'thousand_separator' => ' ',
            'decimals'           => 2,
            'price_format'       => '%2$s&nbsp;%1$s',
        );

        // Formatting html output 
        $vathtml = "<ins style='text-decoration:none;'>" . wc_price( $price_ex_vat, $args ) . "</ins>"; // No sale price set
    }
    return $vathtml;
} 
add_shortcode( 'wooprice_vat', 'mwp_wooprice_vat_shortcode_callback' );
 
