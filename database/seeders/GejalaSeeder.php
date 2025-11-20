<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gejala;

class GejalaSeeder extends Seeder
{
    public function run()
    {
        $gejalas = [
            ['kode_gejala' => 'G01', 'nama_gejala' => 'Butuh waktu yang sangat lama untuk bisa terlelap setelah berbaring.'],
            ['kode_gejala' => 'G02', 'nama_gejala' => 'Sering terbangun di tengah malam/Kesulitan untuk tetap tidur di malam hari'],
            ['kode_gejala' => 'G03', 'nama_gejala' => 'Sulit untuk tidur kembali ketika sudah terbangun'],
            ['kode_gejala' => 'G04', 'nama_gejala' => 'Terbangun jauh lebih awal dari yang diinginkan'],
            ['kode_gejala' => 'G05', 'nama_gejala' => 'Kantuk berlebihan di siang hari'],
            ['kode_gejala' => 'G06', 'nama_gejala' => 'Kesulitan berkonsentrasi'],
            ['kode_gejala' => 'G07', 'nama_gejala' => 'Kesulitan tidur terjadi meskipun memiliki kesempatan tidur yang cukup'],
            ['kode_gejala' => 'G08', 'nama_gejala' => 'Sulit tidur bukan disebabkan oleh pengaruh zat atau obat-obatan tertentu.'],
            ['kode_gejala' => 'G09', 'nama_gejala' => 'Adanya perasaan khawatir berlebihan dengan kualitas tidur yang tidak maksimal'],
            ['kode_gejala' => 'G10', 'nama_gejala' => 'Gangguan terjadi minimal 3 kali dalam seminggu selama minimal 1 bulan'],
            ['kode_gejala' => 'G11', 'nama_gejala' => 'Perasaan tidur yang tidak menyegarkan.'],
            ['kode_gejala' => 'G12', 'nama_gejala' => 'Terdapat hasil pemeriksaan polisomnografi yang menunjukkan terjadinya Obstructive Sleep Apnea'],
            ['kode_gejala' => 'G13', 'nama_gejala' => 'Mengalami jeda napas berlebihan saat tidur yang mengganggu kualitas tidur'],
            ['kode_gejala' => 'G14', 'nama_gejala' => 'Mendengkur sangat keras dan diselingi periode hening'],
            ['kode_gejala' => 'G15', 'nama_gejala' => 'Tersedak atau ter engah-engah kesulitan bernapas di tengah kondisi tidur.'],
            ['kode_gejala' => 'G16', 'nama_gejala' => 'Kondisi fisik mengalami kelelahan yang disebabkan terganggunya aktivitas tidur'],
            ['kode_gejala' => 'G17', 'nama_gejala' => 'Tidak disebabkan oleh gangguan mental lainnya'],
            ['kode_gejala' => 'G18', 'nama_gejala' => 'Sakit tenggorokan, mulut kering, atau sakit kepala saat bangun di pagi/siang hari.'],
            ['kode_gejala' => 'G19', 'nama_gejala' => 'Gerakan tubuh mendadak atau berkedut saat tidur.'],
            ['kode_gejala' => 'G20', 'nama_gejala' => 'Gerakan gelisah dan berbalik-balik.'],
            ['kode_gejala' => 'G21', 'nama_gejala' => 'Perubahan kepribadian, seperti perubahan mood dan kesulitan bergaul dengan orang lain.'],
            ['kode_gejala' => 'G22', 'nama_gejala' => 'Kesulitan mengingat hal-hal seperti tanggal dan nama, serta menjadi lebih pelupa (Masalah dengan ingatan yang buruk)'],
            ['kode_gejala' => 'G23', 'nama_gejala' => 'Tidur dengan waktu yang sangat lama, lebih dari 9 jam'],
            ['kode_gejala' => 'G24', 'nama_gejala' => 'Merasa sangat mengantuk atau mudah tertidur di situasi yang tidak seharusnya.'],
            ['kode_gejala' => 'G25', 'nama_gejala' => 'Mengalami periode tidur berulang, meski sudah tidur sebelumnya'],
            ['kode_gejala' => 'G26', 'nama_gejala' => 'Kesulitan untuk bangun'],
            ['kode_gejala' => 'G27', 'nama_gejala' => 'Terjaga sepenuhnya setelah terbangun secara tiba-tiba.'],
            ['kode_gejala' => 'G28', 'nama_gejala' => 'Gangguan tersebut terjadi setiap hari selama lebih dari 1 bulan'],
            ['kode_gejala' => 'G29', 'nama_gejala' => 'Perasaan sedih, putus asa, dan pesimis yang sering muncul.'],
            ['kode_gejala' => 'G30', 'nama_gejala' => 'Mengalami halusinasi sesaat ketika akan tertidur.'],
            ['kode_gejala' => 'G31', 'nama_gejala' => 'Mudah teralhkan dan impulsif.'],
            ['kode_gejala' => 'G32', 'nama_gejala' => 'Pola tidur yang tidak normal dalam kualitas, kuantitas, atau karakteristiknya.'],
            ['kode_gejala' => 'G33', 'nama_gejala' => 'Ketidakmampuan untuk menggerakkan tubuh saat akan tidur atau saat bangun tidur.'],
            ['kode_gejala' => 'G34', 'nama_gejala' => 'Mendengkur / Pernapasan yang dalam dan berisik saat tidur.'],
            ['kode_gejala' => 'G35', 'nama_gejala' => 'Melakukan aktivitas yang biasa dilakukan saat terjaga (seperti: berjalan di sekitar rumah, menyiapkan peralatan, membuka tutup barang, hingga keluar rumah. dsb)'],
            ['kode_gejala' => 'G36', 'nama_gejala' => 'Menggumam atau mengucapkan kalimat yang tidak jelas saat tidur.'],
            ['kode_gejala' => 'G37', 'nama_gejala' => 'Tidak sadar akan lingkungan sekitar meskipun mata terbuka dan bergerak'],
            ['kode_gejala' => 'G38', 'nama_gejala' => 'Tidak mampu mengingat kejadian saat episode tidur berjalan.'],
            ['kode_gejala' => 'G39', 'nama_gejala' => 'Sulit dibangunkan saat sedang tidur berjalan'],
            ['kode_gejala' => 'G40', 'nama_gejala' => 'Kebingungan setelah terbangun pasca kondisi tidur berjalan.'],
            ['kode_gejala' => 'G41', 'nama_gejala' => 'Merasa takut atau agresif saat terbangun pasca kondisi tidur berjalan'],
            ['kode_gejala' => 'G42', 'nama_gejala' => 'Jadwal tidur internal tubuh tidak selaras dengan jadwal yang diinginkan atau dibutuhkan secara sosial.'],
            ['kode_gejala' => 'G43', 'nama_gejala' => 'Terjaga di malam hari dan baru tidur di siang hari'],
            ['kode_gejala' => 'G44', 'nama_gejala' => 'Tidur sangat larut malam (misalnya, pukul 2-6 pagi) dan bangun sangat siang.'],
            ['kode_gejala' => 'G45', 'nama_gejala' => 'Merasa sangat mengantuk di sore atau awal malam (misalnya, pukul 6-9 malam) dan bangun sangat dini hari.'],
            ['kode_gejala' => 'G46', 'nama_gejala' => 'Tidur dengan banyak periode namun singkat dan tersebar sepanjang 24 jam'],
            ['kode_gejala' => 'G47', 'nama_gejala' => 'Waktu tidur dan bangun secara bertahap terus bergeser lebih lambat setiap harinya.'],
        ];

        foreach ($gejalas as $gejala) {
            Gejala::create($gejala);
        }
    }
}
