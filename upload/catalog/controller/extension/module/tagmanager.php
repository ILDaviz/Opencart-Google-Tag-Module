<?php

class ControllerExtensionModuleTagmanager extends Controller
{
    /**
     * Add Code After Head
     * catalog/view/common/header/after
     * @param $route
     * @param $args
     * @param $output
     */
    public function insertHeadCodeAfter(&$route, &$args, &$output)
    {
        if (!$this->config->get('module_tagmanager_status')) {
            return;
        }
        // Code printed on front-end
        $code_script = '';
        $page_title = $this->document->getTitle();
        // Route page
        if (isset($this->request->get['route'])) {
            $type_page = $this->request->get['route'];
        } else {
            $type_page = null;
        }
        // Route of page
        $page_route = '';
        // Url of page
        $page_url = '';
        // Extrapolation request route
        if (isset($this->request->get['route'])) {
            if (isset($this->request->get['_route_'])) {
                $page_route = $this->request->get['_route_'];
                $page_url = '/' . $page_route;
            } else {
                $page_route = $this->request->server['REQUEST_URI'];
                $page_url = $page_route;
            }
        } else {
            $page_url = '/' . $page_route;
        }

        $this->load->model('catalog/product');
        $this->load->model('catalog/category');

        if ($type_page == "product/product") {
            if (isset($this->request->get['product_id'])) {
                $product_id = $this->request->get['product_id'];
                $product_info = $this->model_catalog_product->getProduct($product_id);
                if ($product_info) {
                    // Start script
                    $code_script .= "<script>";
                    $code_script .= "dataLayer = [{";
                    $code_script .= "    'page_type': '" . $type_page . "',";
                    $code_script .= "    'page_title': '" . $page_title . "',";
                    $code_script .= "    'page_url': '" . $page_url . "',";
                    $code_script .= "    'event': 'view_item',";
                    $code_script .= "    'ecommerce': {";
                    $code_script .= "        'items': [{";
                    $code_script .= "            'item_id': " . $product_info['product_id'] . ",";
                    $code_script .= "            'item_name': '" . $product_info['name'] . "',";
                    $code_script .= "            'item_price': " . $product_info['price'] . "";
                    $code_script .= "        }]";
                    $code_script .= "    }";
                    $code_script .= "}];";
                    $code_script .= "</script>";
                    // End script
                }
            }
        } elseif ($type_page == "product/category") {
            // Estract category_id
            if (isset($this->request->get['path'])) {
                $path = '';
                $parts = explode('_', (string)$this->request->get['path']);
                $category_id = (int)array_pop($parts);
            } else {
                $category_id = 0;
            }
            $category_info = $this->model_catalog_category->getCategory($category_id);
            if ($category_info) {
                // Start script
                $code_script .= "<script>";
                $code_script .= "dataLayer = [{";
                $code_script .= "    'page_type': '" . $type_page . "',";
                $code_script .= "    'page_title': '" . $page_title . "',";
                $code_script .= "    'page_url': '" . $page_url . "',";
                if (isset($product_info['name']) && !empty($product_info['name'])) {
                    $code_script .= "    'category_name': '" . $product_info['name'] . "',";
                }
                $code_script .= "}];";
                $code_script .= "</script>";
                // End script
            }
        } elseif ($type_page == "checkout/checkout") {
            $products_cart = $this->cart->getProducts();
            $list_cart_product = array();
            $i = 0;
            foreach ($products_cart as $product_cart) {
                $list_cart_product[] = array(
                    'item_id' => $product_cart['product_id'],
                    'item_name' => $product_cart['name'],
                    'quantity' => $product_cart['quantity'],
                    'index' => $i,
                    'price' => $product_cart['total']
                );
                $i++;
            }
            // Start script
            $code_script .= "<script>";
            $code_script .= "dataLayer = [{";
            $code_script .= "    'page_type': '" . $type_page . "',";
            $code_script .= "    'page_title': '" . $page_title . "',";
            $code_script .= "    'page_url': '" . $page_url . "',";
            $code_script .= "    'event': 'begin_checkout',";
            $code_script .= "    'ecommerce': {";
            $code_script .= "        'items': " . json_encode($list_cart_product);
            $code_script .= "    }";
            $code_script .= "}];";
            $code_script .= "</script>";
            // End script
        } elseif ($type_page == "checkout/success") {
            $products_cart = $this->session->data['google_tag']['products_cart'];
            $list_cart_product = array();
            $i = 0;
            foreach ($products_cart as $product_cart) {
                $list_cart_product[] = array(
                    'item_id' => $product_cart['product_id'],
                    'item_name' => $product_cart['name'],
                    'quantity' => $product_cart['quantity'],
                    'index' => $i,
                    'price' => $product_cart['total']
                );
                $i++;
            }
            // Start script
            $code_script .= "<script>";
            $code_script .= "dataLayer = [{";
            $code_script .= "    'page_type': '" . $type_page . "',";
            $code_script .= "    'page_title': '" . $page_title . "',";
            $code_script .= "    'page_url': '" . $page_url . "',";
            $code_script .= "    'event': 'purchase',";
            $code_script .= "    'ecommerce': {";
            $code_script .= "        'transaction_id': '" . $this->session->data['google_tag']['order_id'] . "',";
            $code_script .= "        'affiliation': 'Online Store',";
            $code_script .= "        'value': '" . $this->session->data['google_tag']['order_id'] . "',";
            $code_script .= "        'shipping': '" . $this->session->data['google_tag']['shipping_method']['cost'] . "',";
            $code_script .= "        'currency': '" . $this->session->data['google_tag']['currency'] . "',";
            $code_script .= "        'items': " . json_encode($list_cart_product);
            $code_script .= "    }";
            $code_script .= "    'row_data': " . json_encode($this->session->data['google_tag']) . ",";
            $code_script .= "}];";
            $code_script .= "</script>";
            // Reset cache data on session
            $this->session->data['google_tag'] = [];
            // End script
        } else {
            $code_script .= "<script>";
            $code_script .= "dataLayer = [{";
            $code_script .= "    'page_type': '" . $type_page . "',";
            $code_script .= "    'page_title': '" . $page_title . "',";
            $code_script .= "    'page_url': '" . $page_url . "',";
            $code_script .= "}];";
            $code_script .= "</script>";
        }
        $code_script .= $this->config->get('module_tagmanager_head_code');
        $head_code = html_entity_decode($code_script, ENT_QUOTES, 'UTF-8');
        $output = str_replace('</head>', $head_code . '</head>', $output);
    }

