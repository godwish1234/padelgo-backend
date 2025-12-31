# Partner & Location API Documentation

## Overview

RESTful APIs for managing partners, locations, courts, and schedules.

## Authentication

Public endpoints - No authentication required.

---

## Endpoints

### 1. GET /api/v1/partners

Get all active partners with their locations.

**Query Parameters:**

- `per_page` (optional, default: 10) - Items per page

**Example Response:**

```json
{
  "success": true,
  "message": "Partners retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "PadelPro Indonesia",
      "description": "Premium padel court facilities",
      "logo": "https://example.com/logo.png",
      "is_active": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z",
      "locations": [
        {
          "id": 1,
          "partner_id": 1,
          "name": "PadelPro Jakarta Selatan",
          "city": "Jakarta Selatan",
          "address": "Jl. Sudirman No. 123",
          "latitude": -6.2088,
          "longitude": 106.8456
        }
      ]
    }
  ],
  "pagination": {
    "total": 25,
    "per_page": 10,
    "current_page": 1,
    "last_page": 3,
    "from": 1,
    "to": 10
  }
}
```

---

### 2. GET /api/v1/partner-locations

Get all active partner locations.

**Query Parameters:**

- `per_page` (optional, default: 10) - Items per page

**Example Response:**

```json
{
  "success": true,
  "message": "Partner locations retrieved successfully",
  "data": [
    {
      "id": 1,
      "partner_id": 1,
      "name": "PadelPro Jakarta Selatan",
      "address": "Jl. Sudirman No. 123",
      "city": "Jakarta Selatan",
      "province": "DKI Jakarta",
      "postal_code": "12190",
      "latitude": -6.2088,
      "longitude": 106.8456,
      "phone": "021-12345678",
      "email": "jakarta@padelpro.id",
      "is_active": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z",
      "partner": {
        "id": 1,
        "name": "PadelPro Indonesia",
        "logo": "https://example.com/logo.png"
      },
      "courts": [
        {
          "id": 1,
          "name": "Court 1 - Indoor",
          "description": "Professional indoor court",
          "facilities": ["AC", "Lighting", "Locker Room"]
        }
      ]
    }
  ],
  "pagination": {
    "total": 50,
    "per_page": 10,
    "current_page": 1,
    "last_page": 5,
    "from": 1,
    "to": 10
  }
}
```

---

### 3. GET /api/v1/partner-locations/{id}

Get detailed information for a specific partner location.

**Example Response:**

```json
{
  "success": true,
  "message": "Partner location retrieved successfully",
  "data": {
    "id": 1,
    "partner_id": 1,
    "name": "PadelPro Jakarta Selatan",
    "address": "Jl. Sudirman No. 123",
    "city": "Jakarta Selatan",
    "province": "DKI Jakarta",
    "postal_code": "12190",
    "latitude": -6.2088,
    "longitude": 106.8456,
    "phone": "021-12345678",
    "email": "jakarta@padelpro.id",
    "is_active": true,
    "partner": {
      "id": 1,
      "name": "PadelPro Indonesia",
      "description": "Premium padel court facilities",
      "logo": "https://example.com/logo.png"
    },
    "courts": [
      {
        "id": 1,
        "partner_location_id": 1,
        "name": "Court 1 - Indoor",
        "description": "Professional indoor court with AC",
        "facilities": ["AC", "Lighting", "Locker Room", "Shower"],
        "is_active": true,
        "schedules": [
          {
            "id": 1,
            "court_id": 1,
            "date": "2025-12-31",
            "start_time": "08:00:00",
            "end_time": "10:00:00",
            "price": "200000.00",
            "is_available": true
          }
        ]
      }
    ]
  }
}
```

---

### 4. GET /api/v1/partner-locations/search

Search partner locations by city name.

**Query Parameters:**

- `city` (required) - City name to search
- `per_page` (optional, default: 10) - Items per page

**Example Request:**

```
GET /api/v1/partner-locations/search?city=Jakarta&per_page=15
```

**Example Response:**

```json
{
  "success": true,
  "message": "Partner locations retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "PadelPro Jakarta Selatan",
      "city": "Jakarta Selatan",
      "address": "Jl. Sudirman No. 123",
      "latitude": -6.2088,
      "longitude": 106.8456
    }
  ]
}
```

---

### 5. GET /api/v1/partner-locations/nearest

Find nearest partner locations based on coordinates.

**Query Parameters:**

- `lat` (required) - Latitude (-90 to 90)
- `lng` (required) - Longitude (-180 to 180)
- `radius_km` (optional, default: 10, max: 100) - Search radius in kilometers
- `per_page` (optional, default: 10) - Items per page

**Example Request:**

```
GET /api/v1/partner-locations/nearest?lat=-6.2088&lng=106.8456&radius_km=5
```

**Example Response:**

```json
{
  "success": true,
  "message": "Nearest partner locations retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "PadelPro Jakarta Selatan",
      "city": "Jakarta Selatan",
      "address": "Jl. Sudirman No. 123",
      "latitude": -6.2088,
      "longitude": 106.8456,
      "distance_km": 0.5,
      "partner": {
        "id": 1,
        "name": "PadelPro Indonesia"
      },
      "courts": [
        {
          "id": 1,
          "name": "Court 1 - Indoor"
        }
      ]
    },
    {
      "id": 2,
      "name": "PadelPro Jakarta Pusat",
      "city": "Jakarta Pusat",
      "distance_km": 3.2
    }
  ]
}
```

---

### 6. GET /api/v1/partner-locations/{id}/courts

Get all courts for a specific partner location.

**Example Response:**

```json
{
  "success": true,
  "message": "Courts retrieved successfully",
  "data": [
    {
      "id": 1,
      "partner_location_id": 1,
      "name": "Court 1 - Indoor",
      "address": "Jl. Sudirman No. 123",
      "city": "Jakarta Selatan",
      "phone": "021-12345678",
      "facilities": ["AC", "Lighting", "Locker Room"],
      "description": "Professional indoor court",
      "is_active": true,
      "schedules": [
        {
          "id": 1,
          "date": "2025-12-31",
          "start_time": "08:00:00",
          "end_time": "10:00:00",
          "price": "200000.00",
          "is_available": true
        }
      ]
    }
  ]
}
```

---

### 7. GET /api/v1/courts/{id}/schedules

Get upcoming schedules for a specific court.

**Query Parameters:**

- `per_page` (optional, default: 20) - Items per page

**Example Response:**

```json
{
  "success": true,
  "message": "Court schedules retrieved successfully",
  "data": [
    {
      "id": 1,
      "court_id": 1,
      "date": "2025-12-31",
      "start_time": "08:00:00",
      "end_time": "10:00:00",
      "price": "200000.00",
      "is_available": true,
      "is_active": true,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "court": {
        "id": 1,
        "name": "Court 1 - Indoor",
        "partner_location": {
          "id": 1,
          "name": "PadelPro Jakarta Selatan",
          "address": "Jl. Sudirman No. 123"
        }
      }
    }
  ],
  "pagination": {
    "total": 100,
    "per_page": 20,
    "current_page": 1,
    "last_page": 5,
    "from": 1,
    "to": 20
  }
}
```

---

## Error Responses

All endpoints follow the standard error format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {}
}
```

**Common Error Codes:**

- `400` - Bad Request (invalid parameters)
- `404` - Resource Not Found
- `422` - Validation Failed
- `500` - Internal Server Error
