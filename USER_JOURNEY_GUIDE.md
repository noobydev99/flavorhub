# 📱 FlavorHub Order System - User Journey Visual Guide

## 🛒 Customer Journey

```
┌─────────────────────────────────────────────────────────────────┐
│ STEP 1: Browse Menu                                             │
│                                                                 │
│ Visit: http://localhost/my%20project/frontend/menu.html        │
│                                                                 │
│ [FlavorHub Logo]              [Menu Page]                       │
│                                                                 │
│ 🔍 Search Items    🏷️ Filter by Category                        │
│                                                                 │
│ ┌──────────────┐  ┌──────────────┐  ┌──────────────┐           │
│ │ Pepperoni    │  │ Margherita   │  │ Caesar       │           │
│ │ Pizza        │  │ Pizza        │  │ Salad        │           │
│ │ $14.99       │  │ $12.50       │  │ $9.00        │           │
│ │              │  │              │  │              │           │
│ │ [Add to Cart]│  │ [Add to Cart]│  │ [Add to Cart]│           │
│ └──────────────┘  └──────────────┘  └──────────────┘           │
│                                                                 │
│ Cart Items: 3 | Cart Total: $36.49                             │
│                         [Go to Checkout] ➜                     │
└─────────────────────────────────────────────────────────────────┘

                            ⬇️

┌─────────────────────────────────────────────────────────────────┐
│ STEP 2: Checkout Form                                           │
│                                                                 │
│ Order Summary:                                                  │
│ ├─ Pepperoni Pizza x1 .......... $14.99                        │
│ ├─ Margherita Pizza x1 ......... $12.50                        │
│ └─ Caesar Salad x1 ............ $9.00                          │
│                                                                 │
│ Subtotal: $36.49                                               │
│ Tax (8%): $2.92                                                │
│ Delivery: $3.50                                                │
│ ─────────────────                                              │
│ TOTAL: $42.91                                                  │
│                                                                 │
│ Delivery Details:                                              │
│                                                                 │
│ Full Name:  [Ali Silva_______________]                         │
│ Phone:      [0777123456_____________]                          │
│ Address:    [456 Park Road, Kandy___]                          │
│                                                                 │
│ Payment Method:                                                │
│ ☑️  Card      ☐ Cash      ☐ UPI                                │
│                                                                 │
│ Special Instructions:                                          │
│ [Extra cheese please______________] (optional)                │
│                                                                 │
│ [Go Back] ◀️                       ▶️ [Place Order]             │
└─────────────────────────────────────────────────────────────────┘

                            ⬇️

┌─────────────────────────────────────────────────────────────────┐
│ STEP 3: Order Confirmation                                      │
│                                                                 │
│ ✅ ORDER PLACED SUCCESSFULLY!                                   │
│                                                                 │
│ Your Order ID: ORD-240876                                      │
│ Status: Order Received                                         │
│ Estimated Delivery: 25-35 mins                                 │
│                                                                 │
│ Redirecting to tracking page...                                │
│                                                                 │
│ [View Order Details] ➜                                          │
└─────────────────────────────────────────────────────────────────┘

                            ⬇️

┌─────────────────────────────────────────────────────────────────┐
│ STEP 4: Order Tracking                                          │
│                                                                 │
│ tracking.html?orderId=ORD-240876                               │
│                                                                 │
│ Order ID: ORD-240876                                           │
│                                                                 │
│ Status Timeline:                                               │
│                                                                 │
│ ✅ Order Received ────► ⏳ Preparing ────► 🚗 Out for       │
│    (13:12)              (pending)      Delivery (pending)     │
│                                           │                   │
│                                           ▼                   │
│                                        ✅ Delivered           │
│                                        (pending)              │
│                                                               │
│ Estimated Time: 25-35 minutes                                 │
│ Expected Delivery: 13:45 - 13:55                              │
│                                                               │
│ Order Details:                                                │
│ ├─ Pepperoni Pizza x1 ........ $14.99                        │
│ ├─ Margherita Pizza x1 ....... $12.50                        │
│ └─ Caesar Salad x1 ........... $9.00                          │
│                                                               │
│ Total: $42.91                                                 │
│                                                               │
│ [Continue Shopping] ➜                                         │
└─────────────────────────────────────────────────────────────────┘
```

