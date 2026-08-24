<?php
require 'config.php';
$baseDir = 'uploads/';
?>
<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Models | Studio360</title>
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
                        lightbg: '#f8fafc'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #f8fafc;
            color: #1f2937;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.08);
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
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

<body class="min-h-screen p-4 md:p-8 relative overflow-x-hidden">
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-red-400/10 rounded-full blur-[120px] pointer-events-none z-0"></div>
    <div class="max-w-[1400px] mx-auto relative z-10">
        <header class="text-center mb-12 border-b border-gray-200 pb-8 pt-4">
            <h1 class="text-4xl md:text-6xl font-black tracking-tight uppercase mb-3 text-gray-900">Our <span class="text-studio">Talents</span></h1>
            <p class="text-gray-500 text-sm md:text-base tracking-[0.2em] uppercase font-semibold">Select the perfect face for your next project</p>
        </header>

        <div class="glass-panel p-4 rounded-2xl mb-12 flex flex-col md:flex-row gap-4 items-center justify-center max-w-4xl mx-auto shadow-lg">
            <div class="flex-1 w-full relative"><i class="fa-solid fa-search absolute left-4 top-3.5 text-gray-400"></i><input type="text" id="searchInput" onkeyup="filterModels()" placeholder="Search models by name..." class="w-full bg-white border border-gray-200 rounded-xl py-2.5 pl-11 pr-4 text-gray-700 focus:outline-none focus:border-studio shadow-sm"></div>
            <div class="w-full md:w-36"><select id="genderFilter" onchange="filterModels()" class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:border-studio appearance-none shadow-sm text-center">
                    <option value="">All Genders</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select></div>
            <div class="w-full md:w-36"><select id="ageFilter" onchange="filterModels()" class="w-full bg-white border border-gray-200 rounded-xl py-2.5 px-4 text-gray-700 focus:outline-none focus:border-studio appearance-none shadow-sm text-center">
                    <option value="">All Ages</option>
                    <option value="0-18">Under 18</option>
                    <option value="19-25">19 - 25</option>
                    <option value="26-35">26 - 35</option>
                    <option value="36+">36+</option>
                </select></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="modelsGrid">
            <?php
            $stmt = $pdo->query("SELECT * FROM registered_models ORDER BY id DESC");
            $models = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $studioWhatsApp = "8801632344221";

            if (count($models) > 0) {
                foreach ($models as $d) {
                    $folderPath = $baseDir . $d['folder_name'];
                    $images = glob($folderPath . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
                    $coverImg = !empty($images) ? $images[0] : 'https://via.placeholder.com/400x500/f8fafc/ef4444?text=No+Photo';

                    $isPro = (strtolower($d['experience']) == 'yes' || strtolower($d['experience']) == 'হ্যাঁ');
                    $searchName = strtolower($d['name']);
                    $ageVal = (int)$d['age'];
                    $searchGender = strtolower(trim($d['gender'] ?? ''));
                    $whatsappMsg = urlencode("Hello Studio360, I am interested in booking this model: \nName: {$d['name']} \nAge: {$d['age']} \nGender: {$d['gender']}");

                    echo "
                    <div class='model-card bg-white rounded-[2rem] overflow-hidden group flex flex-col relative border border-gray-100 hover:border-red-400 hover:shadow-[0_15px_40px_rgba(239,68,68,0.15)] transition-all duration-500 shadow-xl' data-name='{$searchName}' data-age='{$ageVal}' data-gender='{$searchGender}'>
                        <div class='relative h-80 overflow-hidden bg-gray-100'>
                            <img src='{$coverImg}' class='w-full h-full object-cover transition-transform duration-700 group-hover:scale-110'>
                            <div class='absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent'></div>
                            " . ($isPro ? "<div class='absolute top-4 left-4 bg-studio text-white text-[10px] uppercase font-black tracking-wider px-3 py-1.5 rounded shadow-lg'><i class='fa-solid fa-star mr-1'></i>PRO MODEL</div>" : "") . "
                            <div class='absolute bottom-5 left-5 right-5 z-10'>
                                <h2 class='text-2xl font-black text-white truncate mb-3 tracking-wide'>{$d['name']}</h2>
                                <div class='flex gap-2 text-xs font-bold uppercase tracking-wider'>
                                    " . (!empty($d['gender']) ? "<span class='bg-white/90 backdrop-blur-md border border-white/50 px-3 py-1.5 rounded-lg text-gray-800 shadow-sm'>" . (strtolower($d['gender']) == 'male' ? "<i class='fa-solid fa-mars text-blue-500 mr-1.5'></i>" : "<i class='fa-solid fa-venus text-pink-500 mr-1.5'></i>") . "{$d['gender']}</span>" : "") . "
                                    " . (!empty($d['age']) ? "<span class='bg-white/90 backdrop-blur-md border border-white/50 px-3 py-1.5 rounded-lg text-gray-800 shadow-sm'>{$d['age']} Years</span>" : "") . "
                                </div>
                            </div>
                        </div>
                        <div class='p-6 flex-1 flex flex-col z-10 bg-white'>
                            <div class='flex justify-between items-center mb-6'>
                                <div class='flex flex-col'><span class='text-gray-400 text-[10px] uppercase tracking-widest mb-1 font-bold'>Height / Weight</span><span class='font-semibold text-gray-700'>{$d['height']} / {$d['weight']}</span></div>
                                <div class='flex flex-col text-right'><span class='text-gray-400 text-[10px] uppercase tracking-widest mb-1 font-bold'>Base Location</span><span class='font-semibold text-gray-700'><i class='fa-solid fa-location-dot text-studio text-xs mr-1'></i>" . ($d['address'] ? explode(',', $d['address'])[0] : 'N/A') . "</span></div>
                            </div>
                            
                            " . (!empty($d['tiktok_link']) || !empty($d['insta_link']) || !empty($d['fb_link']) ? "
                            <div class='flex gap-2 mb-6'>
                                " . (!empty($d['tiktok_link']) ? "<a href='{$d['tiktok_link']}' target='_blank' class='flex-1 flex justify-center bg-[#ff0050]/10 text-[#ff0050] hover:bg-[#ff0050] hover:text-white rounded-lg py-2 transition-all'><i class='fa-brands fa-tiktok text-lg'></i></a>" : "") . "
                                " . (!empty($d['insta_link']) ? "<a href='{$d['insta_link']}' target='_blank' class='flex-1 flex justify-center bg-pink-500/10 text-pink-500 hover:bg-pink-500 hover:text-white rounded-lg py-2 transition-all'><i class='fa-brands fa-instagram text-lg'></i></a>" : "") . "
                                " . (!empty($d['fb_link']) ? "<a href='{$d['fb_link']}' target='_blank' class='flex-1 flex justify-center bg-blue-600/10 text-blue-500 hover:bg-blue-600 hover:text-white rounded-lg py-2 transition-all'><i class='fa-brands fa-facebook-f text-lg'></i></a>" : "") . "
                            </div>" : "") . "

                            <div class='mt-auto pt-2'>
                                <a href='https://wa.me/{$studioWhatsApp}?text={$whatsappMsg}' target='_blank' class='w-full block text-center bg-gray-900 hover:bg-studio text-white font-bold text-sm uppercase tracking-widest py-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-red-500/40'>
                                    <i class=" . "fa-regular fa-calendar-check mr-2" . "></i> Book This Model
                                </a>
                            </div>
                        </div>
                    </div>";
                }
            } else {
                echo "<div class='col-span-full text-center py-24'><i class='fa-solid fa-users-slash text-6xl text-gray-300 mb-4'></i><h2 class='text-2xl font-bold text-gray-400'>No Models Available Yet</h2></div>";
            }
            ?>
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
    </script>
</body>

</html>