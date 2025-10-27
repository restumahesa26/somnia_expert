<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penyakit;

class PenyakitSeeder extends Seeder
{
    public function run()
    {
        $penyakits = [
            [
                'kode_penyakit' => 'P01',
                'nama_penyakit' => 'Insomnia',
                'deskripsi' => 'Insomnia adalah kondisi ketika seseorang sulit tidur atau butuh waktu yang sangat lama sampai bisa tidur. Penyebabnya bisa kebiasaan sebelum tidur yang tidak baik, gangguan mental, restless legs syndrome, atau gangguan kelenjar pineal.',
                'solusi' => 'Menerapkan kebiasaan tidur yang baik, mengelola stres, dan menghindari stimulan sebelum tidur. Jika diperlukan, konsultasi dengan profesional kesehatan untuk penanganan lebih lanjut.',
            ],
            [
                'kode_penyakit' => 'P02',
                'nama_penyakit' => 'Obstructive Sleep Apnea (OSA)',
                'deskripsi' => 'OSA adalah gangguan pernapasan saat tidur yang ditandai dengan obstruksi jalan napas dan menyebabkan napas berhenti sesaat. Ditandai dengan mengorok, sesak napas saat tidur, dan rasa lelah meskipun tidur lama.',
                'solusi' => 'Mengubah gaya hidup seperti menurunkan berat badan, menghindari alkohol, dan tidur pada posisi tertentu. Penggunaan alat bantu pernapasan (CPAP) atau tindakan medis lainnya mungkin diperlukan sesuai anjuran dokter.',
            ],
            [
                'kode_penyakit' => 'P03',
                'nama_penyakit' => 'Hipersomnia',
                'deskripsi' => 'Hipersomnia adalah kondisi di mana penderitanya tidur sangat panjang dan tetap mengantuk di siang hari. Salah satu penyebabnya adalah depresi.',
                'solusi' => 'Menjaga pola tidur yang teratur, menghindari stimulan sebelum tidur, dan berkonsultasi dengan profesional kesehatan untuk penanganan lebih lanjut jika diperlukan.',
            ],
            [
                'kode_penyakit' => 'P04',
                'nama_penyakit' => 'Sleepwalking',
                'deskripsi' => 'Sleepwalking atau somnabulisme adalah kondisi ketika seseorang berjalan atau melakukan aktivitas dalam keadaan tidur tanpa menyadarinya.',
                'solusi' => 'Menciptakan lingkungan tidur yang aman, menghindari stres, dan menjaga pola tidur yang teratur. Jika sleepwalking sering terjadi atau membahayakan, konsultasi dengan profesional kesehatan dianjurkan.',
            ],
            [
                'kode_penyakit' => 'P05',
                'nama_penyakit' => 'Gangguan Tidur Ritme Sirkadian',
                'deskripsi' => 'Gangguan tidur ritme sirkadian terjadi ketika jam biologis tubuh tidak selaras dengan siklus siang-malam 24 jam sehingga menyebabkan kesulitan tidur dan bangun di waktu normal.',
                'solusi' => 'Menjaga pola tidur yang konsisten, mengatur paparan cahaya alami, dan menghindari stimulan sebelum tidur. Konsultasi dengan profesional kesehatan jika gangguan berlanjut.',
            ],
        ];

        foreach ($penyakits as $penyakit) {
            Penyakit::create($penyakit);
        }
    }
}