---

## 👨‍💼 Admin Journey

```
┌──────────────────────────────────────────────────────────────────┐
│ ADMIN LOGIN                                                      │
│                                                                  │
│ Email: admin@flavorhub.com                                      │
│ Password: ••••••••••                                            │
│ [Login]                                                         │
│                                                                  │
│ ▶️ Leads to: admin/dashboard.php                                 │
└──────────────────────────────────────────────────────────────────┘

                              ⬇️

┌──────────────────────────────────────────────────────────────────┐
│ ADMIN DASHBOARD                                                  │
│                                                                  │
│ [Dashboard] [Orders] [Recipes] [Categories] [Users]            │
│                                                                  │
│ ▶️ Click "Orders" in sidebar                                    │
└──────────────────────────────────────────────────────────────────┘

                              ⬇️

┌──────────────────────────────────────────────────────────────────┐
│ ORDERS MANAGEMENT PAGE - admin/orders.php                       │
│                                                                  │
│ All Orders:                                                     │
│                                                                  │
│ ┌────────┬───────────┬────────────┬───────┬───────┬──────┐    │
│ │ Order  │ Customer  │ Phone      │ Items │ Total │Status│    │
│ │  ID    │   Name    │            │       │       │      │    │
├─┼────────┼───────────┼────────────┼───────┼───────┼──────┤    │
│ │ORD-240 │ Ali Silva │0777123456 │  3    │$42.91 │ 🔵    │    │
│ │876     │           │            │       │       │ Ord  │    │
│ │        │           │            │       │       │Recvd │    │
│ │        │[View] [Upd]│            │       │       │      │    │
├─┼────────┼───────────┼────────────┼───────┼───────┼──────┤    │
│ │ORD-424 │ John Doe  │0771234567 │  2    │$52.10 │ 🔵    │    │
│ │315     │           │            │       │       │Ord   │    │
│ │        │           │            │       │       │Recvd │    │
│ │        │[View] [Upd]│            │       │       │      │    │
├─┼────────┼───────────┼────────────┼───────┼───────┼──────┤    │
│ │ORD-889 │ John Doe  │0771234567 │  2    │$52.10 │ 🟡    │    │
│ │781     │           │            │       │       │Prep  │    │
│ │        │           │            │       │       │ing   │    │
│ │        │[View] [Upd]│            │       │       │      │    │
└─┴────────┴───────────┴────────────┴───────┴───────┴──────┘    │
│                                                                  │
│ Status Legends: 🔵 Order Received | 🟡 Preparing | 🔷 Out for  │
│                 🟢 Delivered | 🔴 Cancelled                    │
└──────────────────────────────────────────────────────────────────┘

                              ⬇️ [View] Click

┌──────────────────────────────────────────────────────────────────┐
│ ORDER DETAILS MODAL                                              │
│                                                                  │
│ Order: ORD-240876                                               │
│ ─────────────────────────────────────────────────────────────   │
│                                                                  │
│ Customer Information:                                           │
│ Name: Ali Silva                                                 │
│ Phone: 0777123456                                              │
│ Address: 456 Park Road, Kandy                                  │
│ Payment Method: Card                                            │
│ Special Instructions: Extra cheese please                       │
│                                                                  │
│ Order Items:                                                    │
│ ┌──────────────────┬─────┬────────┬────────┐                  │
│ │ Item             │ Qty │ Price  │ Total  │                  │
│ ├──────────────────┼─────┼────────┼────────┤                  │
│ │ Pepperoni Pizza  │  1  │ $14.99 │ $14.99 │                  │
│ │ Margherita Pizza │  1  │ $12.50 │ $12.50 │                  │
│ │ Caesar Salad     │  1  │  $9.00 │  $9.00 │                  │
│ └──────────────────┴─────┴────────┴────────┘                  │
│                                                                  │
│ Cost Breakdown:                                                 │
│ ┌─────────────────────────────┐                                │
│ │ Subtotal: .......... $36.49  │                                │
│ │ Tax (8%): .......... $2.92   │                                │
│ │ Delivery Fee: ...... $3.50   │                                │
│ ├─────────────────────────────┤                                │
│ │ TOTAL: ............ $42.91   │                                │
│ └─────────────────────────────┘                                │
│                                                                  │
│ [Close Modal]                                                   │
└──────────────────────────────────────────────────────────────────┘

                        OR ⬇️ [Update Status] Click

┌──────────────────────────────────────────────────────────────────┐
│ UPDATE ORDER STATUS MODAL                                        │
│                                                                  │
│ Order: ORD-240876                                               │
│ Customer: Ali Silva                                             │
│ Current Status: Order Received                                  │
│                                                                  │
│ Select New Status:                                              │
│                                                                  │
│ ◯ Order Received  (✓ Current)                                   │
│ ◯ Preparing                                                     │
│ ◯ Out for Delivery                                              │
│ ◯ Delivered                                                     │
│ ◯ Cancelled                                                     │
│                                                                  │
│ [Update Status] [Cancel]                                        │
└──────────────────────────────────────────────────────────────────┘

                            ⬇️

        ✅ Order status updated successfully!
        
       (Admin sees immediate update in table)
```

