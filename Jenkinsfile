pipeline {
    agent any

    // 1. PARAMETER (MENU PILIHAN) WAJIB DI ATAS
    parameters {
        choice(
            name: 'MODE_OPERASI',
            choices: ['1. Build & Deploy Staging', '2. Promote to Production'],
            description: 'Mau update Staging atau Production?'
        )
        string(
            name: 'VERSION_TO_PROMOTE',
            defaultValue: 'latest',
            description: 'Khusus Promote: Masukkan Angka Build Number (Contoh: 25)'
        )
    }

    // 2. ENVIRONMENT (VARIABEL) WAJIB DI ATAS
    environment {
        // Mengambil variabel dari Settingan Job Jenkins (UI)
        // Pastikan kamu sudah setting ini di Dashboard Jenkins!
        IMAGE_NAME = "${env.PROYEK_NAMA_IMAGE}"

        CONTAINER_DEV = "${env.PROYEK_NAMA_CONTAINER}-staging"
        CONTAINER_PROD = "${env.PROYEK_NAMA_CONTAINER}-prod"

        PORT_STAGING = "${env.PROYEK_PORT_STAGING}"
        PORT_PROD = "${env.PROYEK_PORT_PROD}"

        PATH_SECRETS = "/var/jenkins_home/secrets"
    }

    stages {
        // === KARTU 1: PIPELINE STAGING ===
        stage('Pipeline: Build & Staging') {
            when { expression { return params.MODE_OPERASI == '1. Build & Deploy Staging' } }

            steps {
                script {
                    echo "🔨 [MODE 1] Memulai Build & Staging..."

                    // ... (kode copy .env dan build image tetap sama) ...
                    sh 'cp .env.example .env'
                    sh "docker build -t ${IMAGE_NAME}:${env.BUILD_NUMBER} ."
                    sh "docker tag ${IMAGE_NAME}:${env.BUILD_NUMBER} ${IMAGE_NAME}:latest"

                    echo "🚀 Deploy ke Staging Port ${PORT_STAGING}..."
                    sh "docker rm -f ${CONTAINER_DEV} || true"

                    // --- TAMBAHAN BARU DI SINI ---
                    // Buat network jika belum ada (|| true artinya: kalau sudah ada, jangan error, lanjut aja)
                    sh "docker network create laravel-net || true"
                    // -----------------------------

                    sh """
                        docker run -d --name ${CONTAINER_DEV} \
                        -p ${PORT_STAGING}:80 \
                        --network laravel-net \
                        -v ${PATH_SECRETS}/.env.staging:/var/www/.env \
                        ${IMAGE_NAME}:${env.BUILD_NUMBER}
                    """

                    // ... (sisa kode migrasi tetap sama) ...
                    sleep 5
                    sh "docker exec ${CONTAINER_DEV} php artisan migrate --force"
                }
            }
        }

        // === KARTU 2: PROMOTE PRODUCTION ===
        stage('Pipeline: Promote Production') {
            when { expression { return params.MODE_OPERASI == '2. Promote to Production' } }

            steps {
                script {
                    echo "🏆 [MODE 2] Promote Versi ${params.VERSION_TO_PROMOTE} ke Production..."

                    if (params.VERSION_TO_PROMOTE == 'latest' || params.VERSION_TO_PROMOTE == '') {
                        error "⛔ STOP! Masukkan Angka Build Number di kolom parameter."
                    }

                    // Cek image dulu
                    sh "docker inspect ${IMAGE_NAME}:${params.VERSION_TO_PROMOTE} > /dev/null"

                    sh "docker rm -f ${CONTAINER_PROD} || true"

                    sh """
                        docker run -d --name ${CONTAINER_PROD} \
                        -p ${PORT_PROD}:80 \
                        --network laravel-net \
                        -v ${PATH_SECRETS}/.env.production:/var/www/.env \
                        ${IMAGE_NAME}:${params.VERSION_TO_PROMOTE}
                    """

                    sleep 5
                    // 1. Cek dulu ada file apa di dalam folder /var/www?
                    sh "docker exec ${CONTAINER_DEV} ls -la /var/www"

                    // 2. Baru jalankan migrate
                    sh "docker exec ${CONTAINER_DEV} php /var/www/artisan migrate --force"

                    echo "✅ Sukses! Production sekarang versi ${params.VERSION_TO_PROMOTE}"
                }
            }
        }
    }
}
