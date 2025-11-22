pipeline {
    agent any

    environment {
        IMAGE_NAME = "somnia-app"
        CONTAINER_DEV = "laravel-staging"
        CONTAINER_PROD = "laravel-production"

        // Ganti path ini sesuai lokasi folder jenkins-secrets di laptopmu!
        // Contoh: Jika kamu pakai Windows/WSL, pastikan path-nya absolut.
        // Untuk tutorial ini, kita asumsikan file rahasia ada di folder /tmp/secrets/ di laptop
        // (Nanti saya jelaskan cara map-nya di bawah kode ini)
        PATH_SECRETS = "/var/jenkins_home/secrets"
    }

    stages {
        // === TAHAP 1: MEMASAK (Build) ===
        stage('Build Image') {
            steps {
                // Kita copy .env.example jadi .env DUMMY dulu supaya Composer gak error saat build
                sh 'cp .env.example .env'
                sh "docker build -t ${IMAGE_NAME}:latest ."
            }
        }

        // === TAHAP 2: STAGING ===
        stage('Deploy to Staging') {
            steps {
                echo '--- Deploy Staging (Port 8080) ---'
                sh "docker rm -f ${CONTAINER_DEV} || true"

                // Perhatikan baris -v untuk menyuntikkan .env.staging
                sh """
                    docker run -d \
                    --name ${CONTAINER_DEV} \
                    -p 8080:80 \
                    --network laravel-net \
                    -v ${PATH_SECRETS}/.env.staging:/var/www/.env \
                    ${IMAGE_NAME}:latest
                """

                // Tunggu 5 detik biar MySQL siap mental
                sleep 5

                echo '--- Menjalankan Migrasi Staging ---'
                // Kita paksa migrate jalan (gunakan --force agar tidak tanya "Are you sure?")
                sh "docker exec ${CONTAINER_DEV} php artisan migrate --force"
            }
        }

        // === TAHAP 3: PROMOTE ===
        stage('Promote?') {
            steps {
                script {
                    input message: 'Staging Aman? Lanjut ke Production?', ok: 'GAS!'
                }
            }
        }

        // === TAHAP 4: PRODUCTION ===
        stage('Deploy to Production') {
            steps {
                echo '--- Deploy Production (Port 9090) ---'
                sh "docker rm -f ${CONTAINER_PROD} || true"

                // Suntikkan .env.production
                sh """
                    docker run -d \
                    --name ${CONTAINER_PROD} \
                    -p 9090:80 \
                    --network laravel-net \
                    -v ${PATH_SECRETS}/.env.production:/var/www/.env \
                    ${IMAGE_NAME}:latest
                """

                sleep 5

                echo '--- Menjalankan Migrasi Production ---'
                sh "docker exec ${CONTAINER_PROD} php artisan migrate --force"
            }
        }
    }
}