---

## 🔄 Data Flow Diagram

```
Customer                    Frontend                 Backend
   │                           │                         │
   │─── Add to Cart ───────────│                         │
   │                           │                         │
   │─── Fill Checkout ────────│ localStorage            │
   │    Form                  (cart data)              │
   │                           │                         │
   │─── Click Place ───────────│ api.js                 │
   │    Order                  │ (validates)            │
   │                           │                         │
   │                           │─── POST JSON ────────►  │place_order.php
   │                           │                         │
   │                           │                         │─── UserDAO
   │                           │                         │   (lookup user)
   │                           │                         │
   │                           │                         │─── OrderDAO
   │                           │                         │   (create order)
   │                           │                         │
   │                           │                         │─── OrderDAO
   │                           │                         │   (create items)
   │                           │                         │
   │                           │◄─── JSON Response ─────│ {success}
   │                           │
   │◄─── Show Confirmation ───│
   │    & Redirect
   │
   │
Admin                       Admin Panel              Database
   │                           │                         │
   │─── Login ─────────────────│─── AuthService ───────►│ users
   │                           │                        │
   │─── Navigate to ───────────│─── Load orders ───────►│ orders
   │    Orders Page            │                        │ order_items
   │                           │                        │
   │─── View Details ──────────│─── Show Modal ────────│
   │    (modal opens)          │   (pre-populated)     │
   │                           │                        │
   │─── Update Status ─────────│─── POST Status ───────►│ orders
   │                           │                        │ (update)
   │                           │◄─── Success Response ──│
   │◄─── Show Updated ─────────│                        │
   │    Status (badge)
```

---

## 📊 Order Status State Machine

```
                    ┌──────────────────┐
                    │ Order Received   │ (Blue Badge)
                    │ (Initial State)  │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │   Preparing      │ (Yellow Badge)
                    └────────┬─────────┘
                             │
                    ┌────────▼──────────┐
                    │  Out for Delivery │ (Light Blue Badge)
                    └────────┬─────────┘
                             │
                    ┌────────▼──────────┐
                    │   Delivered      │ (Green Badge)
                    │ (Final Success)  │
                    └──────────────────┘
                    
Any Status ─── Can Cancel ───► Cancelled (Red Badge)
```

---

## ✨ Key Features Summary

| Feature | Customer | Admin |
|---------|----------|-------|
| Browse Items | ✅ | - |
| Add to Cart | ✅ | - |
| Place Order | ✅ | - |
| View Confirmation | ✅ | - |
| Track Order | ✅ | - |
| View All Orders | - | ✅ |
| View Order Details | - | ✅ |
| Update Order Status | - | ✅ |
| See Cost Breakdown | ✅ | ✅ |
| Download Invoice | - | 🔲 (Future) |

---

**Last Updated:** July 10, 2026  
**Status:** ✅ Complete & Operational
