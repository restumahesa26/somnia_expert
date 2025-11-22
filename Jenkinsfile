pipeline {
    agent any

    parameters {
        // 1. Menu Pilihan: Mau ngapain?
        choice(
            name: 'ACTION',
            choices: ['Build & Deploy Staging', 'Promote to Production'],
            description: 'Pilih aksi yang ingin dilakukan'
        )

        // 2. Input Versi: Mau deploy versi berapa ke Production?
        // (Hanya diisi kalau memilih Promote)
        string(
            name: 'IMAGE_TAG',
            defaultValue: 'latest',
            description: 'Masukkan Tag/Nomor Build untuk Production (Contoh: 25 atau latest). Lihat di Riwayat Build.'
        )
    }

    environment {
        IMAGE_NAME = "somnia-app"
        CONTAINER_DEV = "laravel-staging"
        CONTAINER_PROD = "laravel-production"
        PATH_SECRETS = "/var/jenkins_home/secrets"

        // Kita gunakan Nomor Build Jenkins sebagai versi (Tag) otomatis
        // Contoh: somnia-app:1, somnia-app:2, dst.
        CURRENT_TAG = "${env.BUILD_NUMBER}"
    }

    stages {
        // ============================================================
        // MODE 1: BUILD & STAGING (Hanya jalan jika Action = Build)
        // ============================================================

        stage('Build Image') {
            when { expression { params.ACTION == 'Build & Deploy Staging' } }
            steps {
                script {
                    echo "🔨 Membangun Image Versi: ${CURRENT_TAG}..."
                    sh 'cp .env.example .env'

                    // Kita build dengan 2 Tag: Nomor Build (unik) dan Latest
                    sh "docker build -t ${IMAGE_NAME}:${CURRENT_TAG} ."
                    sh "docker tag ${IMAGE_NAME}:${CURRENT_TAG} ${IMAGE_NAME}:latest"
                }
            }
        }

        stage('Deploy to Staging') {
            when { expression { params.ACTION == 'Build & Deploy Staging' } }
            steps {
                script {
                    echo "🚀 Deploy ke Staging (Versi ${CURRENT_TAG})..."
                    sh "docker rm -f ${CONTAINER_DEV} || true"

                    sh """
                        docker run -d --name ${CONTAINER_DEV} \
                        -p 8080:80 \
                        --network laravel-net \
                        -v ${PATH_SECRETS}/.env.staging:/var/www/.env \
                        ${IMAGE_NAME}:${CURRENT_TAG}
                    """

                    sleep 5
                    sh "docker exec ${CONTAINER_DEV} php artisan migrate --force"

                    echo "✅ Sukses! Image versi ${CURRENT_TAG} sudah ada di Staging."
                    echo "Ingat nomor '${CURRENT_TAG}' ini untuk Promote nanti."
                }
            }
        }

        // ============================================================
        // MODE 2: PRODUCTION (Hanya jalan jika Action = Promote)
        // ============================================================

        stage('Deploy to Production') {
            when { expression { params.ACTION == 'Promote to Production' } }
            steps {
                script {
                    echo "🏆 Mempromosikan Image Versi: ${params.IMAGE_TAG} ke Production..."

                    // Cek dulu apakah image versinya ada?
                    // (Command ini akan error kalau image gak ada)
                    sh "docker inspect ${IMAGE_NAME}:${params.IMAGE_TAG} > /dev/null"

                    sh "docker rm -f ${CONTAINER_PROD} || true"

                    sh """
                        docker run -d --name ${CONTAINER_PROD} \
                        -p 9090:80 \
                        --network laravel-net \
                        -v ${PATH_SECRETS}/.env.production:/var/www/.env \
                        ${IMAGE_NAME}:${params.IMAGE_TAG}
                    """

                    sleep 5
                    sh "docker exec ${CONTAINER_PROD} php artisan migrate --force"
                }
            }
        }
    }
}
