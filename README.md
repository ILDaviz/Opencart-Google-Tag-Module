# Extension Opencart 3.X Google Tag Analytics
---
[![Minimum Opencart Version](https://img.shields.io/badge/Opencart-%3E%3D%203.X-green)](https://www.opencart.com/index.php?route=common/home)
[![Minimum Opencart Version](https://img.shields.io/badge/Donate-Buy%20me%20a%20coffee%2C%20Thanks!!-orange)](https://www.buymeacoffee.com/davidev)
---
Use Google Tag Manager to manage tags (such as measurement and marketing optimization JavaScript tags) on your site. Without editing your site code, you use GTM user interface to add and update Google Ads, Google Analytics, Floodlight, and non-Google tags. This reduces errors and allows you to to deploy tags on your site quickly.

### Installation
```text
- This is a plug and play module;
- Install file .ocmod.zip on Extensions/Installer; 
- Reload on Extensions/Modifications;
- PHP >= 5.6.
```

### What does this module do?
```text
- Track users using google tag
- Track the pages viewed
- Track the categories displayed
- Track the displayed products
- Track the cart
- Track checkouts
- Track completed checkouts
```


### Google TAG dataLayer data output
Standard route dataLayer
```javascript
<script>
    dataLayer = [{    
        'page_type': '',    
        'page_title': '',    
        'page_url': '',
    }];
</script>
```

Standard route category
```javascript
<script>
    dataLayer = [{    
        'page_type': '',    
        'page_title': '',    
        'page_url': ''
    }];
</script>
```

Standard route product
```javascript
<script>
    dataLayer = [{    
        'page_type': '',    
        'page_title': '',    
        'page_url': '',    
        'event': '',    
        'ecommerce': {        
            'items': [{            
                'item_id': 0,            
                'item_name': '',
                'item_price': 0       
            }]   
        }    
        'product_id': 0,    
        'product_name': '',    
        'product_model': '',    
        'product_price': 0,    
        'product_viewed': 0,    
        'product_stock_status': ''
    }];
</script>
```

Standard route checkout/checkout
```javascript
<script>
    dataLayer = [{    
        'page_type': '',    
        'page_title': '',    
        'page_url': '',    
        'event': '',    
        'ecommerce': {        
            'items': [{
                    "item_id": 0,
                    "item_name": "",
                    "quantity": 0,
                    "index": 0,
                    "price": 0
                }]    
            }
    }];
</script>
```

Standard route checkout/success
```javascript
<script>
    dataLayer = [{    
        'page_type': '',    
        'page_title': '',    
        'page_url': '',    
        'event': '',   
        'ecommerce': {        
            'transaction_id': 0,        
            'affiliation': '',       
            'value': 0,        
            'shipping': 0,      
            'currency': '',      
            'items': [{
                "item_id": 0,
                "item_name": "",
                "quantity": 0,
                "index": 0,
                "price": 0:
            }]    
            }    
        'row_data': {
            "order_id": "",
            "account": "",
            "language": "",
            "currency": "",
            "customer_id": 0,
            "products_cart": [],
            "total_cart": 0,
            "payment_address": {},
            "shipping_address": {},
            "payment_method": {},
            "shipping_method": {},
            "comment": ""
        }
    }];
</script>
```