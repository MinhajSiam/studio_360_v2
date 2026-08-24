<?php
session_start();
$admin_password = 'Studio@360';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: models.php");
    exit;
}

$error_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_password'])) {
    if ($_POST['login_password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: models.php");
        exit;
    } else {
        $error_msg = 'ভুল পাসওয়ার্ড! আবার চেষ্টা করুন।';
    }
}

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
?>
    <!DOCTYPE html>
    <html lang="bn">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login | Studio360</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Outfit', 'sans-serif']
                        },
                        colors: {
                            studio: '#ef4444'
                        }
                    }
                }
            }
        </script>
        <style>
            body {
                background-color: #0a0a0a;
                color: #fff;
            }
        </style>
    </head>

    <body class="min-h-screen flex items-center justify-center relative px-4">
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-red-600/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 right-1/4 w-72 h-72 bg-red-900/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="w-full max-w-md bg-black/60 backdrop-blur-xl border border-gray-800 rounded-3xl p-8 relative z-10 shadow-2xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-900 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-[0_0_30px_rgba(239,68,68,0.3)]"><i class="fa-solid fa-lock text-2xl text-white"></i></div>
                <h1 class="text-3xl font-black uppercase tracking-tight">Studio<span class="text-studio">360</span></h1>
                <p class="text-gray-500 text-sm mt-1 uppercase tracking-widest font-semibold">Secured Access Only</p>
            </div><?php if ($error_msg): ?><div class="bg-red-500/10 border border-red-500/30 text-red-500 text-sm font-semibold p-3 rounded-lg text-center mb-6"><i class="fa-solid fa-triangle-exclamation mr-1"></i> <?php echo $error_msg; ?></div><?php endif; ?><form method="POST" class="space-y-6">
                <div><label class="block text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wide">Enter Admin Password</label>
                    <div class="relative"><i class="fa-solid fa-key absolute left-4 top-3.5 text-gray-500"></i><input type="password" name="login_password" required class="w-full bg-black/50 border border-gray-700 rounded-xl py-3 pl-11 pr-4 text-white focus:outline-none focus:border-studio transition-all" placeholder="••••••••"></div>
                </div><button type="submit" class="w-full bg-studio hover:bg-red-600 text-white font-bold py-3.5 rounded-xl transition-all shadow-[0_0_20px_rgba(239,68,68,0.3)] flex justify-center items-center gap-2 uppercase tracking-wider"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
            </form>
        </div>
    </body>

    </html>
<?php exit;
}

