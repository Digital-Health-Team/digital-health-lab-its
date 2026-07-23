<?php

/**
 * Static lab-activity news feed (workshops, visits, achievements,
 * collaborations). Ponytail: no DB table / model / migration / admin CRUD —
 * add those when this needs editing without a deploy. This array is the
 * single source of truth; database/seeders/LandingContentSeeder.php mirrors
 * the first entry (featured) + the next four into the `articles_*` CMS keys,
 * and NewsController reads it directly for the /news routes. Ordered newest
 * first — the landing section's "featured" is index 0.
 */
return [
    'articles' => [
        [
            'slug' => 'workshop-cetak-3d-prostetik-pelajar-sma',
            'title' => 'IDIG Gelar Workshop Cetak 3D Prostetik untuk Pelajar SMA',
            'category' => 'Workshop',
            'date' => '12 Juni 2025',
            'excerpt' => 'Puluhan siswa SMA se-Surabaya mencoba langsung proses desain hingga perakitan akhir prostetik cetak 3D, didampingi tim peneliti laboratorium.',
            'body' => [
                'Laboratorium Teknologi Rekayasa Medis (IDIG HTech) ITS menyelenggarakan workshop cetak 3D prostetik bagi 40 pelajar dari lima SMA di Surabaya, Kamis (12/6). Kegiatan ini menjadi bagian dari program pengenalan teknologi fabrikasi digital kepada calon mahasiswa teknik biomedis.',
                'Peserta diajak menyusun desain lengan prostetik sederhana menggunakan perangkat lunak CAD, sebelum menyaksikan proses pencetakan berlangsung di laboratorium. Tim peneliti menjelaskan pertimbangan material dan toleransi cetak yang memengaruhi kekuatan struktural perangkat.',
                'Menurut penanggung jawab kegiatan, workshop semacam ini rutin diadakan untuk membuka akses pelajar terhadap riset teknologi kesehatan sejak dini, sekaligus memperkenalkan kapasitas fabrikasi yang dimiliki laboratorium.',
            ],
            'image' => '/assets/images/projects/clinical_3d_printing.png',
            'image_alt' => 'Peserta workshop mengamati proses pencetakan 3D prostetik di laboratorium IDIG HTech',
        ],
        [
            'slug' => 'kunjungan-rsud-dr-soetomo-uji-ortosis',
            'title' => 'Tim Lab Uji Coba Ortosis Cetak 3D di RSUD Dr. Soetomo',
            'category' => 'Kunjungan',
            'date' => '5 Juni 2025',
            'excerpt' => 'Kunjungan kerja sama dengan unit rehabilitasi medik menguji kecocokan ortosis kaki hasil cetak 3D pada pasien uji coba.',
            'body' => [
                'Tim IDIG HTech melakukan kunjungan kerja ke Instalasi Rehabilitasi Medik RSUD Dr. Soetomo, Kamis (5/6), untuk menguji langsung ortosis kaki hasil cetak 3D pada pasien yang telah menyetujui uji coba.',
                'Uji coba difokuskan pada kesesuaian ukuran, kenyamanan pemakaian, dan respons pergerakan sendi pergelangan kaki. Dokter rehabilitasi medik turut memberikan catatan klinis yang menjadi masukan revisi desain.',
                'Kunjungan ini merupakan bagian dari kolaborasi berkelanjutan antara laboratorium dan rumah sakit dalam mengembangkan alat bantu medis yang terjangkau dan sesuai kebutuhan pasien di Indonesia.',
            ],
            'image' => '/assets/images/projects/prosthetic_limb_3d.png',
            'image_alt' => 'Ortosis kaki hasil cetak 3D yang diuji coba bersama tim rehabilitasi medik',
        ],
        [
            'slug' => 'juara-kompetisi-inovasi-teknologi-kesehatan-2025',
            'title' => 'Tim IDIG Raih Juara Kompetisi Inovasi Teknologi Kesehatan Nasional',
            'category' => 'Prestasi',
            'date' => '28 Mei 2025',
            'excerpt' => 'Purwarupa alat pemantauan pasien berbasis IoT besutan mahasiswa laboratorium meraih juara satu pada ajang inovasi tingkat nasional.',
            'body' => [
                'Tim mahasiswa binaan IDIG HTech meraih juara satu pada Kompetisi Inovasi Teknologi Kesehatan Nasional 2025 melalui purwarupa alat pemantauan pasien berbasis IoT, Rabu (28/5).',
                'Alat tersebut dirancang untuk memantau tanda vital pasien rawat jalan secara nirkabel dan mengirimkan data langsung ke tenaga medis, hasil pengembangan selama satu semester di laboratorium.',
                'Prestasi ini menambah daftar capaian mahasiswa program Teknik Biomedis ITS pada ajang inovasi tingkat nasional, sekaligus menegaskan arah riset laboratorium pada teknologi kesehatan berbasis sensor dan konektivitas.',
            ],
            'image' => '/assets/images/projects/patient_monitoring_iot.png',
            'image_alt' => 'Purwarupa alat pemantauan pasien berbasis IoT yang meraih juara satu kompetisi nasional',
        ],
        [
            'slug' => 'printer-resin-baru-fabrikasi-presisi',
            'title' => 'Printer Resin Baru Perkuat Fabrikasi Presisi Laboratorium',
            'category' => 'Kabar Lab',
            'date' => '20 Mei 2025',
            'excerpt' => 'Unit printer resin generasi terbaru resmi beroperasi, memperluas kapasitas laboratorium mencetak komponen medis berpresisi tinggi.',
            'body' => [
                'Laboratorium IDIG HTech menambah satu unit printer resin generasi terbaru untuk memperkuat kapasitas fabrikasi presisi tinggi, menyusul meningkatnya permintaan pencetakan komponen medis berukuran kecil.',
                'Printer ini digunakan untuk mencetak model uji anatomi, panduan bedah, dan komponen ortotik yang membutuhkan detail permukaan halus dan toleransi ketat — hal yang sulit dicapai teknologi FDM konvensional.',
                'Penambahan unit ini merupakan bagian dari investasi berkelanjutan laboratorium dalam infrastruktur fabrikasi digital, guna menjaga kualitas keluaran riset dan layanan cetak pesanan.',
            ],
            'image' => '/assets/images/projects/stl_medical_devices.png',
            'image_alt' => 'Unit printer resin baru yang digunakan untuk fabrikasi komponen medis presisi tinggi',
        ],
        [
            'slug' => 'kolaborasi-riset-fk-universitas-airlangga',
            'title' => 'Kolaborasi Riset dengan FK Universitas Airlangga Dimulai',
            'category' => 'Kolaborasi',
            'date' => '9 Mei 2025',
            'excerpt' => 'Nota kesepahaman riset bersama Fakultas Kedokteran Universitas Airlangga membuka jalan pengembangan alat bantu medis berbasis kebutuhan klinis.',
            'body' => [
                'IDIG HTech dan Fakultas Kedokteran Universitas Airlangga menandatangani nota kesepahaman riset kolaboratif, Jumat (9/5), untuk mengembangkan alat bantu medis berbasis kebutuhan klinis di lapangan.',
                'Kolaborasi ini akan diawali dengan riset bersama mengenai kebutuhan alat bantu gerak pada pasien pascastroke, melibatkan tim klinis FK Unair dan tim rekayasa dari laboratorium.',
                'Kerja sama lintas institusi semacam ini menjadi salah satu strategi laboratorium untuk memastikan setiap inovasi yang dikembangkan benar-benar menjawab kebutuhan klinis, bukan sekadar kelayakan teknis.',
            ],
            'image' => '/assets/images/projects/medtech_research_digest_v4.png',
            'image_alt' => 'Penandatanganan nota kesepahaman riset kolaboratif dengan FK Universitas Airlangga',
        ],
    ],
];
