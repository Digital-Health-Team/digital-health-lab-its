<?php

/**
 * Static lab-activity news feed (workshops, visits, achievements,
 * collaborations). Ponytail: no DB table / model / migration / admin CRUD —
 * add those when this needs editing without a deploy. This array is the
 * single source of truth; database/seeders/LandingContentSeeder.php mirrors
 * the first entry (featured) + the next four into the `articles_*` CMS keys,
 * and NewsController reads it directly for the /news routes. Ordered newest
 * first — the landing section's "featured" is index 0.
 *
 * Indonesian is the base copy; English lives alongside it under `<key>_en`,
 * matching the convention used by `page_sections` and `lab_team_*`. See
 * NewsController::localised(). `slug` is deliberately NOT translated — it is a
 * public URL. `date` is ISO: it is formatted per locale at render time, because
 * a stored "12 Juni 2025" could only ever be one language.
 */
return [
    'articles' => [
        [
            'slug' => 'workshop-cetak-3d-prostetik-pelajar-sma',
            'date' => '2025-06-12',
            'title' => 'IDIG Gelar Workshop Cetak 3D Prostetik untuk Pelajar SMA',
            'title_en' => 'IDIG Runs a 3D-Printed Prosthetics Workshop for High-School Students',
            'category' => 'Workshop',
            'category_en' => 'Workshop',
            'excerpt' => 'Puluhan siswa SMA se-Surabaya mencoba langsung proses desain hingga perakitan akhir prostetik cetak 3D, didampingi tim peneliti laboratorium.',
            'excerpt_en' => 'Dozens of Surabaya high-school students worked through the whole process themselves — from design to final assembly of a 3D-printed prosthetic — alongside the laboratory research team.',
            'body' => [
                'Laboratorium Teknologi Rekayasa Medis (IDIG HTech) ITS menyelenggarakan workshop cetak 3D prostetik bagi 40 pelajar dari lima SMA di Surabaya, Kamis (12/6). Kegiatan ini menjadi bagian dari program pengenalan teknologi fabrikasi digital kepada calon mahasiswa teknik biomedis.',
                'Peserta diajak menyusun desain lengan prostetik sederhana menggunakan perangkat lunak CAD, sebelum menyaksikan proses pencetakan berlangsung di laboratorium. Tim peneliti menjelaskan pertimbangan material dan toleransi cetak yang memengaruhi kekuatan struktural perangkat.',
                'Menurut penanggung jawab kegiatan, workshop semacam ini rutin diadakan untuk membuka akses pelajar terhadap riset teknologi kesehatan sejak dini, sekaligus memperkenalkan kapasitas fabrikasi yang dimiliki laboratorium.',
            ],
            'body_en' => [
                'The ITS Medical Engineering Technology Laboratory (IDIG HTech) ran a 3D-printed prosthetics workshop for 40 students from five Surabaya high schools on Thursday 12 June. The session is part of a programme introducing digital fabrication technology to prospective biomedical engineering students.',
                'Participants built a simple prosthetic arm design in CAD software before watching it print in the laboratory. The research team walked through the material choices and print tolerances that determine how structurally strong the finished device is.',
                'According to the session lead, workshops like this run regularly to open up health technology research to students early, and to introduce them to the fabrication capacity the laboratory has available.',
            ],
            'image' => '/assets/images/projects/clinical_3d_printing.png',
            'image_alt' => 'Peserta workshop mengamati proses pencetakan 3D prostetik di laboratorium IDIG HTech',
            'image_alt_en' => 'Workshop participants watching a prosthetic being 3D-printed in the IDIG HTech laboratory',
        ],
        [
            'slug' => 'kunjungan-rsud-dr-soetomo-uji-ortosis',
            'date' => '2025-06-05',
            'title' => 'Tim Lab Uji Coba Ortosis Cetak 3D di RSUD Dr. Soetomo',
            'title_en' => 'Lab Team Trials 3D-Printed Orthoses at RSUD Dr. Soetomo',
            'category' => 'Kunjungan',
            'category_en' => 'Visit',
            'excerpt' => 'Kunjungan kerja sama dengan unit rehabilitasi medik menguji kecocokan ortosis kaki hasil cetak 3D pada pasien uji coba.',
            'excerpt_en' => 'A joint visit with the medical rehabilitation unit tested the fit of 3D-printed foot orthoses on trial patients.',
            'body' => [
                'Tim IDIG HTech melakukan kunjungan kerja ke Instalasi Rehabilitasi Medik RSUD Dr. Soetomo, Kamis (5/6), untuk menguji langsung ortosis kaki hasil cetak 3D pada pasien yang telah menyetujui uji coba.',
                'Uji coba difokuskan pada kesesuaian ukuran, kenyamanan pemakaian, dan respons pergerakan sendi pergelangan kaki. Dokter rehabilitasi medik turut memberikan catatan klinis yang menjadi masukan revisi desain.',
                'Kunjungan ini merupakan bagian dari kolaborasi berkelanjutan antara laboratorium dan rumah sakit dalam mengembangkan alat bantu medis yang terjangkau dan sesuai kebutuhan pasien di Indonesia.',
            ],
            'body_en' => [
                'The IDIG HTech team visited the Medical Rehabilitation Installation at RSUD Dr. Soetomo on Thursday 5 June to trial 3D-printed foot orthoses directly on patients who had consented to take part.',
                'The trial focused on sizing, wearing comfort, and how the ankle joint responded through its range of movement. Rehabilitation physicians contributed clinical notes that feed into the next design revision.',
                'The visit is part of an ongoing collaboration between the laboratory and the hospital to develop affordable medical assistive devices matched to the needs of patients in Indonesia.',
            ],
            'image' => '/assets/images/projects/prosthetic_limb_3d.png',
            'image_alt' => 'Ortosis kaki hasil cetak 3D yang diuji coba bersama tim rehabilitasi medik',
            'image_alt_en' => 'A 3D-printed foot orthosis being trialled with the medical rehabilitation team',
        ],
        [
            'slug' => 'juara-kompetisi-inovasi-teknologi-kesehatan-2025',
            'date' => '2025-05-28',
            'title' => 'Tim IDIG Raih Juara Kompetisi Inovasi Teknologi Kesehatan Nasional',
            'title_en' => 'IDIG Team Wins the National Health Technology Innovation Competition',
            'category' => 'Prestasi',
            'category_en' => 'Achievement',
            'excerpt' => 'Purwarupa alat pemantauan pasien berbasis IoT besutan mahasiswa laboratorium meraih juara satu pada ajang inovasi tingkat nasional.',
            'excerpt_en' => 'An IoT-based patient monitoring prototype built by the laboratory’s students took first place at a national innovation competition.',
            'body' => [
                'Tim mahasiswa binaan IDIG HTech meraih juara satu pada Kompetisi Inovasi Teknologi Kesehatan Nasional 2025 melalui purwarupa alat pemantauan pasien berbasis IoT, Rabu (28/5).',
                'Alat tersebut dirancang untuk memantau tanda vital pasien rawat jalan secara nirkabel dan mengirimkan data langsung ke tenaga medis, hasil pengembangan selama satu semester di laboratorium.',
                'Prestasi ini menambah daftar capaian mahasiswa program Teknik Biomedis ITS pada ajang inovasi tingkat nasional, sekaligus menegaskan arah riset laboratorium pada teknologi kesehatan berbasis sensor dan konektivitas.',
            ],
            'body_en' => [
                'A student team mentored by IDIG HTech took first place at the 2025 National Health Technology Innovation Competition on Wednesday 28 May with an IoT-based patient monitoring prototype.',
                'The device monitors outpatient vital signs wirelessly and sends the data straight to clinical staff — the result of a semester of development in the laboratory.',
                'The win adds to the record of ITS Biomedical Engineering students at national innovation events, and reinforces the laboratory’s research direction in sensor- and connectivity-based health technology.',
            ],
            'image' => '/assets/images/projects/patient_monitoring_iot.png',
            'image_alt' => 'Purwarupa alat pemantauan pasien berbasis IoT yang meraih juara satu kompetisi nasional',
            'image_alt_en' => 'The IoT-based patient monitoring prototype that took first place at the national competition',
        ],
        [
            'slug' => 'printer-resin-baru-fabrikasi-presisi',
            'date' => '2025-05-20',
            'title' => 'Printer Resin Baru Perkuat Fabrikasi Presisi Laboratorium',
            'title_en' => 'A New Resin Printer Strengthens the Laboratory’s Precision Fabrication',
            'category' => 'Kabar Lab',
            'category_en' => 'Lab News',
            'excerpt' => 'Unit printer resin generasi terbaru resmi beroperasi, memperluas kapasitas laboratorium mencetak komponen medis berpresisi tinggi.',
            'excerpt_en' => 'A latest-generation resin printer is now in service, widening the laboratory’s capacity to print high-precision medical components.',
            'body' => [
                'Laboratorium IDIG HTech menambah satu unit printer resin generasi terbaru untuk memperkuat kapasitas fabrikasi presisi tinggi, menyusul meningkatnya permintaan pencetakan komponen medis berukuran kecil.',
                'Printer ini digunakan untuk mencetak model uji anatomi, panduan bedah, dan komponen ortotik yang membutuhkan detail permukaan halus dan toleransi ketat — hal yang sulit dicapai teknologi FDM konvensional.',
                'Penambahan unit ini merupakan bagian dari investasi berkelanjutan laboratorium dalam infrastruktur fabrikasi digital, guna menjaga kualitas keluaran riset dan layanan cetak pesanan.',
            ],
            'body_en' => [
                'The IDIG HTech laboratory has added a latest-generation resin printer to strengthen its high-precision fabrication capacity, following rising demand for small medical components.',
                'The printer produces anatomical test models, surgical guides, and orthotic components that need smooth surface detail and tight tolerances — hard to reach with conventional FDM technology.',
                'The addition is part of the laboratory’s continuing investment in digital fabrication infrastructure, keeping the quality of both research output and made-to-order printing services high.',
            ],
            'image' => '/assets/images/projects/stl_medical_devices.png',
            'image_alt' => 'Unit printer resin baru yang digunakan untuk fabrikasi komponen medis presisi tinggi',
            'image_alt_en' => 'The new resin printer unit used for high-precision medical component fabrication',
        ],
        [
            'slug' => 'kolaborasi-riset-fk-universitas-airlangga',
            'date' => '2025-05-09',
            'title' => 'Kolaborasi Riset dengan FK Universitas Airlangga Dimulai',
            'title_en' => 'Research Collaboration with the Universitas Airlangga Faculty of Medicine Begins',
            'category' => 'Kolaborasi',
            'category_en' => 'Collaboration',
            'excerpt' => 'Nota kesepahaman riset bersama Fakultas Kedokteran Universitas Airlangga membuka jalan pengembangan alat bantu medis berbasis kebutuhan klinis.',
            'excerpt_en' => 'A joint research memorandum with the Universitas Airlangga Faculty of Medicine opens the way for clinically driven medical assistive devices.',
            'body' => [
                'IDIG HTech dan Fakultas Kedokteran Universitas Airlangga menandatangani nota kesepahaman riset kolaboratif, Jumat (9/5), untuk mengembangkan alat bantu medis berbasis kebutuhan klinis di lapangan.',
                'Kolaborasi ini akan diawali dengan riset bersama mengenai kebutuhan alat bantu gerak pada pasien pascastroke, melibatkan tim klinis FK Unair dan tim rekayasa dari laboratorium.',
                'Kerja sama lintas institusi semacam ini menjadi salah satu strategi laboratorium untuk memastikan setiap inovasi yang dikembangkan benar-benar menjawab kebutuhan klinis, bukan sekadar kelayakan teknis.',
            ],
            'body_en' => [
                'IDIG HTech and the Universitas Airlangga Faculty of Medicine signed a collaborative research memorandum on Friday 9 May, to develop medical assistive devices around needs observed in clinical practice.',
                'The collaboration opens with joint research into mobility aid requirements for post-stroke patients, bringing together the FK Unair clinical team and the laboratory’s engineering team.',
                'Cross-institution work like this is one of the laboratory’s strategies for making sure every innovation it develops answers a real clinical need rather than merely being technically feasible.',
            ],
            'image' => '/assets/images/projects/medtech_research_digest_v4.png',
            'image_alt' => 'Penandatanganan nota kesepahaman riset kolaboratif dengan FK Universitas Airlangga',
            'image_alt_en' => 'Signing the collaborative research memorandum with the Universitas Airlangga Faculty of Medicine',
        ],
    ],
];
