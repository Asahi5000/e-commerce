<?php
require '../../authenticator.php';
require '../../../config/config.php';

// Handle delete request first (if user confirmed deletion)
if (isset($_POST['confirmDelete'])) {
    $id = intval($_POST['message_id']);

    try {
        $stmt = $conn->prepare("DELETE FROM messages WHERE message_id = ?");
        $stmt->execute([$id]);
        header("Location: messages.php?deleted=1");
        exit;
    } catch (PDOException $e) {
        echo "Error deleting message: " . $e->getMessage();
        exit;
    }
}

// Handle search query
$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $conn->prepare("SELECT * FROM messages 
                            WHERE name LIKE :search 
                               OR email LIKE :search 
                               OR subject LIKE :search 
                               OR message LIKE :search 
                            ORDER BY created_at DESC");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
}

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Messages</title>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/messages.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" href="../library/images/logo.png" />
</head>
<body>

  <!-- Sidebar -->
  <?php include '../resources/sidebars/messages-sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <header>
      <button class="hamburger" onclick="toggleSidebar()">☰</button>
      <div class="header-text">
        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Here are your client messages.</p>
      </div>

      <button class="theme-toggle" onclick="toggleTheme()">🌙</button>

      <?php
      $profileImage = "../../assets/uploads/user/" . ($_SESSION['profile_image'] ?? "default.png");
      $username = $_SESSION['username'] ?? "Guest";
      $email = $_SESSION['email'] ?? "";
      ?>
      <div class="user-info" onclick="toggleUserMenu()">
        <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="User">
        <span><?php echo htmlspecialchars($username); ?></span>
      </div>

      <div class="user-menu" id="userMenu">
        <div class="profile-info">
          <strong><?php echo htmlspecialchars($username); ?></strong>
          <small><?php echo htmlspecialchars($email); ?></small>
        </div>
        <hr>
        <a href="../pages/settings.php"><i class='bx bxs-user'></i> Profile</a>
        <a href="../../logout.php"><i class='bx bx-log-out'></i> Logout</a>
      </div>
    </header>

    <!-- Messages Section -->
    <main class="p-6">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-green-700">📬 Client Messages</h2>
        <form method="get" class="flex">
          <input 
            type="text" 
            name="search" 
            value="<?= htmlspecialchars($search) ?>" 
            placeholder="Search messages..." 
            class="border border-gray-300 rounded-l-md p-2 text-gray-700 focus:outline-none"
          >
          <button class="bg-green-700 text-white px-3 rounded-r-md hover:bg-green-800">Search</button>
        </form>
      </div>

<?php if (isset($_GET['deleted'])): ?>
  <div id="deleteAlert" class="alert alert-success">
    ✅ Message deleted successfully! <span id="countdown">(10s)</span>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const alertBox = document.getElementById("deleteAlert");
      const countdownSpan = document.getElementById("countdown");
      let timeLeft = 10;

      // Update countdown every second
      const timer = setInterval(() => {
        timeLeft--;
        countdownSpan.textContent = `(${timeLeft}s)`;

        if (timeLeft <= 0) {
          clearInterval(timer);
          alertBox.classList.add("fade-out");
          setTimeout(() => alertBox.remove(), 500); // remove after fade
        }
      }, 1000);
    });
  </script>
<?php endif; ?>

      <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full border border-gray-200">
          <thead class="bg-green-600 text-white">
            <tr>
              <th class="py-3 px-4 text-left">#</th>
              <th class="py-3 px-4 text-left">Name</th>
              <th class="py-3 px-4 text-left">Email</th>
              <th class="py-3 px-4 text-left">Subject</th>
              <th class="py-3 px-4 text-left">Date</th>
              <th class="py-3 px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($messages): ?>
              <?php foreach ($messages as $row): ?>
                <tr class="border-b hover:bg-gray-50 transition">
                  <td data-label="#" class="py-3 px-4"><?= htmlspecialchars($row['message_id']) ?></td>
                  <td data-label="Name:" class="py-3 px-4"><?= htmlspecialchars($row['name']) ?></td>
                  <td data-label="Email:" class="py-3 px-4"><?= htmlspecialchars($row['email']) ?></td>
                  <td data-label="Subject:" class="py-3 px-4"><?= htmlspecialchars($row['subject'] ?: '—') ?></td>
                  <td data-label="Date:" class="py-3 px-4 text-gray-600 message-date">
                    <?= htmlspecialchars($row['created_at']) ?>
                  </td>


                  <td class="py-3 px-4 text-center space-x-2 actions">
                    <button class="view-btn" 
                      onclick="openViewModal(
                        '<?= addslashes($row['name']) ?>', 
                        '<?= addslashes($row['email']) ?>', 
                        '<?= addslashes($row['subject']) ?>', 
                        `<?= addslashes($row['message']) ?>`,
                        '<?= $row['created_at'] ?>'
                      )">
                      <i class='bx bx-show'></i> View
                    </button>

                    <button class="delete-btn" onclick="openDeleteModal(<?= $row['message_id'] ?>)">
                      <i class='bx bx-trash'></i> Delete
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="text-center py-6 text-gray-500">No messages found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

  <!-- Modals -->
  <?php include '../resources/modal/view-message.php'; ?>
  <?php include '../resources/modal/delete-message.php'; ?>

<!-- ✅ Modal Script -->
<script>
function openViewModal(name, email, subject, message, created) {
  document.getElementById("viewName").innerText = name;
  document.getElementById("viewEmail").innerText = email;
  document.getElementById("viewSubject").innerText = subject || "(No subject)";
  document.getElementById("viewMessage").innerText = message;
  document.getElementById("viewCreated").innerText = created;
  document.getElementById("viewModal").style.display = "flex";
}

function closeViewModal() {
  document.getElementById("viewModal").style.display = "none";
}

function openDeleteModal(id) {
  document.getElementById("deleteMessageId").value = id;
  document.getElementById("deleteModal").style.display = "flex";
}

function closeDeleteModal() {
  document.getElementById("deleteModal").style.display = "none";
}

window.onclick = function(e) {
  if (e.target.classList.contains("modal")) {
    e.target.style.display = "none";
  }
}

// ✅ Auto hide alert after 10 seconds with countdown
document.addEventListener("DOMContentLoaded", () => {
  const alertBox = document.getElementById("deleteAlert");
  if (!alertBox) return;

  const countdown = document.getElementById("countdown");
  let timeLeft = 10;
  const timer = setInterval(() => {
    timeLeft--;
    countdown.textContent = `(${timeLeft}s)`;
    if (timeLeft <= 0) {
      clearInterval(timer);
      alertBox.classList.add("fade-out");
      setTimeout(() => alertBox.remove(), 500);
    }
  }, 1000);
});
</script>

  <script src="../script/theme-toggle.js"></script>
  <script src="../script/sidebar.js"></script>
  <script src="../script/toggleUserMenu.js"></script>
</body>
</html>
