# FlavorHub Order System - Architecture & Flow

## 📊 Complete System Architecture

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         FLAVORHUB ORDER SYSTEM                          │
└─────────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────────┐
│ FRONTEND LAYER (Customer Interface)                                      │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  menu.html                  checkout.html              tracking.html    │
│     │                            │                          ▲           │
│     │ (add to cart)              │ (fill details)          │            │
│     ├────────────────────────────┤ (review order)          │            │
│     │                            │                         │            │
│     └──────────────────────────► checkout.js ─────────────┘            │
│                                  ▼                                      │
│                            api.js Module                                │
│                     (API Calls & LocalStorage)                         │
│                                  │                                      │
│                                  │ USE_MOCK_API = false                │
│                                  │ (Database Enabled)                  │
│                                  ▼                                      │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                         POST /api/place_order.php
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│ API LAYER (PHP Endpoints)                                                │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  place_order.php                                                        │
│  ├─ Parse JSON payload                                                 │
│  ├─ Lookup user by email (UserDAO)                                    │
│  ├─ Create order record (OrderDAO.createOrder)                       │
│  ├─ Create order items (OrderDAO.createOrderItems)                  │
│  └─ Return success response                                           │
│                                                                          │
│  orders.php (for dashboard)                                            │
│  └─ Get user's orders by user_id (OrderDAO)                         │
│                                                                          │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                         PDO Database Calls
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│ DATA ACCESS LAYER (DAO Classes)                                          │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  OrderDAO                          UserDAO                             │
│  ├─ createOrder()              ├─ findByEmail()                       │
│  ├─ createOrderItems()         └─ getById()                           │
│  ├─ getAll()                                                           │
│  ├─ getOrderById()                                                     │
│  ├─ getOrderByOrderId()                                               │
│  ├─ getOrdersByUserId()                                               │
│  ├─ updateStatus()                                                     │
│  └─ getOrderItems()                                                    │
│                                                                          │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                         MySQL Queries
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│ DATABASE LAYER (MySQL Tables)                                            │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  users Table                  orders Table          order_items Table  │
│  ├─ id                        ├─ id                 ├─ id              │
│  ├─ fullname                  ├─ order_id           ├─ order_id        │
│  ├─ email                     ├─ user_id (FK)       ├─ food_id         │
│  ├─ phone                     ├─ customer_name      ├─ name            │
│  └─ address                   ├─ customer_phone     ├─ unit_price      │
│                               ├─ customer_address   ├─ quantity        │
│                               ├─ payment_method     └─ total_price     │
│                               ├─ special_instructions                  │
│                               ├─ subtotal                              │
│                               ├─ tax                                   │
│                               ├─ delivery_fee                          │
│                               ├─ total                                 │
│                               ├─ status                                │
│                               └─ created_at                            │
│                                                                          │
└──────────────────────────────────┬───────────────────────────────────────┘
                                   │
                   ORDER DATA NOW STORED IN DATABASE
                                   │
┌──────────────────────────────────▼───────────────────────────────────────┐
│ ADMIN PANEL LAYER (Order Management)                                     │
├──────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  admin/orders.php                                                      │
│  ├─ OrderService.getAllOrders()                                       │
│  ├─ Display order table                                               │
│  │  ├─ Order ID                                                       │
│  │  ├─ Customer Name & Phone                                          │
│  │  ├─ Items Count                                                    │
│  │  ├─ Total Amount                                                   │
│  │  ├─ Status (Color Badge)                                           │
│  │  ├─ Created Date                                                   │
│  │  └─ Action Buttons (View | Update Status)                         │
│  │                                                                    │
│  │  ┌─────────────────────────────────────────┐                      │
│  │  │ View Order Details Modal                │                      │
│  │  ├─────────────────────────────────────────┤                      │
│  │  │ Order: ORD-240876                       │                      │
│  │  │ Customer: Ali Silva (0777123456)        │                      │
│  │  │ Address: 456 Park Road, Kandy           │                      │
│  │  │                                         │                      │
│  │  │ Items:                                  │                      │
│  │  │ Pepperoni Pizza x1 @ $14.99 = $14.99   │                      │
│  │  │ Garlic Bread x2 @ $5.99 = $11.98       │                      │
│  │  │                                         │                      │
│  │  │ Subtotal: $26.97                        │                      │
│  │  │ Tax (8%): $2.16                         │                      │
│  │  │ Delivery: $3.50                         │                      │
│  │  │ TOTAL:    $32.63                        │                      │
│  │  │                                         │                      │
│  │  │ Payment: Card                           │                      │
│  │  │ Notes: Extra cheese please              │                      │
│  │  └─────────────────────────────────────────┘                      │
│  │                                                                    │
│  │  ┌─────────────────────────────────────────┐                      │
│  │  │ Update Order Status Modal               │                      │
│  │  ├─────────────────────────────────────────┤                      │
│  │  │ Order: ORD-240876                       │                      │
│  │  │ Customer: Ali Silva                     │                      │
│  │  │ Current Status: Order Received          │                      │
│  │  │                                         │                      │
│  │  │ Select New Status:                      │                      │
│  │  │ ☐ Order Received  (Blue)               │                      │
│  │  │ ☐ Preparing       (Yellow)              │                      │
│  │  │ ☐ Out for Delivery (Light Blue)        │                      │
│  │  │ ☐ Delivered       (Green)               │                      │
│  │  │ ☐ Cancelled       (Red)                │                      │
│  │  │                                         │                      │
│  │  │ [UPDATE] [CANCEL]                       │                      │
│  │  └─────────────────────────────────────────┘                      │
│  │                                                                    │
│  └─ OrderService.updateOrderStatus()                                │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Order Placement Flow Sequence

