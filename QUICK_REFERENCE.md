# 🚀 Order System - Quick Reference

## What Users Can Do (Frontend)

### 1. Browse & Order
```
menu.html → Add items to cart → checkout.html → Fill details → Place order
```

### 2. Order Details Form
```
Full Name ________________
Phone    _________________
Address  _________________
Instructions (optional) ___
[ ] Card  [ ] Cash  [ ] UPI
```

### 3. Order Confirmation
```
✅ Order placed successfully!
Order ID: ORD-240876
Redirecting to tracking page...
```

---

## What Admin Can Do (Admin Panel)

### 1. View All Orders
Navigate to: `admin/orders.php`

Shows table with:
- Order ID
- Customer Name  
- Phone
- Items Count
- Total Amount
- Status (color badge)
- Date Created
- Action Buttons

### 2. View Order Details
Click "View" button → Modal opens showing:
```
Order ID: ORD-240876
Customer: Ali Silva (0777123456)
Address: 456 Park Road, Kandy

Items:
┌──────────────────┬───┬────────┬────────┐
│ Item             │ Qty│ Price  │ Total  │
├──────────────────┼───┼────────┼────────┤
│ Pepperoni Pizza  │ 1 │ $14.99 │ $14.99 │
│ Garlic Bread     │ 2 │ $5.99  │ $11.98 │
└──────────────────┴───┴────────┴────────┘

Subtotal: $26.97
Tax (8%): $2.16
Delivery: $3.50
TOTAL:    $32.63

Payment: Card
Notes: Extra cheese please
```

### 3. Update Order Status
Click "Update Status" button → Modal opens:
```
Order: ORD-240876
Customer: Ali Silva
Current: Order Received

New Status:
[ ] Order Received
[ ] Preparing
[ ] Out for Delivery
[ ] Delivered
[ ] Cancelled

[Update Status] [Cancel]
```

---

## Database View (SQL)

### All Orders
```sql
SELECT order_id, customer_name, customer_phone, total, status, created_at 
FROM orders 
ORDER BY created_at DESC;
```

### Order Items
```sql
SELECT name, quantity, unit_price, total_price 
FROM order_items 
WHERE order_id = 1;
```

---

## Order Status Color Codes

| Status | Badge Color | Icon |
|--------|------------|------|
| Order Received | 🔵 Blue | ✓ |
| Preparing | 🟡 Yellow | ⏳ |
| Out for Delivery | 🔷 Light Blue | 🚗 |
| Delivered | 🟢 Green | ✓✓ |
| Cancelled | 🔴 Red | ✗ |

---

## Key Endpoints

```
Frontend:
GET  /frontend/menu.html         - Browse items
GET  /frontend/checkout.html     - Checkout form

API:
POST /api/place_order.php        - Create order
GET  /api/orders.php             - Get user orders

Admin:
GET  /admin/orders.php           - View all orders
```

---

## Troubleshooting Quick Fixes

### Orders Not Saving?
1. Check `USE_MOCK_API = false` in api.js
2. Verify MySQL is running
3. Check customer email exists

### Items Not Showing?
1. Verify cart items have id, name, price, quantity
2. Check order_items table has entries
3. Verify OrderDAO.getOrderItems() called

### Admin Page Empty?
1. Run: `php verify_orders.php`
2. Check database connection
3. Verify user is logged in as admin

---

## File Locations

```
Root Directory: d:\xampp\htdocs\my project\

Frontend Files:
  frontend/checkout.html
  frontend/js/checkout.js
  frontend/js/api.js

API Files:
  api/place_order.php

Admin Files:
  admin/orders.php
  admin/includes/header.php

Backend Files:
  src/DataAccess/OrderDAO.php
  src/BusinessLogic/OrderService.php
  config/database.php

Test Files:
  test_api_order.php
  verify_orders.php
  test_order_flow.php
```

---

## Default Test User

**For Testing Orders:**
```
Email: customer@flavorhub.com
Password: password123
```

**Admin Access:**
```
Email: admin@flavorhub.com
Password: admin123
URL: http://localhost/my%20project/admin/dashboard.php
```

---

## One-Minute Setup Check

```bash
# 1. Start XAMPP (Apache & MySQL)

# 2. Test Connection
cd d:\xampp\htdocs\my project
php test_db_connection.php

# 3. Check Order DAO
php test_order_dao.php

# 4. Create Test Order
php test_api_order.php

# 5. View All Orders
php verify_orders.php

# 6. Visit Admin Panel
http://localhost/my%20project/admin/orders.php
```

All green? ✅ You're ready to go!

---

## Status: ✅ LIVE

The order system is fully operational and production-ready!
