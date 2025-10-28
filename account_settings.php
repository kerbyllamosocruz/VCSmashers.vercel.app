<?php
session_start();
$userName = $_SESSION["name"] ?? "Guest";
$userEmail = $_SESSION["email"] ?? "Unknown";
$userPhone = $_SESSION["phone"] ?? "";
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Account Settings</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Gotu&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="icon" type="image/x-icon" href="Assets/logo.png" />
  <script src="script.js"></script>
</head>
<body>
  <nav class="bg-primary shadow-lg">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20">
        <div class="flex items-center">
          <img src="Assets/logo.png" alt="Maysan Badminton Court Logo" class="h-10" />
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <a href="index.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Home</a>
          <a href="schedule.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Schedule</a>
          <a href="faqs.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">FAQs</a>
          <a href="contact.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
          <a href="profile_page.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline transition">Profile</a>
        </div>
        <div class="md:hidden flex items-center">
          <button class="text-white">
            <i data-feather="menu"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <header class="bg-gray-100 py-8">
    <h2 class="text-3xl font-bold text-center text-primary">Account Settings</h2>
  </header>

  <div class="container mx-auto p-4">
    <?php if ($status): ?>
      <div class="mb-4 p-3 rounded-md text-white <?php echo $status === 'success' ? 'bg-green-600' : 'bg-red-600'; ?>">
        <?php echo htmlspecialchars($message ?: ($status === 'success' ? 'Changes saved.' : 'There was a problem.')); ?>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Sidebar: Profile card + Quick Links -->
      <section class="lg:col-span-1 flex flex-col gap-y-8">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
          <div class="w-24 h-24 mx-auto mb-3 rounded-full bg-gray-200 flex items-center justify-center text-primary font-bold text-2xl overflow-hidden">
            <?php if (!empty($_SESSION['profile_pic'])): ?>
              <img src="<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile" class="w-full h-full object-cover">
            <?php else: ?>
              <?php echo strtoupper(substr($userName, 0, 1)); ?>
            <?php endif; ?>
          </div>
          <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($userName); ?></h3>
          <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($userEmail); ?></p>
          <a href="#profile" class="bg-primary text-white inline-block px-6 py-3 rounded-lg font-bold hover:bg-opacity-80 transition w-full text-center">Account Settings</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg">
          <h3 class="text-xl font-bold mb-4 text-left">Quick Links</h3>
          <ul class="space-y-2 text-left">
            <li><a href="#profile" class="text-gray-700 hover:text-primary">Profile</a></li>
            <li><a href="#security" class="text-gray-700 hover:text-primary">Security</a></li>
            <li><a href="#preferences" class="text-gray-700 hover:text-primary">Preferences</a></li>
            <li><a href="#payments" class="text-gray-700 hover:text-primary">Payments</a></li>
            <li><a href="#privacy" class="text-gray-700 hover:text-primary">Privacy</a></li>
            <li><a href="profile_page.php#booking-history" class="text-gray-700 hover:text-primary">Booking History</a></li>
            <li>
              <form action="logout.php" method="POST" style="margin:0;">
                <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Log out</button>
              </form>
            </li>
          </ul>
        </div>
      </section>

      <!-- Right Content: Settings sections -->
      <section class="lg:col-span-2 space-y-8">

    <div id="profile" class="bg-white p-6 rounded-lg shadow-lg settings-section">
      <h3 class="text-xl font-bold text-primary mb-4">Profile</h3>
      <form action="update_profile.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label for="settings-name" class="block text-sm font-medium text-gray-700">Full Name</label>
          <input type="text" id="settings-name" name="name" value="<?php echo htmlspecialchars($userName); ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>
        <div>
          <label for="settings-email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" id="settings-email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600" />
        </div>
        <div>
          <label for="settings-phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
          <input type="tel" id="settings-phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>" pattern="[0-9]{11}" maxlength="11" placeholder="e.g., 09123456789" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>
        <div>
          <label for="settings-profile-pic" class="block text-sm font-medium text-gray-700">Profile Picture</label>
          <div class="flex items-center gap-4 mt-1">
            <div class="w-16 h-16 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center text-primary font-bold">
              <?php if (!empty($_SESSION['profile_pic'])): ?>
                <img src="<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile" class="w-full h-full object-cover">
              <?php else: ?>
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
              <?php endif; ?>
            </div>
            <input type="file" id="settings-profile-pic" name="profile_pic" accept="image/*" />
          </div>
          <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG/PNG only.</p>
        </div>
        <div class="md:col-span-2">
          <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md font-bold hover:bg-opacity-90">Save Changes</button>
        </div>
      </form>
    </div>

    <div id="security" class="bg-white p-6 rounded-lg shadow-lg settings-section">
      <h3 class="text-xl font-bold text-primary mb-4">Security</h3>
      <form action="change_password.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
          <label for="current-password" class="block text-sm font-medium text-gray-700">Current Password</label>
          <input type="password" id="current-password" name="current_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>
        <div>
          <label for="new-password" class="block text-sm font-medium text-gray-700">New Password</label>
          <input type="password" id="new-password" name="new_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>
        <div>
          <label for="confirm-password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
          <input type="password" id="confirm-password" name="confirm_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>
        <div class="md:col-span-3">
          <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md font-bold hover:bg-opacity-90">Update Password</button>
        </div>
      </form>
      <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <h4 class="font-semibold mb-2">Two-Factor Authentication</h4>
          <p class="text-sm text-gray-600 mb-3">Coming soon: Email code or authenticator app.</p>
          <button class="px-4 py-2 bg-gray-200 rounded-md text-gray-600 cursor-not-allowed">Set up</button>
        </div>
        <div>
          <h4 class="font-semibold mb-2">Active Sessions</h4>
          <p class="text-sm text-gray-600 mb-3">Review your logged-in devices.</p>
          <button class="px-4 py-2 bg-gray-200 rounded-md text-gray-600 cursor-not-allowed">Sign out all</button>
        </div>
      </div>
    </div>

    <div id="preferences" class="bg-white p-6 rounded-lg shadow-lg settings-section">
      <h3 class="text-xl font-bold text-primary mb-4">Preferences</h3>
      <form action="update_preferences.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-3">
          <h4 class="font-semibold mb-2">Notifications</h4>
          <label class="inline-flex items-center mr-6">
            <input type="checkbox" name="notify_email" value="1" class="mr-2" <?php echo !empty($_SESSION['pref_notify_email']) ? 'checked' : ''; ?>> Email updates
          </label>
          <label class="inline-flex items-center">
            <input type="checkbox" name="notify_sms" value="1" class="mr-2" <?php echo !empty($_SESSION['pref_notify_sms']) ? 'checked' : ''; ?>> SMS updates
          </label>
        </div>
        <div>
          <label for="language" class="block text-sm font-medium text-gray-700">Language</label>
          <?php $lang = $_SESSION['pref_language'] ?? 'en'; ?>
          <select id="language" name="language" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            <option value="en" <?php echo $lang==='en'?'selected':''; ?>>English</option>
            <option value="fil" <?php echo $lang==='fil'?'selected':''; ?>>Filipino</option>
          </select>
        </div>
        <div>
          <label for="timezone" class="block text-sm font-medium text-gray-700">Time Zone</label>
          <?php $tz = $_SESSION['pref_timezone'] ?? 'Asia/Manila'; ?>
          <select id="timezone" name="timezone" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            <option value="Asia/Manila" <?php echo $tz==='Asia/Manila'?'selected':''; ?>>Asia/Manila (GMT+8)</option>
            <option value="UTC" <?php echo $tz==='UTC'?'selected':''; ?>>UTC</option>
          </select>
        </div>
        <div>
          <label for="time_window" class="block text-sm font-medium text-gray-700">Preferred Time Window</label>
          <?php $tw = $_SESSION['pref_time_window'] ?? 'any'; ?>
          <select id="time_window" name="time_window" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
            <option value="any" <?php echo $tw==='any'?'selected':''; ?>>Anytime</option>
            <option value="morning" <?php echo $tw==='morning'?'selected':''; ?>>Morning</option>
            <option value="afternoon" <?php echo $tw==='afternoon'?'selected':''; ?>>Afternoon</option>
            <option value="evening" <?php echo $tw==='evening'?'selected':''; ?>>Evening</option>
          </select>
        </div>
        <div class="md:col-span-3">
          <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md font-bold hover:bg-opacity-90">Save Preferences</button>
        </div>
      </form>
    </div>

    <div id="payments" class="bg-white p-6 rounded-lg shadow-lg settings-section">
      <h3 class="text-xl font-bold text-primary mb-2">Payments</h3>
      <p class="text-gray-600">Saved payment methods and billing receipts — coming soon.</p>
    </div>

    <div id="privacy" class="bg-white p-6 rounded-lg shadow-lg settings-section">
      <h3 class="text-xl font-bold text-primary mb-2">Privacy</h3>
      <ul class="list-disc pl-6 text-gray-700">
        <li>Data visibility controls — coming soon.</li>
        <li>Download my data — coming soon.</li>
        <li>Deactivate/Delete account — coming soon.</li>
      </ul>
    </div>

      </section>
    </div>
  </div>

  <footer class="bg-[#232067] text-white py-12">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-4 gap-8 text-center md:text-left">
        <div>
          <h3 class="text-xl font-bold mb-4">Maysan Badminton Court</h3>
          <p class="text-secondary">Maysan Badminton Court is a project of Valenzuela Congressman Eric Martinez</p>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li>
              <a href="index.php" class="text-secondary hover:text-white font-bold underline transition">Home</a>
            </li>
            <li><a href="schedule.php" class="text-secondary hover:text-white transition">Schedule</a></li>
            <li><a href="faqs.php" class="text-secondary hover:text-white transition font-bold">FAQs</a></li>
            <li><a href="contact.php" class="text-secondary hover:text-white transition">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Legal</h4>
          <ul class="space-y-2">
            <li><a href="terms.php" class="text-secondary hover:text-white transition">Terms of Service</a></li>
            <li><a href="privacy.php" class="text-secondary hover:text-white transition">Privacy Policy</a></li>
            <li><a href="cookies.php" class="text-secondary hover:text-white transition">Cookie Policy</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Connect</h4>
          <div class="flex justify-center md:justify-start space-x-4">
            <a href="https://www.facebook.com/people/Valenzuela-City-Smashers/100091987359934/" class="text-secondary hover:text-white transition">
              <i data-feather="facebook"></i>
            </a>
            <a href="#" class="text-secondary hover:text-white transition">
              <i class="fa-brands fa-whatsapp fa-xl"></i>
            </a>
          </div>
          <div class="mt-4">
            <p class="text-secondary md:break-words [@media(min-width:1100px)]:break-normal">support@maysanbadmintoncourt.site</p>
            <p class="text-secondary">0915-865-3350</p>
          </div>
        </div>
      </div>

      <div class="border-t border-white mt-8 pt-8 text-center text-secondary">
        <p>© 2025 Maysan Badminton Court. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    feather.replace();
    (function(){
      const ids = ['profile','security','preferences','payments','privacy'];
      function showSection(targetId){
        ids.forEach(id => {
          const el = document.getElementById(id);
          if (!el) return;
          if (id === targetId) {
            el.classList.remove('hidden');
          } else {
            el.classList.add('hidden');
          }
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }
      const initial = (location.hash || '#profile').substring(1);
      showSection(initial);
      window.addEventListener('hashchange', () => {
        const id = (location.hash || '#profile').substring(1);
        showSection(id);
      });
      // Mobile menu toggle compatibility, mirroring profile_page.php
      const menu = document.getElementById('mobile-menu');
      const navLinks = document.getElementById('nav-links');
      if (menu && navLinks) {
        menu.addEventListener('click', () => {
          navLinks.classList.toggle('active');
          menu.classList.toggle('open');
        });
      }
    })();
  </script>
</body>
</html>

