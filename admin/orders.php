<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\OrderDAO;
use FlavorHub\BusinessLogic\OrderService;

$error = '';
$success = '';

try {
    $db = Database::getConnection();
    $orderDAO = new OrderDAO($db);
    $orderService = new OrderService($orderDAO);
} catch (Exception $e) {
    $error = "Database Error: " . $e->getMessage();
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = trim($_POST['status']);
    
    try {
        if ($orderService->updateOrderStatus($orderId, $status)) {
            $success = "Order status updated successfully.";
        } else {
            $error = "Failed to update order status.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch all orders
$orders = [];
try {
    $orders = $orderService->getAllOrders();
} catch (Exception $e) {
    $error = "Failed to load orders: " . $e->getMessage();
}

$statusOptions = [
    'Order Received',
    'Preparing',
    'Out for Delivery',
    'Delivered',
    'Cancelled'
];
?>

    <!-- Alert notices -->
    <?php if ($error !== ''): ?>
      <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm py-3 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if ($success !== ''): ?>
      <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm py-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-12">
        <div class="fh-card">
          <!-- Card Header -->
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-bag-check-fill text-brand fs-5"></i>
              Order Management
            </h5>
          </div>

          <!-- Card Body -->
          <div class="card-body p-0">
            <?php if (empty($orders)): ?>
              <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">No orders found.</p>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="fh-table">
                  <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Customer Name</th>
                      <th>Phone</th>
                      <th>Items</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($orders as $order): ?>
                      <tr>
                        <td>
                          <span class="badge bg-light text-dark"><?= htmlspecialchars($order['order_id']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?></td>
                        <td>
                          <?php
                          $itemsCount = count($order['items'] ?? []);
                          echo $itemsCount . ' item' . ($itemsCount !== 1 ? 's' : '');
                          ?>
                        </td>
                        <td><strong>LKR <?= number_format($order['total'], 2) ?></strong></td>
                        <td>
                          <?php
                          $statusClass = '';
                          switch ($order['status']) {
                              case 'Delivered':
                                  $statusClass = 'bg-success';
                                  break;
                              case 'Out for Delivery':
                                  $statusClass = 'bg-info';
                                  break;
                              case 'Preparing':
                                  $statusClass = 'bg-warning text-dark';
                                  break;
                              case 'Order Received':
                                  $statusClass = 'bg-primary';
                                  break;
                              case 'Cancelled':
                                  $statusClass = 'bg-danger';
                                  break;
                              default:
                                  $statusClass = 'bg-secondary';
                          }
                          ?>
                          <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($order['status']) ?></span>
                        </td>
                        <td>
                          <small class="text-muted">
                            <?= date('M d, Y', strtotime($order['created_at'])) ?>
                          </small>
                        </td>
                        <td>
                          <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewOrderModal<?= $order['id'] ?>">
                              <i class="bi bi-eye"></i> View
                            </button>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editOrderModal<?= $order['id'] ?>">
                              <i class="bi bi-pencil-square"></i> Status
                            </button>
                          </div>
                        </td>
                      </tr>

                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <!-- Modals -->
              <?php foreach ($orders as $order): ?>
                <!-- View Order Details Modal -->
                <div class="modal fade" id="viewOrderModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Order Details - <?= htmlspecialchars($order['order_id']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <!-- Customer Information -->
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label class="form-label"><strong>Customer Name</strong></label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['customer_name']) ?>" disabled>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label"><strong>Phone</strong></label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['customer_phone'] ?? 'N/A') ?>" disabled>
                          </div>
                        </div>

                        <div class="mb-3">
                          <label class="form-label"><strong>Delivery Address</strong></label>
                          <textarea class="form-control" disabled><?= htmlspecialchars($order['customer_address'] ?? 'N/A') ?></textarea>
                        </div>

                        <!-- Order Items Section -->
                        <div class="mb-3">
                          <label class="form-label"><strong>Order Items</strong></label>
                          <div class="table-responsive">
                            <table class="table table-sm table-hover">
                              <thead class="table-light">
                                <tr>
                                  <th>Item Name</th>
                                  <th style="width: 80px;">Qty</th>
                                  <th style="width: 100px;">Unit Price</th>
                                  <th style="width: 100px;">Total</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                $itemsTotal = 0;
                                foreach ($order['items'] as $item): 
                                  $itemTotal = $item['unit_price'] * $item['quantity'];
                                  $itemsTotal += $itemTotal;
                                ?>
                                  <tr>
                                    <td><?= htmlspecialchars($item['name']) ?></td>
                                    <td><?= (int)$item['quantity'] ?></td>
                                    <td>LKR <?= number_format($item['unit_price'], 2) ?></td>
                                    <td>LKR <?= number_format($itemTotal, 2) ?></td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="row mb-3">
                          <div class="col-md-6">
                            <label class="form-label"><strong>Payment Method</strong></label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['payment_method'] ?? 'N/A') ?>" disabled>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label"><strong>Special Instructions</strong></label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['special_instructions'] ?? 'None') ?>" disabled>
                          </div>
                        </div>

                        <!-- Cost Breakdown -->
                        <div class="card bg-light">
                          <div class="card-body p-3">
                            <div class="row mb-2">
                              <div class="col-6"><strong>Subtotal:</strong></div>
                              <div class="col-6 text-end">LKR <?= number_format($order['subtotal'], 2) ?></div>
                            </div>
                            <hr>
                            <div class="row">
                              <div class="col-6"><h5 class="mb-0"><strong>Total:</strong></h5></div>
                              <div class="col-6 text-end"><h5 class="mb-0 text-brand"><strong>LKR <?= number_format($order['total'], 2) ?></strong></h5></div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Edit Status Modal -->
                <div class="modal fade" id="editOrderModal<?= $order['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form method="POST">
                        <div class="modal-body">
                          <div class="mb-3">
                            <label class="form-label">Order ID</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['order_id']) ?>" disabled>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Customer</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['customer_name']) ?>" disabled>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">Current Status</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($order['status']) ?>" disabled>
                          </div>
                          <div class="mb-3">
                            <label class="form-label">New Status</label>
                            <select name="status" class="form-select" required>
                              <option value="">-- Select Status --</option>
                              <?php foreach ($statusOptions as $status): ?>
                                <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                          <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                          <button type="submit" class="btn btn-brand">Update Status</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
