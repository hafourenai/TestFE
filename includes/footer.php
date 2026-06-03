</div>

<nav class="mobile-bottom-nav">
<a href="index.php?page=dashboard" class="<?php echo isActive('dashboard') ? 'active' : ''; ?>">
<span class="material-symbols-outlined" style="font-size: 22px;">dashboard</span>
<span>Home</span>
</a>
<a href="index.php?page=attendance-list" class="<?php echo isActive('attendance-list') ? 'active' : ''; ?>">
<span class="material-symbols-outlined" style="font-size: 22px;">calendar_today</span>
<span>Data</span>
</a>
<a href="index.php?page=attendance-create" class="<?php echo isActive('attendance-create') ? 'active' : ''; ?>">
<span class="material-symbols-outlined" style="font-size: 22px;">person_add</span>
<span>Add</span>
</a>
<a href="logout.php">
<span class="material-symbols-outlined" style="font-size: 22px;">logout</span>
<span>Logout</span>
</a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