```
┌─────────────┐
│   Customer  │
└──────┬──────┘
       │
       │ 1. Browse menu & add items
       ▼
┌──────────────────────────────┐
│  Frontend: menu.html         │
│  Shows recipes from database │
└──────┬───────────────────────┘
       │
       │ 2. Click "Go to Checkout"
       ▼
┌──────────────────────────────┐
│  Frontend: checkout.html     │
│  - Pre-fill customer fields  │
│  - Show cart items           │
│  - Payment method selection  │
│  - Special instructions      │
└──────┬───────────────────────┘
       │
       │ 3. Validate & submit form
       ▼
┌──────────────────────────────┐
│  JS: checkout.js             │
│  - Validate all fields       │
│  - Get current user          │
│  - Get cart items            │
│  - Calculate totals          │
└──────┬───────────────────────┘
       │
       │ 4. Call API module
       ▼
┌──────────────────────────────┐
│  JS: api.js                  │
│  - placeOrder()              │
│  - POST to place_order.php   │
└──────┬───────────────────────┘
       │
       │ 5. Send to server
       │ POST /api/place_order.php
       │ JSON: {orderId, items, details, totals}
       ▼
┌──────────────────────────────┐
│  PHP: place_order.php        │
│  - Parse payload             │
│  - Look up user              │
│  - Validate data             │
└──────┬───────────────────────┘
       │
       │ 6. Save to database
       ▼
┌──────────────────────────────┐
│  OrderDAO: createOrder()     │
│  - Insert into orders table  │
│  - Get last insert ID        │
└──────┬───────────────────────┘
       │
       │ 7. Save order items
       ▼
┌──────────────────────────────┐
│  OrderDAO: createOrderItems()│
│  - Insert items into table   │
└──────┬───────────────────────┘
       │
       │ 8. Return success
       ▼
┌──────────────────────────────┐
│  JSON Response:              │
│  {success: true, orderId}    │
└──────┬───────────────────────┘
       │
       │ 9. Show confirmation
       ▼
┌──────────────────────────────┐
│  Frontend: tracking.html     │
│  - Show order ID             │
│  - Redirect with orderId     │
└──────┬───────────────────────┘
       │
       │ 10. Order now in database
       ▼
┌──────────────────────────────┐
│  Admin: orders.php           │
│  - See new order in list     │
│  - Click to view details     │
│  - Update status as needed   │
└──────────────────────────────┘
```

---

## 📦 Data Structure

### Order Object (In Transit)
```json
{
  "orderId": "ORD-240876",
  "customerEmail": "customer@flavorhub.com",
  "date": "2026-07-10T13:12:34.000Z",
  "items": [
    {
      "id": 1,
      "name": "Pepperoni Pizza",
      "price": 14.99,
      "quantity": 1
    },
    {
      "id": 2,
      "name": "Garlic Bread",
      "price": 5.99,
      "quantity": 2
    }
  ],
  "subtotal": 26.97,
  "tax": 2.16,
  "deliveryFee": 3.50,
  "total": 32.63,
  "deliveryDetails": {
    "fullName": "Ali Silva",
    "phone": "0777123456",
    "address": "456 Park Road, Kandy",
    "paymentMethod": "Card",
    "instructions": "Extra cheese please"
  },
  "status": "Order Received",
  "estimatedTime": "25-35 mins"
}
```

### Order in Database
```sql
-- orders table
id: 3
order_id: "ORD-240876"
user_id: 2
customer_name: "Ali Silva"
customer_phone: "0777123456"
customer_address: "456 Park Road, Kandy"
payment_method: "Card"
special_instructions: "Extra cheese please"
subtotal: 26.97
tax: 2.16
delivery_fee: 3.50
total: 32.63
status: "Order Received"
estimated_time: "25-35 mins"
created_at: "2026-07-10 13:12:34"

-- order_items table
id: 5, order_id: 3, food_id: 1, name: "Pepperoni Pizza", unit_price: 14.99, quantity: 1, total_price: 14.99
id: 6, order_id: 3, food_id: 2, name: "Garlic Bread", unit_price: 5.99, quantity: 2, total_price: 11.98
```

---

## 🎯 Status Color Mapping

```
Status Value          →    Color        →    Badge Style
"Order Received"      →    Blue (#0d6efd) →   bg-primary
"Preparing"           →    Yellow (#ffc107) → bg-warning
"Out for Delivery"    →    Light Blue (#0dcaf0) → bg-info
"Delivered"           →    Green (#198754) →  bg-success
"Cancelled"           →    Red (#dc3545) →    bg-danger
```

---

## ✅ Integration Checklist

- ✅ Frontend checkout form complete
- ✅ API.js module configured (USE_MOCK_API = false)
- ✅ place_order.php endpoint working
- ✅ OrderDAO saving to database
- ✅ Order items stored correctly
- ✅ Admin panel displaying orders
- ✅ View modal showing details
- ✅ Status update working
- ✅ Database schema correct
- ✅ User lookup by email working
- ✅ Cost calculations accurate (8% tax, $3.50 delivery)
- ✅ Order ID generation working
- ✅ Error handling in place

---

**System Status: 🟢 FULLY OPERATIONAL**
