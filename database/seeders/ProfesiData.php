<?php

namespace Database\Seeders;

/**
 * Data contoh profesi untuk seeder, mengikuti konten Hi-Fi Figma KARIRIS.
 */
final class ProfesiData
{
    /** @return list<array<string, mixed>> */
    public static function all(): array
    {
        return [
            [
                'slug' => 'data-analyst', 'nama' => 'Data Analyst', 'kategori' => 'Technology', 'status' => 'Popular',
                'deskripsi' => 'Data Analyst mengumpulkan, membersihkan, dan menganalisis data untuk menemukan pola serta insight yang membantu perusahaan mengambil keputusan berbasis data.',
                'skill' => 'SQL, Python, Excel, Data Visualization, Statistics, Story Telling',
                'gaji' => 'IDR 6-15 M', 'demand' => 'High', 'burnout' => 70,
                'join' => 'Mulai dari gelar Statistika, Sistem Informasi, atau Matematika. Kuasai SQL dan Excel, ikuti bootcamp atau sertifikasi Google Data Analytics, lalu bangun portofolio analisis dari dataset publik.',
            ],
            [
                'slug' => 'data-scientist', 'nama' => 'Data Scientist', 'kategori' => 'Technology', 'status' => 'Competitive',
                'deskripsi' => 'Data Scientist membangun model statistik dan machine learning untuk memprediksi tren dan mengotomatisasi pengambilan keputusan.',
                'skill' => 'Python, Machine Learning, Statistics, SQL, Deep Learning, Data Visualization',
                'gaji' => 'IDR 10-25 M', 'demand' => 'High', 'burnout' => 65,
                'join' => 'Perkuat dasar matematika dan statistik, pelajari Python beserta library ML, ikuti kompetisi Kaggle, dan publikasikan proyek end-to-end di GitHub.',
            ],
            [
                'slug' => 'data-engineer', 'nama' => 'Data Engineer', 'kategori' => 'Technology', 'status' => 'Emerging',
                'deskripsi' => 'Data Engineer merancang dan memelihara pipeline serta infrastruktur data agar data tersedia, bersih, dan siap dianalisis.',
                'skill' => 'SQL, Python, ETL, Cloud Computing, Apache Spark, Database Design',
                'gaji' => 'IDR 9-22 M', 'demand' => 'High', 'burnout' => 60,
                'join' => 'Pelajari database, SQL lanjutan, dan bahasa pemrograman seperti Python. Lanjutkan ke tools big data dan sertifikasi cloud (AWS/GCP).',
            ],
            [
                'slug' => 'business-analyst', 'nama' => 'Business Analyst', 'kategori' => 'Business', 'status' => 'Stable',
                'deskripsi' => 'Business Analyst menjembatani kebutuhan bisnis dan tim teknologi dengan menganalisis proses, menyusun requirement, dan merekomendasikan solusi.',
                'skill' => 'Requirement Analysis, Communication, SQL, Process Modeling, Excel, Stakeholder Management',
                'gaji' => 'IDR 7-16 M', 'demand' => 'Medium', 'burnout' => 55,
                'join' => 'Latar belakang Sistem Informasi atau Manajemen sangat membantu. Pelajari BPMN, teknik elisitasi requirement, dan ambil sertifikasi seperti ECBA.',
            ],
            [
                'slug' => 'sap-consultant', 'nama' => 'SAP Consultant', 'kategori' => 'Business', 'status' => 'Competitive',
                'deskripsi' => 'SAP Consultant mengimplementasikan dan mengonfigurasi sistem ERP SAP agar sesuai dengan proses bisnis klien.',
                'skill' => 'SAP ERP, Business Process, Communication, Problem Solving, ABAP, Project Management',
                'gaji' => 'IDR 12-30 M', 'demand' => 'High', 'burnout' => 75,
                'join' => 'Pilih satu modul SAP (FI, MM, SD), ikuti pelatihan resmi dan sertifikasi SAP, lalu mulai sebagai associate consultant di perusahaan konsultan.',
            ],
            [
                'slug' => 'uiux-designer', 'nama' => 'UI/UX Designer', 'kategori' => 'Creative', 'status' => 'Popular',
                'deskripsi' => 'UI/UX Designer merancang pengalaman dan tampilan produk digital yang mudah digunakan, berdasarkan riset kebutuhan pengguna.',
                'skill' => 'Figma, User Research, Prototyping, Wireframing, Story Telling, Visual Design',
                'gaji' => 'IDR 6-18 M', 'demand' => 'High', 'burnout' => 60,
                'join' => 'Pelajari prinsip desain dan Figma, lakukan studi kasus redesign aplikasi, lalu susun portofolio yang menampilkan proses riset hingga prototype.',
            ],
            [
                'slug' => 'content-writer', 'nama' => 'Content Writer', 'kategori' => 'Creative', 'status' => 'Stable',
                'deskripsi' => 'Content Writer menulis konten yang informatif dan persuasif untuk website, media sosial, dan materi pemasaran.',
                'skill' => 'Copywriting, SEO, Story Telling, Research, Editing, Social Media',
                'gaji' => 'IDR 4-10 M', 'demand' => 'Medium', 'burnout' => 45,
                'join' => 'Rajin menulis di blog atau Medium, pelajari dasar SEO, dan kumpulkan contoh tulisan terbaik sebagai portofolio.',
            ],
            [
                'slug' => 'product-manager', 'nama' => 'Product Manager', 'kategori' => 'Business', 'status' => 'Competitive',
                'deskripsi' => 'Product Manager menentukan arah produk, memprioritaskan fitur, dan mengoordinasikan tim agar produk memberi nilai bagi pengguna dan bisnis.',
                'skill' => 'User Research, Product Strategy, Communication, Data Analysis, Figma, Story Telling',
                'gaji' => 'IDR 12-35 M', 'demand' => 'High', 'burnout' => 72,
                'join' => 'Mulai dari peran terkait produk (UX, analis, engineer), ikuti program Associate PM, dan pelajari framework prioritas seperti RICE.',
            ],
            [
                'slug' => 'creative-strategist', 'nama' => 'Creative Strategist', 'kategori' => 'Creative', 'status' => 'Emerging',
                'deskripsi' => 'Creative Strategist merumuskan ide kampanye kreatif berdasarkan insight audiens untuk mencapai tujuan brand.',
                'skill' => 'Story Telling, Market Research, Copywriting, Branding, Figma, Presentation',
                'gaji' => 'IDR 8-20 M', 'demand' => 'Medium', 'burnout' => 58,
                'join' => 'Bangun pemahaman pemasaran dan perilaku konsumen, ikuti kompetisi ide kampanye, dan magang di agensi kreatif.',
            ],
        ];
    }