    /**
     * Add Code After Body
     * catalog/view/common/header/after
     * @param $route
     * @param $args
     * @param $output
     */
    public function insertBodyCodeAfter(&$route, &$args, &$output)
    {
        if (!$this->config->get('module_tagmanager_status')) {
            return;
        }
        $body_code = html_entity_decode($this->config->get('module_tagmanager_body_code'), ENT_QUOTES, 'UTF-8');
        $output = str_replace('<body>', '<body>' . $body_code, $output);
    }

    /**
     * Save on session cache data order pre deleting
     * catalog/controller/checkout/success/before
     * @param $route
     * @param $args
     */
    public function saveOrderConfirmPreConfirmPageBefore(&$route, &$args)
    {
        if (!$this->config->get('module_tagmanager_status')) {
            return;
        }
        // Reset google_tag
        $this->session->data['google_tag'] = [];
        // Add dato on google_tag session
        if (isset($this->session->data['order_id'])) {
            $this->session->data['google_tag']['order_id'] = $this->session->data['order_id'];
            $this->session->data['google_tag']['account'] = $this->session->data['account'];
            $this->session->data['google_tag']['language'] = $this->session->data['language'];
            $this->session->data['google_tag']['currency'] = $this->session->data['currency'];
            $this->session->data['google_tag']['customer_id'] = $this->session->data['account'];
            $this->session->data['google_tag']['products_cart'] = $this->cart->getProducts();
            $this->session->data['google_tag']['total_cart'] = $this->cart->getTotal();
            $this->session->data['google_tag']['payment_address'] = $this->session->data['payment_address'];
            $this->session->data['google_tag']['shipping_address'] = $this->session->data['shipping_address'];
            $this->session->data['google_tag']['payment_method'] = $this->session->data['payment_method'];
            $this->session->data['google_tag']['shipping_method'] = $this->session->data['shipping_method'];
            $this->session->data['google_tag']['comment'] = $this->session->data['comment'];
        }
    }
}
