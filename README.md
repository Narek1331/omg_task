# Currency Exchange Microservice

This microservice provides an API for retrieving currency exchange rates from the Central Bank of Russia.

## Base URL

```
http://your-domain.com/api/currency
```

## Endpoints

### 1. Get Latest Currency Rates

```
GET /latest
```

- **Description**: Retrieves the latest currency exchange rates from the Central Bank of Russia.

- **Parameters**: None

- **Response**:
  - **Status Code**: 200 OK
  - **Content Type**: application/json
  - **Data Format**:
    ```json
    {
        "ValCurs": {
            "Date": "01.03.2024",
            "Valute": [
                {
                    "ID": "R01010",
                    "NumCode": "036",
                    "CharCode": "AUD",
                    "Nominal": "1",
                    "Name": "Австралийский доллар",
                    "Value": "59,2201",
                    "VunitRate": "59,2201"
                },
                // Other currency objects
            ]
        }
    }
    ```

### 2. Get Historical Currency Rates

```
GET /history
```

- **Description**: Retrieves historical currency exchange rates from the Central Bank of Russia for a specified period.

- **Parameters**:
  - `start_date` (required): The start date of the period in the format YYYY-MM-DD.
  - `end_date` (required): The end date of the period in the format YYYY-MM-DD.

- **Response**:
  - **Status Code**: 200 OK
  - **Content Type**: application/json
  - **Data Format**: 
    ```json
    {
        "message": "Historical rates for the specified period"
    }
    ```

## Error Responses

- **Status Code**: 400 Bad Request
  - **Content Type**: application/json
  - **Response Format**:
    ```json
    {
        "error": "Both start date and end date are required"
    }
    ```

- **Status Code**: 500 Internal Server Error
  - **Content Type**: application/json
  - **Response Format**:
    ```json
    {
        "error": "Failed to fetch currency rates"
    }
    ```

