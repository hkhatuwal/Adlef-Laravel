# Payment Link Generator - User Guide

## Overview
The Payment Link Generator allows you to manually create payment links for your customers directly from the Payment Gateway dashboard without writing any code.

## How to Use

### Step 1: Navigate to Payment Gateway
1. Log in to your client dashboard
2. Go to **Payment Gateway** section
3. Click on the **"Generate Payment Link"** tab

### Step 2: Select API Client
1. Choose an API client from the dropdown
   - Only active API clients will be selectable
   - Inactive clients are shown but disabled
   - Environment (Sandbox/Production) is displayed

### Step 3: Fill in Payment Details

#### Required Fields (marked with *)
- **Amount**: The payment amount (e.g., 100.00)
- **Currency**: Select from supported currencies (USD, EUR, GBP, etc.)
- **Customer Name**: Full name of the customer
- **Customer Email**: Valid email address
- **Customer Phone**: Contact number

#### Optional Fields
- **Description**: Brief description of the payment
- **Your Order ID**: Your internal reference/order number
- **Return URL**: Where to redirect on successful payment
- **Cancel URL**: Where to redirect on failed/cancelled payment

### Step 4: Generate Link
1. Click the **"Generate Payment Link"** button
2. Wait for the system to process (button shows loading state)
3. Success message will appear with transaction details

### Step 5: Share the Link
Once generated, you'll see:
- **Transaction ID**: Unique identifier for tracking
- **Checkout URL**: Link to share with your customer
- **Copy buttons**: Quick copy to clipboard
- **Open button**: Test the link in a new tab

### Step 6: Customer Payment
1. Share the checkout URL with your customer
2. Customer completes payment on the checkout page
3. Transaction appears in your **Transactions** tab
4. Customer is redirected to your Return/Cancel URL

## Important Notes

### Link Expiration
- Payment links expire after **24 hours**
- After expiration, customers cannot complete the payment
- Generate a new link if needed

### Currency Restrictions
- Only currencies allowed for your API client can be selected
- Check your API client settings if a currency is not available

### Transaction Limits
- Daily limit: Check your API client configuration
- Monthly limit: Check your API client configuration
- System will reject if limits are exceeded

### IP Restrictions
- If you've configured IP restrictions on your API client
- Only requests from allowed IPs can generate links
- Configure in API Keys settings

## Troubleshooting

### "Please select an API client"
- Make sure you've selected an API client from the dropdown

### "Currency not allowed for your account"
- The selected currency is not in your API client's allowed currencies
- Update your API client settings in the **API Keys** tab

### "Payment amount exceeds your limits"
- You've reached your daily or monthly transaction limit
- Contact support to increase limits or wait for the next period

### "Invalid API credentials"
- Your API client may be inactive
- Check API client status in the **API Keys** tab

### "API credentials have expired"
- Your API client has an expiry date that has passed
- Contact support to renew

### "IP address not allowed"
- Your current IP is not in the allowed list
- Update IP restrictions in API client settings

## Best Practices

### 1. Use Descriptive Order IDs
- Use your internal order/invoice numbers
- Makes tracking easier in your system
- Example: `INV-2024-001`, `ORDER-12345`

### 2. Set Return and Cancel URLs
- Always provide these for better user experience
- Helps customers get back to your site
- Allows you to show custom success/error messages

### 3. Include Clear Descriptions
- Help customers identify the payment
- Reduces confusion and support requests
- Example: "Invoice #12345 - Consulting Services"

### 4. Test in Sandbox First
- Create a sandbox API client
- Test the complete flow
- Then switch to production

### 5. Monitor Transaction Status
- Check the **Transactions** tab regularly
- Set up webhooks for real-time updates
- Keep customers informed of payment status

## Integration Tips

### For Developers
Even though this is a manual tool, you can:
1. Copy the checkout URL structure
2. Study the API request/response format
3. Implement automated generation in your system
4. Use the **API Documentation** tab for coding examples

### For Non-Technical Users
- No coding knowledge required
- Simple form-based interface
- Copy and paste links to share
- Track all payments in one place

## Security

### Safe Practices
✅ Only share checkout URLs with intended customers
✅ Use HTTPS for Return/Cancel URLs
✅ Keep API credentials secure
✅ Review transactions regularly
✅ Set reasonable transaction limits

### Avoid
❌ Sharing API keys publicly
❌ Using unsecured HTTP for callbacks
❌ Setting unlimited transaction amounts
❌ Ignoring failed transactions
❌ Reusing expired links

## Quick Reference

| Field | Type | Required | Max Length | Example |
|-------|------|----------|------------|---------|
| API Client | Dropdown | Yes | - | My Store API |
| Amount | Number | Yes | - | 100.00 |
| Currency | Dropdown | Yes | 3 chars | USD |
| Customer Name | Text | Yes | 255 chars | John Doe |
| Customer Email | Email | Yes | 255 chars | john@example.com |
| Customer Phone | Text | Yes | 20 chars | +1234567890 |
| Description | Text | No | 255 chars | Payment for services |
| Order ID | Text | No | 100 chars | ORDER-12345 |
| Return URL | URL | No | 500 chars | https://site.com/success |
| Cancel URL | URL | No | 500 chars | https://site.com/cancel |

## Support

Need help?
1. Check this guide first
2. Review API Documentation tab
3. Contact support team
4. Check transaction logs

## Next Steps

After generating payment links:
1. **Track Transactions**: Monitor in Transactions tab
2. **Set Up Webhooks**: Get real-time payment updates
3. **Configure Settlement**: Set up automatic payouts
4. **Review Analytics**: Check success rates and volumes
5. **API Integration**: Automate for higher volumes