require 'config.php';
$baseDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("SELECT folder_name FROM registered_models WHERE id = ?");
    $stmt->execute([$id]);
    $model = $stmt->fetch();
    if ($model) {
        $dirPath = $baseDir . $model['folder_name'];
        if (is_dir($dirPath)) {
            $files = glob($dirPath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }
            rmdir($dirPath);
        }
        $pdo->prepare("DELETE FROM registered_models WHERE id = ?")->execute([$id]);
    }
    header("Location: models.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("UPDATE registered_models SET name=?, gender=?, age=?, phone=?, address=?, height=?, weight=?, insta_link=?, fb_link=?, drive_link=?, tiktok_link=?, rem_whole_day=?, rem_per_content=? WHERE id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['gender'],
        $_POST['age'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['height'],
        $_POST['weight'],
        $_POST['instaLink'],
        $_POST['fbLink'],
        $_POST['driveLink'],
        $_POST['tiktokLink'],
        $_POST['remWholeDay'],
        $_POST['remPerContent'],
        $id
    ]);
    header("Location: models.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Directory | Studio360</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif']
                    },
                    colors: {
                        studio: '#ef4444',
                        darkbg: '#0a0a0a',
                        panel: '#141414'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #0a0a0a;
            color: #fff;
        }

        .glass-panel {
            background: rgba(20, 20, 20, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        ::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #ef4444;
        }

        .hide-scroll::-webkit-scrollbar {
            display: none;
        }

        .hide-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="min-h-screen p-4 md:p-8">

    <div class="max-w-[1400px] mx-auto">
        <header class="flex flex-col md:flex-row md:justify-between md:items-end mb-8 border-b border-gray-800 pb-6 gap-4">
            <div>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight uppercase flex items-center gap-3">Studio<span class="text-studio">360</span><span class="text-gray-600 font-light text-2xl md:text-4xl">| Admin</span></h1>
            </div>
            <div class="flex gap-3">
                <button onclick="exportToCSV()" class="bg-green-600 hover:bg-green-500 text-white px-5 py-2.5 rounded-lg font-semibold transition-all flex items-center gap-2 shadow-lg shadow-green-900/50 text-sm"><i class="fa-solid fa-file-excel"></i> Export</button>
                <a href="?logout=true" class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 px-5 py-2.5 rounded-lg font-semibold transition-all flex items-center gap-2 text-sm"><i class="fa-solid fa-power-off"></i> Logout</a>
            </div>
        </header>

        <div class="glass-panel p-4 rounded-xl mb-8 flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1 w-full relative"><i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-500"></i><input type="text" id="searchInput" onkeyup="filterModels()" placeholder="Search by name..." class="w-full bg-black/50 border border-gray-700 rounded-lg py-2.5 pl-11 pr-4 text-white focus:outline-none focus:border-studio"></div>
            <div class="w-full md:w-40"><input type="text" id="locationFilter" onkeyup="filterModels()" placeholder="Filter Location" class="w-full bg-black/50 border border-gray-700 rounded-lg py-2.5 px-4 text-white focus:outline-none focus:border-studio"></div>
            <div class="w-full md:w-32"><select id="genderFilter" onchange="filterModels()" class="w-full bg-black/50 border border-gray-700 rounded-lg py-2.5 px-4 text-white focus:outline-none focus:border-studio appearance-none">
                    <option value="">All Genders</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select></div>
            <div class="w-full md:w-32"><select id="ageFilter" onchange="filterModels()" class="w-full bg-black/50 border border-gray-700 rounded-lg py-2.5 px-4 text-white focus:outline-none focus:border-studio appearance-none">
                    <option value="">All Ages</option>
                    <option value="0-18">Under 18</option>
                    <option value="19-25">19 - 25</option>
                    <option value="26-35">26 - 35</option>
                    <option value="36+">36+</option>
                </select></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="modelsGrid">
            <?php
            $exportData = [];
            $stmt = $pdo->query("SELECT * FROM registered_models ORDER BY id DESC");
            $models = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($models) > 0) {
                foreach ($models as $d) {
                    $folderPath = $baseDir . $d['folder_name'];
                    $images = glob($folderPath . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
                    $coverImg = !empty($images) ? $images[0] : 'https://via.placeholder.com/400x500/1a1a1a/ef4444?text=No+Photo';
                    $cleanPhone = preg_replace('/[^0-9]/', '', $d['phone']);
                    $exportData[] = $d;

                    $searchName = strtolower($d['name']);
                    $searchLoc = strtolower($d['address']);
                    $ageVal = (int)$d['age'];
                    $searchGender = strtolower(trim($d['gender'] ?? ''));
                    $jsonData = htmlspecialchars(json_encode($d), ENT_QUOTES, 'UTF-8');

                    echo "
                    <div class='model-card glass-panel rounded-2xl overflow-hidden group flex flex-col relative' data-name='{$searchName}' data-loc='{$searchLoc}' data-age='{$ageVal}' data-gender='{$searchGender}'>
                        <div class='absolute top-3 right-3 z-20 flex gap-2'>
                            <button data-info='{$jsonData}' onclick='openEditModal(this)' class='w-8 h-8 rounded bg-blue-500/80 hover:bg-blue-500 text-white flex items-center justify-center backdrop-blur shadow-lg transition-all'><i class='fa-solid fa-pen text-xs'></i></button>
                            <form method='POST' onsubmit='return confirm(\"Are you sure you want to delete this model completely?\")' class='m-0'><input type='hidden' name='action' value='delete'><input type='hidden' name='id' value='{$d['id']}'><button type='submit' class='w-8 h-8 rounded bg-red-600/80 hover:bg-red-600 text-white flex items-center justify-center backdrop-blur shadow-lg transition-all'><i class='fa-solid fa-trash text-xs'></i></button></form>
                        </div>
                        <div class='relative h-80 overflow-hidden'>
                            <img src='{$coverImg}' class='w-full h-full object-cover transition-transform duration-700 group-hover:scale-105'>
                            <div class='absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent'></div>
                            <div class='absolute bottom-4 left-5 right-5 z-10'>
                                <h2 class='text-2xl font-bold text-white truncate mb-2'>{$d['name']}</h2>
                                <div class='flex gap-2 text-xs font-semibold'>
                                    " . (!empty($d['gender']) ? "<span class='bg-white/10 backdrop-blur border border-white/20 px-2.5 py-1 rounded-md text-gray-200'>" . (strtolower($d['gender']) == 'male' ? "<i class='fa-solid fa-mars text-blue-400 mr-1'></i>" : "<i class='fa-solid fa-venus text-pink-400 mr-1'></i>") . "{$d['gender']}</span>" : "") . "
                                    " . (!empty($d['age']) ? "<span class='bg-white/10 backdrop-blur border border-white/20 px-2.5 py-1 rounded-md text-gray-200'><i class='fa-regular fa-calendar mr-1'></i>{$d['age']}y</span>" : "") . "
                                </div>
                            </div>
                        </div>
                        <div class='p-5 flex-1 flex flex-col z-10 bg-panel'>
                            <div class='grid grid-cols-2 gap-4 mb-4 text-sm'>
                                <div class='flex flex-col'><span class='text-gray-500 text-[10px] uppercase tracking-wider mb-1'>Height / Weight</span><span class='font-medium text-gray-300 truncate'>{$d['height']} / {$d['weight']}</span></div>
                                <div class='flex flex-col'><span class='text-gray-500 text-[10px] uppercase tracking-wider mb-1'>Location</span><span class='font-medium text-gray-300 break-words leading-tight'>" . ($d['address'] ?: 'N/A') . "</span></div>
                            </div>
                            
                            <div class='bg-black/50 rounded-lg p-3 border border-gray-800 mb-4'>
                                <h4 class='text-[10px] text-gray-500 uppercase tracking-wider mb-2 font-bold'>Expected Remuneration</h4>
                                <div class='flex justify-between text-sm'><span class='text-gray-400'>Whole Day:</span><span class='text-studio font-bold'>৳ {$d['rem_whole_day']}</span></div>
                                <div class='flex justify-between text-sm'><span class='text-gray-400'>Per Content:</span><span class='text-studio font-bold'>৳ {$d['rem_per_content']}</span></div>
                            </div>

                            <div class='flex gap-2 mb-6'>
                                " . (!empty($cleanPhone) ? "<a href='https://wa.me/88{$cleanPhone}' target='_blank' class='flex-1 flex items-center justify-center bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white border border-[#25D366]/20 rounded-lg py-2 transition-all'><i class='fa-brands fa-whatsapp text-lg'></i></a>" : "") . "
                                " . (!empty($d['insta_link']) ? "<a href='{$d['insta_link']}' target='_blank' class='flex-1 flex items-center justify-center bg-pink-500/10 text-pink-500 hover:bg-pink-500 hover:text-white border border-pink-500/20 rounded-lg py-2 transition-all'><i class='fa-brands fa-instagram text-lg'></i></a>" : "") . "
                                " . (!empty($d['tiktok_link']) ? "<a href='{$d['tiktok_link']}' target='_blank' class='flex-1 flex items-center justify-center bg-[#ff0050]/10 text-[#ff0050] hover:bg-[#ff0050] hover:text-white border border-[#ff0050]/20 rounded-lg py-2 transition-all'><i class='fa-brands fa-tiktok text-lg'></i></a>" : "") . "
                                " . (!empty($d['drive_link']) ? "<a href='{$d['drive_link']}' target='_blank' class='flex-1 flex items-center justify-center bg-green-500/10 text-green-500 hover:bg-green-500 hover:text-white border border-green-500/20 rounded-lg py-2 transition-all'><i class='fa-brands fa-google-drive text-lg'></i></a>" : "") . "
                            </div>
                            <div class='mt-auto'>
                                <div class='flex justify-between items-end mb-2 border-t border-gray-800 pt-3'>
                                    <h3 class='text-[10px] text-gray-500 uppercase tracking-widest font-semibold'>Photos Gallery</h3>
                                    <span class='text-xs font-bold text-studio bg-red-500/10 px-2 py-0.5 rounded'>" . (is_array($images) ? count($images) : 0) . "</span>
                                </div>
                                <div class='flex gap-2 overflow-x-auto pb-1 hide-scroll fancybox-gallery-{$d['id']}'>";
                    if (is_array($images)) {
                        foreach ($images as $img) {
                            echo "<a href='{$img}' data-fancybox='gallery-{$d['id']}' class='flex-shrink-0 cursor-zoom-in group/img'><img src='{$img}' class='h-12 w-12 object-cover rounded-lg border border-gray-800 group-hover/img:border-studio opacity-70 hover:opacity-100 transition-all'></a>";
                        }
                    }
                    echo "      </div></div></div></div>";
                }
            } else {
                echo "<div class='col-span-full text-center py-20 text-gray-500'>No database records found.</div>";
            }
            ?>
        </div>
    </div>

    <script>
        const exportData = <?php echo json_encode($exportData ?? []); ?>;
    </script>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-panel border border-gray-700 rounded-2xl w-full max-w-2xl p-6 relative max-h-[90vh] overflow-y-auto">
            <button onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-xl"></i></button>
            <h2 class="text-2xl font-bold mb-6">Edit Database Record</h2>
            <form method="POST" id="editForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="action" value="edit"><input type="hidden" name="id" id="editId">
                <div><label class="text-xs text-gray-400">Name</label><input type="text" name="name" id="editName" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Gender</label><select name="gender" id="editGender" class="w-full bg-black border border-gray-700 rounded p-2 text-white appearance-none">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select></div>
                <div><label class="text-xs text-gray-400">Age</label><input type="text" name="age" id="editAge" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Phone</label><input type="text" name="phone" id="editPhone" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Location</label><input type="text" name="address" id="editAddress" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Height</label><input type="text" name="height" id="editHeight" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Weight</label><input type="text" name="weight" id="editWeight" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>

                <div class="col-span-full border-t border-gray-800 pt-4 mt-2">
                    <h3 class="text-sm font-semibold mb-3 text-studio">Remuneration</h3>
                </div>
                <div><label class="text-xs text-gray-400">Whole Day</label><input type="text" name="remWholeDay" id="editRemWholeDay" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Per Content</label><input type="text" name="remPerContent" id="editRemPerContent" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>

                <div class="col-span-full border-t border-gray-800 pt-4 mt-2">
                    <h3 class="text-sm font-semibold mb-3 text-studio">Social Links</h3>
                </div>
                <div><label class="text-xs text-gray-400">Instagram</label><input type="text" name="instaLink" id="editInsta" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Facebook</label><input type="text" name="fbLink" id="editFB" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">TikTok</label><input type="text" name="tiktokLink" id="editTikTok" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div><label class="text-xs text-gray-400">Google Drive</label><input type="text" name="driveLink" id="editDrive" class="w-full bg-black border border-gray-700 rounded p-2 text-white"></div>
                <div class="col-span-full mt-4"><button type="submit" class="w-full bg-studio hover:bg-red-600 text-white font-bold py-3 rounded-lg transition-all">Save to Database</button></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
            Hash: false,
            Thumbs: {
                autoStart: true
            }
        });

        function filterModels() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let genderFilter = document.getElementById('genderFilter').value.toLowerCase();
            let ageRange = document.getElementById('ageFilter').value;
            let cards = document.querySelectorAll('.model-card');
            cards.forEach(card => {
                let name = card.getAttribute('data-name');
                let gender = card.getAttribute('data-gender');
                let age = parseInt(card.getAttribute('data-age')) || 0;
                let matchesSearch = name.includes(search);
                let matchesGender = (genderFilter === '' || gender === genderFilter);
                let matchesAge = true;
                if (ageRange === '0-18') matchesAge = (age > 0 && age <= 18);
                if (ageRange === '19-25') matchesAge = (age >= 19 && age <= 25);
                if (ageRange === '26-35') matchesAge = (age >= 26 && age <= 35);
                if (ageRange === '36+') matchesAge = (age >= 36);
                card.style.display = (matchesSearch && matchesGender && matchesAge) ? 'flex' : 'none';
            });
        }

        function exportToCSV() {
            if (exportData.length === 0) {
                alert("No data to export!");
                return;
            }
            let csvContent = "data:text/csv;charset=utf-8,";
            let headers = Object.keys(exportData[0]).join(",");
            csvContent += headers + "\r\n";
            exportData.forEach(function(rowArray) {
                let row = Object.values(rowArray).map(val => {
                    return `"${val ? String(val).replace(/"/g, '""') : ''}"`;
                }).join(",");
                csvContent += row + "\r\n";
            });
            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Studio360_Database_Export.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function openEditModal(btn) {
            let data = JSON.parse(btn.getAttribute('data-info'));
            document.getElementById('editId').value = data['id'];
            document.getElementById('editName').value = data['name'] || '';
            document.getElementById('editGender').value = data['gender'] || 'Male';
            document.getElementById('editAge').value = data['age'] || '';
            document.getElementById('editPhone').value = data['phone'] || '';
            document.getElementById('editAddress').value = data['address'] || '';
            document.getElementById('editHeight').value = data['height'] || '';
            document.getElementById('editWeight').value = data['weight'] || '';
            document.getElementById('editRemWholeDay').value = data['rem_whole_day'] || '';
            document.getElementById('editRemPerContent').value = data['rem_per_content'] || '';
            document.getElementById('editInsta').value = data['insta_link'] || '';
            document.getElementById('editFB').value = data['fb_link'] || '';
            document.getElementById('editTikTok').value = data['tiktok_link'] || '';
            document.getElementById('editDrive').value = data['drive_link'] || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>

</html>