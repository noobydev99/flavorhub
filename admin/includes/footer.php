  </div> <!-- Close main-container -->
</div> <!-- Close content -->
</div> <!-- Close app-wrapper -->

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom layout javascript -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarCollapse = document.getElementById("sidebarCollapse");

    if (sidebarCollapse && sidebar) {
      sidebarCollapse.addEventListener("click", function () {
        // Toggle on desktop
        sidebar.classList.toggle("collapsed");
        // Toggle on mobile
        sidebar.classList.toggle("active");
      });
    }

    // Auto-dismiss Bootstrap alerts after 5 seconds
    const alerts = document.querySelectorAll(".alert-dismissible");
    alerts.forEach(function (alert) {
      setTimeout(function () {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      }, 5000);
    });
  });
</script>

</body>
</html>
