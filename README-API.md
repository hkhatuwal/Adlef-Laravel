# Transfer Verification API

This API allows external systems to verify asset transfers by reference ID. It's designed to be secure and easy to integrate with Node.js applications.

## API Endpoint

```
POST /api/transfers/verify
```

## Authentication

The API uses HMAC authentication to ensure that only authorized clients can verify transfers. Each request must include:

1. A timestamp (to prevent replay attacks)
2. A signature (to verify the authenticity of the request)

## Request Parameters

| Parameter    | Type   | Description                                                |
|--------------|--------|------------------------------------------------------------|
| reference_id | string | The reference ID of the transfer to verify                 |
| timestamp    | int    | Current Unix timestamp (seconds since epoch)               |
| signature    | string | HMAC-SHA256 signature of the request                       |

## Node.js Implementation Example

```javascript
const crypto = require('crypto');
const axios = require('axios');

// Configuration
const API_URL = 'https://your-laravel-app.com/api/transfers/verify';
const API_SECRET = 'your_secret_key_here'; // Same as TRANSFER_VERIFICATION_SECRET in .env

/**
 * Verify a transfer by reference ID
 * 
 * @param {string} referenceId - The reference ID of the transfer
 * @returns {Promise} - API response
 */
async function verifyTransfer(referenceId) {
  try {
    // Create timestamp (seconds since epoch)
    const timestamp = Math.floor(Date.now() / 1000);
    
    // Data to sign: reference_id + timestamp + secret
    const dataToSign = referenceId + timestamp + API_SECRET;
    
    // Generate signature
    const signature = crypto
      .createHmac('sha256', API_SECRET)
      .update(dataToSign)
      .digest('hex');
    
    // Make API request
    const response = await axios.post(API_URL, {
      reference_id: referenceId,
      timestamp: timestamp,
      signature: signature
    });
    
    return response.data;
  } catch (error) {
    console.error('Error verifying transfer:', error.response?.data || error.message);
    throw error;
  }
}

// Example usage
verifyTransfer('REF123456789')
  .then(result => {
    console.log('Transfer verified successfully:', result);
  })
  .catch(error => {
    console.error('Failed to verify transfer:', error);
  });
```

## Security Considerations

1. Keep your API secret secure and never expose it in client-side code
2. The timestamp is checked to ensure the request is not older than 5 minutes
3. Each request must have a valid signature
4. Only pending transfers of type "in" can be verified through this API
5. All verification attempts are logged for security auditing

## Response Format

### Success Response (200 OK)

```json
{
  "success": true,
  "message": "Transfer verified successfully",
  "data": {
    "reference_id": "REF123456789",
    "status": "verified",
    "verified_at": "2023-07-15 14:30:45"
  }
}
```

### Error Responses

#### Invalid Signature (401 Unauthorized)

```json
{
  "success": false,
  "message": "Invalid signature"
}
```

#### Request Expired (401 Unauthorized)

```json
{
  "success": false,
  "message": "Request expired"
}
```

#### Transfer Not Found (404 Not Found)

```json
{
  "success": false,
  "message": "Transfer not found or not in pending state"
}
```

#### Validation Error (422 Unprocessable Entity)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "reference_id": ["The reference id field is required."]
  }
}
```

#### Server Error (500 Internal Server Error)

```json
{
  "success": false,
  "message": "Failed to verify transfer",
  "error": "An error occurred"
}
``` 