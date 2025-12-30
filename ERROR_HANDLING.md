# Error Handling Best Practices

## Overview

This project implements a unified error handling system across all APIs to ensure consistent response formats.

## Error Response Format

All error responses follow this structure:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {} // Optional: detailed errors
}
```

## HTTP Status Codes

- **200** - OK: Successful request
- **201** - Created: Resource successfully created
- **400** - Bad Request: Invalid request data
- **401** - Unauthorized: Authentication required
- **403** - Forbidden: Insufficient permissions
- **404** - Not Found: Resource not found
- **405** - Method Not Allowed: HTTP method not supported
- **422** - Unprocessable Entity: Validation failed
- **500** - Internal Server Error: Server error

## Custom Exception Classes

### BadRequestException

Use for invalid business logic requests:

```php
throw new BadRequestException('Cannot join a full match');
```

### UnauthorizedException

Use for permission/authorization errors:

```php
throw new UnauthorizedException('You are not allowed to delete this resource');
```

### ResourceNotFoundException

Use when a resource is not found:

```php
throw new ResourceNotFoundException('Match not found');
```

## Usage in Controllers

### Without Try-Catch (Recommended)

Let the global exception handler catch all errors:

```php
public function show($id)
{
    $match = PadelMatch::findOrFail($id); // Throws ModelNotFoundException
    return $this->success($match);
}
```

### With Custom Exceptions

```php
public function join(Request $request, $matchId)
{
    $match = PadelMatch::findOrFail($matchId);

    if ($match->isFull()) {
        throw new BadRequestException('Match is already full');
    }

    // Business logic...
    return $this->success($match, 'Successfully joined match');
}
```

### With Validation

Validation errors are automatically handled:

```php
public function store(CreateMatchRequest $request)
{
    // If validation fails, global handler returns 422 with errors
    $match = Match::create($request->validated());
    return $this->success($match, 'Match created', 201);
}
```

## ApiResponse Trait

Use the ApiResponse trait methods for success responses:

### Success Response

```php
return $this->success($data, 'Operation successful', 200);
```

### Paginated Response

```php
$matches = Match::paginate(15);
return $this->paginated($matches, 'Matches retrieved');
```

## Debug Mode

In development (`APP_DEBUG=true`), detailed error information is included:

- Exception class
- File and line number
- Stack trace (first 5 entries)

In production (`APP_DEBUG=false`), only generic error messages are shown.

## Examples

### Validation Error (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required"],
    "password": ["The password must be at least 8 characters"]
  }
}
```

### Not Found Error (404)

```json
{
  "success": false,
  "message": "Resource not found",
  "errors": null
}
```

### Custom Business Logic Error (400)

```json
{
  "success": false,
  "message": "Match is already full",
  "errors": null
}
```

### Unauthorized Error (401)

```json
{
  "success": false,
  "message": "Unauthenticated",
  "errors": null
}
```

### Permission Error (403)

```json
{
  "success": false,
  "message": "You are not allowed to delete this resource",
  "errors": null
}
```
