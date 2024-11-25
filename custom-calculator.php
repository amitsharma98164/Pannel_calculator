<?php
/**
 * Plugin Name: Custom Calculator
 * Description: A custom calculator plugin to perform predefined calculations.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
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

    wp_localize_script('custom-calculator-script', 'calculatorAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('calculator_email_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'custom_calculator_enqueue_scripts');

// AJAX handler for sending email
function send_calculator_email() {
    check_ajax_referer('calculator_email_nonce', 'nonce');
    
    // Get and sanitize form data
    $calculator_data = isset($_POST['calculatorData']) ? json_decode(stripslashes($_POST['calculatorData']), true) : null;
    $first_name = isset($_POST['firstName']) ? sanitize_text_field($_POST['firstName']) : '';
    $last_name = isset($_POST['lastName']) ? sanitize_text_field($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    
    // Validate required fields
    if (!$calculator_data || !is_array($calculator_data) || empty($first_name) || empty($email)) {
        wp_send_json(array(
            'success' => true,
            'data' => array(
                'success' => false,
                'message' => 'Missing required information'
            )
        ));
        return;
    }
    
    // Email to admin
    $admin_to = 'amit@snakescript.com';
    $admin_subject = 'New Calculator Quote Request';
    
    $admin_message = "<html><body>";
    $admin_message .= "<h2>New quote request received:</h2>";
    $admin_message .= "<h3>Contact Information:</h3>";
    $admin_message .= "<p>Name: {$first_name} {$last_name}<br>";
    $admin_message .= "Email: {$email}<br>";
    $admin_message .= "Phone: {$phone}</p>";
    
    $admin_message .= "<h3>Selected Model:</h3>";
    $admin_message .= "<p>Model: " . esc_html($calculator_data['model']) . "</p>";
    
    $admin_message .= "<h3>Display Dimensions:</h3>";
    $admin_message .= "<table style='border-collapse: collapse; width: 100%;'>";
    $admin_message .= "<tr><th style='border:1px solid #ddd; padding:8px;'>Measurement</th><th style='border:1px solid #ddd; padding:8px;'>Width</th><th style='border:1px solid #ddd; padding:8px;'>Height</th></tr>";
    
    $admin_message .= "<tr><td style='border:1px solid #ddd; padding:8px;'>Feet</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['feet_width']}</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['feet_height']}</td></tr>";
    $admin_message .= "<tr><td style='border:1px solid #ddd; padding:8px;'>Inches</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['inches_width']}</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['inches_height']}</td></tr>";
    $admin_message .= "<tr><td style='border:1px solid #ddd; padding:8px;'>Meters</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['meters_width']}</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['meters_height']}</td></tr>";
    $admin_message .= "</table>";
    
    $admin_message .= "<h3>Technical Specifications:</h3>";
    $admin_message .= "<ul>";
    $admin_message .= "<li>Total Cabinets: {$calculator_data['total_cabinets']}</li>";
    $admin_message .= "<li>Resolution: {$calculator_data['resolution_width']} x {$calculator_data['resolution_height']}</li>";
    $admin_message .= "<li>Total Resolution: {$calculator_data['total_resolution']}</li>";
    $admin_message .= "</ul>";
    $admin_message .= "</body></html>";
    
    // Email to customer
    $customer_subject = 'Your LED Display Quote Details';
    $customer_message = "<html><body>";
    $customer_message .= "<h2>Thank you for your interest in our LED Display solutions.</h2>";
    $customer_message .= "<p>Dear {$first_name},</p>";
    
    $customer_message .= "<h3>Your Selected Configuration:</h3>";
    $customer_message .= "<p>Model: " . esc_html($calculator_data['model']) . "</p>";
    
    $customer_message .= "<h3>Display Dimensions:</h3>";
    $customer_message .= "<table style='border-collapse: collapse; width: 100%;'>";
    $customer_message .= "<tr><th style='border:1px solid #ddd; padding:8px;'>Measurement</th><th style='border:1px solid #ddd; padding:8px;'>Width</th><th style='border:1px solid #ddd; padding:8px;'>Height</th></tr>";
    
    $customer_message .= "<tr><td style='border:1px solid #ddd; padding:8px;'>Feet</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['feet_width']}</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['feet_height']}</td></tr>";
    $customer_message .= "<tr><td style='border:1px solid #ddd; padding:8px;'>Inches</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['inches_width']}</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['inches_height']}</td></tr>";
    $customer_message .= "<tr><td style='border:1px solid #ddd; padding:8px;'>Meters</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['meters_width']}</td><td style='border:1px solid #ddd; padding:8px;'>{$calculator_data['meters_height']}</td></tr>";
    $customer_message .= "</table>";
    
    $customer_message .= "<h3>Technical Specifications:</h3>";
    $customer_message .= "<ul>";
    $customer_message .= "<li>Total Cabinets: {$calculator_data['total_cabinets']}</li>";
    $customer_message .= "<li>Resolution: {$calculator_data['resolution_width']} x {$calculator_data['resolution_height']}</li>";
    $customer_message .= "<li>Total Resolution: {$calculator_data['total_resolution']}</li>";
    $customer_message .= "</ul>";
    
    $customer_message .= "<p>We will contact you shortly to discuss your requirements in detail.</p>";
    $customer_message .= "<p>Best regards,<br>Your LED Display Team</p>";
    $customer_message .= "</body></html>";
    
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: LED Display Team <noreply@' . $_SERVER['HTTP_HOST'] . '>'
    );
    
    // Send emails
    $admin_sent = wp_mail($admin_to, $admin_subject, $admin_message, $headers);
    $customer_sent = wp_mail($email, $customer_subject, $customer_message, $headers);
    
    // Send response
    wp_send_json(array(
        'success' => true,
        'data' => array(
            'success' => ($admin_sent && $customer_sent),
            'message' => ($admin_sent && $customer_sent) 
                ? 'Quote sent successfully! Please check your email.' 
                : 'Failed to send quote. Please try again or contact support.'
        )
    ));
}
add_action('wp_ajax_send_calculator_email', 'send_calculator_email');
add_action('wp_ajax_nopriv_send_calculator_email', 'send_calculator_email');

// Shortcode to display the calculator
function custom_calculator_shortcode() {
    $lcd_models = get_field('lcd_model', 'option');
    ob_start();
    ?>
    <div id="calculator">
        <div class="model-selection">
            <h3>Choose Model/Cabinet Type</h3>
            <select id="model-select">
                <option value="">Select a model</option>
                <?php
                if($lcd_models) {
                    foreach($lcd_models as $model) {
                        $data_attributes = sprintf(
                            'data-panel-width-inches="%s" ' .
                            'data-panel-height-inches="%s" ' .
                            'data-resolution-width="%s" ' .
                            'data-resolution-height="%s" ' .
                            'data-panel-width-mm="%s" ' .
                            'data-panel-height-mm="%s"',
                            esc_attr($model['panel_size_wxhxd_inches_width']),
                            esc_attr($model['panel_size_wxhxd_inches_height']),
                            esc_attr($model['resolution_width']),
                            esc_attr($model['resolution_height']),
                            esc_attr($model['panel_size_wxhxdmm_width']),
                            esc_attr($model['panel_size_wxhxdmm_height'])
                        );
                        
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr($model['model_name']),
                            $data_attributes,
                            esc_html($model['model_name'])
                        );
                    }
                }
                ?>
            </select>
            <p class="sub-text">Indoor or Outdoor Series</p>
        </div>
        <hr>

        <div class="dimensions-section">
            <h3>Enter Dimensions</h3>
            <p class="sub-text">Please Enter Your Row and Column Quantity To See Your Wall Specifications</p>
            
            <div class="input-row">
                <div class="input-group">
                    <label>Rows</label>
                    <input type="number" id="height" value="3" step="1" min="0">
                </div>
                <div class="input-group">
                    <label>Columns</label>
                    <input type="number" id="width" value="8" step="1" min="0">
                </div>
            </div>
        </div>
        <hr>

        <div class="actual-dimensions">
            <h3>Actual Dimensions</h3>
            
            <div class="dimension-group">
                <div class="input-group">
                    <label>Width</label>
                    <input type="text" id="feet-width" readonly>
                    <span class="unit">in feet</span>
                </div>
                <div class="input-group">
                    <label>Height</label>
                    <input type="text" id="feet-height" readonly>
                    <span class="unit">in feet</span>
                </div>
            </div>
            <hr>

            <div class="dimension-group">
                <div class="input-group">
                    <label>Width</label>
                    <input type="text" id="inches-width" readonly>
                    <span class="unit">in inches</span>
                </div>
                <div class="input-group">
                    <label>Height</label>
                    <input type="text" id="inches-height" readonly>
                    <span class="unit">in inches</span>
                </div>
            </div>
            <hr>

            <div class="dimension-group">
                <div class="input-group">
                    <label>Width</label>
                    <input type="text" id="meters-width" readonly>
                    <span class="unit">in meters</span>
                </div>
                <div class="input-group">
                    <label>Height</label>
                    <input type="text" id="meters-height" readonly>
                    <span class="unit">in meters</span>
                </div>
            </div>
            <hr>
        </div>

        <div class="total-panels">
            <h3>Total Panels</h3>
            <input type="text" id="total-cabinets" readonly>
            <span class="unit">total panel count</span>
        </div>
        <hr>

        <div class="resolution-section">
            <h3>Resolution</h3>
            <div class="input-row">
                <div class="input-group">
                    <label>Width</label>
                    <input type="text" id="res-width" readonly>
                    <span class="unit">in pixels</span>
                </div>
                <div class="input-group">
                    <label>Height</label>
                    <input type="text" id="res-height" readonly>
                    <span class="unit">in pixels</span>
                </div>
            </div>
        </div>
        <hr>

        <div class="total-resolution">
            <h3>Total Resolution</h3>
            <input type="text" id="total-resolution" readonly>
            <span class="unit">in pixels</span>
        </div>
        <hr>

        <div class="contact-form-section">
            <div class="info-header">
                <h3>Don't see what you are looking for? We have many customizable solutions.</h3>
            </div>
            
            <form id="calculator-contact-form" class="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="first-name">First Name <span class="required">*</span></label>
                        <input type="text" id="first-name" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" id="last-name" name="lastName">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" id="email" name="email" placeholder="example@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="(000) 000-0000" pattern="\(\d{3}\) \d{3}-\d{4}">
                        <span class="helper-text">Please enter a valid phone number.</span>
                    </div>
                </div>

                <button type="submit" class="submit-button">Submit</button>
            </form>

            <div id="form-status" class="form-status"></div>
        </div>
    </div>

    

    <style>
        hr {
            background-size: 4px 4px;
            border: 1px;
            height: 1px;
            margin: 0 0 24px;
            border-top: 1px solid #e6e6e6;
        }
        #calculator {
            max-width: 750px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }

        .model-selection,
        .dimensions-section,
        .actual-dimensions,
        .total-panels,
        .resolution-section,
        .total-resolution {
            margin-bottom: 30px;
        }

        h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: bold;
            color: #2c3345;
        }

        .sub-text {
            color: #2c3345;
            font-size: 13px;
            margin: 5px 0 15px 0;
        }

        #model-select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .input-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .input-group {
            flex: 1;
        }

        .dimension-group {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: normal;
        }

        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[readonly] {
            background-color: #f5f5f5;
        }

        .unit {
            display: block;
            color: #666;
            font-size: 12px;
            margin-top: 4px;
        }

        .contact-form-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .info-header {
            margin-bottom: 20px;
        }

        .info-header h3 {
            color: #333;
            font-size: 18px;
            margin: 0;
        }

        .contact-form {
            max-width: 100%;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .required {
            color: #dc3545;
        }

        .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }

        .helper-text {
            display: block;
            font-size: 12px;
            color: #6c757d;
            margin-top: 4px;
        }

        .submit-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .submit-button:hover {
            background-color: #c82333;
        }

        .form-status {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            display: block; /* Always show the status div */
            min-height: 20px;
        }

        .form-status:empty {
            display: none; /* Hide only if empty */
        }

        .form-status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .form-status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_calculator', 'custom_calculator_shortcode');