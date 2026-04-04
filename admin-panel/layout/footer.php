<?php
$adminLoggedIn = isset($_SESSION['admin_username']);
?>

<?php if ($adminLoggedIn): ?>
            </div>
        </main>
    </div>
</div>
<script>
(function () {
  var root = document.querySelector(".admin-app");
  var btn = document.getElementById("admin-menu-toggle");
  if (!root || !btn) return;
  var sidebar = root.querySelector(".admin-sidebar");
  function close() {
    root.classList.remove("is-sidebar-open");
    btn.setAttribute("aria-expanded", "false");
  }
  btn.addEventListener("click", function (e) {
    e.stopPropagation();
    var open = root.classList.toggle("is-sidebar-open");
    btn.setAttribute("aria-expanded", open ? "true" : "false");
  });
  document.addEventListener("click", function (e) {
    if (!root.classList.contains("is-sidebar-open") || !sidebar) return;
    if (sidebar.contains(e.target) || btn.contains(e.target)) return;
    close();
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") close();
  });
})();
</script>
<?php else: ?>
    </div>
</div>
<?php endif; ?>

</body>
</html>
