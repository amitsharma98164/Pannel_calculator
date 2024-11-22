<?php
/**
 * Plugin Name: Custom Calculator
 * Description: A custom calculator plugin to perform predefined calculations.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue scripts and styles
function custom_calculator_enqueue_scripts() {
    wp_enqueue_script(
        'custom-calculator-script',
        plugin_dir_url(__FILE__) . 'js/calculator.js',
        array('jquery'),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'custom_calculator_enqueue_scripts');

// Shortcode to display the calculator
function custom_calculator_shortcode() {
    ob_start();
    ?>
    <div id="calculator">
        <label for="width">Width (D3):</label>
        <input type="number" id="width" value="8" step="1" min="0">
        <br>
        <label for="height">Height (E3):</label>
        <input type="number" id="height" value="3" step="1" min="0">
        <br><br>
        <div id="results">
            <p>Feet Width (D4): <span id="feet-width"></span></p>
            <p>Feet Height (E4): <span id="feet-height"></span></p>
            <p>Total Cabinets (G3): <span id="total-cabinets"></span></p>
            <p>Inches Width (D5): <span id="inches-width"></span></p>
            <p>Inches Height (E5): <span id="inches-height"></span></p>
            <p>Meters Width (D6): <span id="meters-width"></span></p>
            <p>Meters Height (E6): <span id="meters-height"></span></p>
            <p>Resolution Width (D7): <span id="res-width"></span></p>
            <p>Resolution Height (E7): <span id="res-height"></span></p>
            <p>Total Resolution (G7): <span id="total-resolution"></span></p>
            <p>Diagonal (G8): <span id="diagonal"></span></p>
            <p>Area (G9): <span id="area"></span></p>
            <p>Weight (G10): <span id="weight"></span></p>
            <p>Max Power (G11): <span id="max-power"></span></p>
            <p>Power Circuits (G12): <span id="power-circuits"></span></p>
        </div>
    </div>

    <style>
        #calculator {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }

        #calculator h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .calculator-inputs {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .input-group label {
            font-size: 16px;
            color: #555;
        }

        .input-group input {
            width: 60%;
            padding: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        hr {
            margin: 20px 0;
            border: 0;
            border-top: 1px solid #ddd;
        }

        .calculator-results {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .result-row strong {
            color: #333;
        }

        .result-row span {
            color: #007bff;
        }

    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_calculator', 'custom_calculator_shortcode');
