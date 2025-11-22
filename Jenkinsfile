pipeline {
  agent any
  stages {
    stage('Pipeline: Build & Staging') {
      when {
        expression {
          return params.MODE_OPERASI == '1. Build & Deploy Staging'
        }

      }
      steps {
        script {
          echo "🔨 [MODE 1] Memulai Build & Deploy Staging..."
          echo "ℹ️  Versi Build ini adalah: ${env.BUILD_NUMBER}"

          // 1. Copy Env Dummy
          sh 'cp .env.example .env'

          // 2. Build Image dengan Tag Angka Unik
          sh "docker build -t ${IMAGE_NAME}:${env.BUILD_NUMBER} ."
          // Tag juga sebagai latest
          sh "docker tag ${IMAGE_NAME}:${env.BUILD_NUMBER} ${IMAGE_NAME}:latest"

          echo "🚀 Deploy ke Staging Port ${PORT_STAGING}..."

          // 3. Reset Container Staging
          sh "docker rm -f ${CONTAINER_DEV} || true"

          // 4. Jalankan Container Staging
          sh """
          docker run -d --name ${CONTAINER_DEV} \
          -p ${PORT_STAGING}:80 \
          --network laravel-net \
          -v ${PATH_SECRETS}/.env.staging:/var/www/.env \
          ${IMAGE_NAME}:${env.BUILD_NUMBER}
          """

          // 5. Migrasi
          sleep 5
          sh "docker exec ${CONTAINER_DEV} php artisan migrate --force"

          echo "✅ Selesai! Ingat nomor versi '${env.BUILD_NUMBER}' ini untuk Promote nanti."
        }

      }
    }

    stage('Pipeline: Promote Production') {
      when {
        expression {
          return params.MODE_OPERASI == '2. Promote to Production'
        }

      }
      steps {
        script {
          echo "🏆 [MODE 2] Memulai Promote ke Production..."
          echo "ℹ️  Mengambil Image Versi: ${params.VERSION_TO_PROMOTE}"

          // Cek apakah user lupa isi nomor versi?
          if (params.VERSION_TO_PROMOTE == 'latest' || params.VERSION_TO_PROMOTE == '') {
            error "⛔ STOP! Kamu harus memasukkan Nomor Build (angka) untuk Promote. Jangan pakai 'latest'."
          }

          // Cek apakah image versi tersebut ada?
          sh "docker inspect ${IMAGE_NAME}:${params.VERSION_TO_PROMOTE} > /dev/null"

          echo "🚀 Deploy ke Production Port ${PORT_PROD}..."

          // 1. Reset Container Production
          sh "docker rm -f ${CONTAINER_PROD} || true"

          // 2. Jalankan Container Production (Pakai versi pilihanmu)
          sh """
          docker run -d --name ${CONTAINER_PROD} \
          -p ${PORT_PROD}:80 \
          --network laravel-net \
          -v ${PATH_SECRETS}/.env.production:/var/www/.env \
          ${IMAGE_NAME}:${params.VERSION_TO_PROMOTE}
          """

          // 3. Migrasi
          sleep 5
          sh "docker exec ${CONTAINER_PROD} php artisan migrate --force"

          echo "✅ Sukses! Production sekarang menggunakan versi ${params.VERSION_TO_PROMOTE}"
        }

      }
    }

  }
  environment {
    IMAGE_NAME = "${env.PROYEK_NAMA_IMAGE}"
    CONTAINER_DEV = "${env.PROYEK_NAMA_CONTAINER}-staging"
    CONTAINER_PROD = "${env.PROYEK_NAMA_CONTAINER}-prod"
    PORT_STAGING = "${env.PROYEK_PORT_STAGING}"
    PORT_PROD = "${env.PROYEK_PORT_PROD}"
    PATH_SECRETS = '/var/jenkins_home/secrets'
  }
  parameters {
    choice(name: 'MODE_OPERASI', choices: ['1. Build & Deploy Staging', '2. Promote to Production'], description: 'Pilih mau update Staging atau update Production?')
    string(name: 'VERSION_TO_PROMOTE', defaultValue: 'latest', description: 'Khusus Promote: Masukkan Angka Build Number yang mau dideploy (Contoh: 25)')
  }
}