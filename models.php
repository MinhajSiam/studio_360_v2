<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Gallery | Studio360</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-8 border-b-4 border-red-500 pb-2 inline-block">Registered Models</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $baseDir = 'uploads/';
            if (is_dir($baseDir)) {
                $folders = array_diff(scandir($baseDir), array('..', '.'));
                rsort($folders); // নতুনগুলো আগে দেখাবে

                foreach ($folders as $folder) {
                    $folderPath = $baseDir . $folder;
                    if (is_dir($folderPath)) {
                        
                        // details.txt থেকে ডেটা পড়া
                        $detailsText = file_exists($folderPath.'/details.txt') ? nl2br(file_get_contents($folderPath.'/details.txt')) : 'No details found.';
                        
                        // কভার ইমেজের জন্য ফোল্ডারের প্রথম ছবিটি খোঁজা
                        $images = glob($folderPath . "/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
                        $coverImg = !empty($images) ? $images[0] : 'https://via.placeholder.com/400x300?text=No+Image';

                        // ফোল্ডারের নাম সুন্দর করে দেখানো
                        $displayName = str_replace('_', ' ', explode('_', $folder)[0] . ' (' . explode('_', $folder)[1] . ')');

                        echo "
                        <div class='bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200 transition hover:shadow-2xl'>
                            <img src='{$coverImg}' class='w-full h-64 object-cover' alt='Cover'>
                            <div class='p-6'>
                                <h2 class='text-2xl font-bold text-gray-800 mb-2'>{$displayName}</h2>
                                <p class='text-sm text-gray-500 mb-4 h-24 overflow-y-auto bg-gray-50 p-2 rounded border'>{$detailsText}</p>
                                
                                <div class='mt-4'>
                                    <h3 class='text-sm font-semibold text-red-600 mb-2'>All Photos:</h3>
                                    <div class='flex gap-2 overflow-x-auto pb-2'>";
                                    foreach($images as $img) {
                                        echo "<a href='{$img}' target='_blank'><img src='{$img}' class='h-16 w-16 object-cover rounded shadow-sm border border-gray-300'></a>";
                                    }
                        echo "      </div>
                                </div>
                            </div>
                        </div>";
                    }
                }
            } else {
                echo "<p class='text-gray-500'>No models registered yet.</p>";
            }
            ?>
        </div>
    </div>

</body>
</html>