### POSR Example
```json
{
    "request": {
        "payload_id": "STRING",
        "timestamp":  "STRING",
        "header": {
            "from": {
            "identity": "string",
        },
        "payload": {
            "buyer_cookie": "string",
            "browser_from_post": {
                "url": "string"
            },
            "return_url": {
                "url": "string"
            },
        }
    }
}
```

### POOM Example
```json
{
    "request": {
        "payload_id": "STRING",
        "timestamp":  "STRING",
        "header": {
            "from": {
            "identity": "string",
        },
        "payload": {
            "buyer_cookie": "string",
            "browser_from_post": {
                "url": "string"
            },
            "return_url": {
                "url": "string"
            },
            "shipping": {
                "ship_to": "",
            },
            "items": [
                {
                    "supplier_part_id": "HSBCUK026S",
                    "description": "HSBC UK Black T-Shirt - S",
                    "quantity": 4,
                    "unit_price": {
                        "currency": "GBP",
                        "value": 4.420
                    },
                    "classification": {
                        "domain": "UNSPSC",
                        "value": "8014161603",
                    }
                }
            ]
        }
    }
}
```

### Reponses
General Response
```json
{
    "status": {
        "code": 400,
        "text": "something something"
    },
}
```

POSR - Response
```json
{
    "status": {
        "code": 400,
        "text": "something something"
    },
    "start_page": {
        "url": "/"
    }
}
```