    /** @return list<array<string, string>> */
    public static function rutinitas(string $kategori): array
    {
        $data = match ($kategori) {
            'Business' => [
                ['09.00 - 10.00', 'Morning Briefing', 'meeting', 'Menyamakan prioritas harian bersama tim dan stakeholder.'],
                ['10.00 - 12.00', 'Stakeholder Interview', 'chat', 'Menggali kebutuhan dan masalah bisnis langsung dari pengguna.'],
                ['13.00 - 14.00', 'Process Mapping', 'task', 'Memetakan proses bisnis saat ini dan usulan perbaikannya.'],
                ['14.00 - 15.00', 'Lunch Break', 'break', 'Istirahat makan siang dan recharge energi.'],
                ['15.00 - 16.30', 'Requirement Writing', 'doc', 'Menyusun dokumen requirement dan user story.'],
                ['16.30 - 17.30', 'Review Meeting', 'meeting', 'Meninjau progres bersama tim teknis.'],
            ],
            'Creative' => [
                ['09.00 - 09.30', 'Daily Standup', 'meeting', 'Update progres dan hambatan bersama tim kreatif.'],
                ['09.30 - 11.30', 'Research & Moodboard', 'search', 'Mengumpulkan referensi dan insight pengguna.'],
                ['11.30 - 13.00', 'Ideation Session', 'idea', 'Brainstorming konsep bersama tim.'],
                ['13.00 - 14.00', 'Lunch Break', 'break', 'Istirahat makan siang dan recharge energi.'],
                ['14.00 - 16.30', 'Deep Work Design', 'design', 'Fokus mengerjakan desain atau konten utama.'],
                ['16.30 - 17.30', 'Feedback Review', 'chat', 'Mempresentasikan hasil dan menerima masukan.'],
            ],
            default => [
                ['09.00 - 11.00', 'Daily Standup', 'meeting', 'Rapat singkat harian untuk berbagi progres kemarin, rencana hari ini, dan hambatan yang dihadapi tim.'],
                ['11.30 - 13.00', 'Code Review', 'code', 'Meninjau kode atau query rekan tim untuk menjaga kualitas dan konsistensi.'],
                ['13.00 - 14.00', 'Deep Work Coding', 'code', 'Waktu fokus tanpa gangguan untuk mengerjakan analisis atau fitur utama.'],
                ['14.30 - 15.30', 'Lunch Break', 'break', 'Istirahat makan siang dan recharge energi.'],
                ['15.30 - 17.00', 'Sprint Planning', 'meeting', 'Merencanakan pekerjaan untuk sprint berikutnya bersama tim.'],
                ['17.00 - 18.00', 'Bug Fixing', 'bug', 'Menyelesaikan bug atau anomali data yang ditemukan.'],
            ],
        };

        return array_map(fn (array $r) => [
            'jam' => $r[0], 'kegiatan' => $r[1], 'ikon' => $r[2], 'deskripsi' => $r[3],
        ], $data);
    }

    /** @return list<array<string, mixed>> */
    public static function fase(): array
    {
        $data = [
            ['Requirement', 'Analisis kebutuhan sistem', '1 minggu', 'Sprint 1', 'doc', 'Mengumpulkan dan menganalisis kebutuhan dari stakeholder, lalu menuangkannya dalam dokumen requirement yang disepakati bersama.'],
            ['System Design', 'Skema basis data & arsitektur', '1 minggu', 'Sprint 1', 'design', 'Merancang arsitektur solusi, skema basis data, dan alur data sebelum implementasi dimulai.'],
            ['Development', 'Implementasi fitur inti', '1 minggu', 'Sprint 2', 'code', 'Mengimplementasikan fitur inti sesuai desain dan requirement yang telah disetujui.'],
            ['Testing', 'Pengujian & QA', '3 hari', 'Sprint 3', 'bug', 'Menguji fungsionalitas, memperbaiki bug, dan memastikan kualitas sebelum rilis.'],
            ['Deployment', 'Rilis ke produksi', '2 hari', 'Sprint 3', 'rocket', 'Merilis hasil ke lingkungan produksi dan memantau performanya.'],
        ];

        return array_map(fn (array $f, int $i) => [
            'urutan' => $i + 1, 'nama_fase' => $f[0], 'ringkasan' => $f[1], 'durasi' => $f[2],
            'sprint' => $f[3], 'ikon' => $f[4], 'deskripsi' => $f[5],
        ], $data, array_keys($data));
    }
}
