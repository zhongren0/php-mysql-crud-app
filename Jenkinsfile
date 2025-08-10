pipeline {
    agent any // This pipeline can run on any available Jenkins agent

    environment {
        // Your Docker Hub username
        DOCKER_HUB_USER   = 'gzr01' 
        // The name of the image on Docker Hub
        DOCKER_IMAGE_NAME = 'php-mysql-crud-app'
        // Kubernetes details from your project
        K8S_NAMESPACE     = 'php-mysql'
        K8S_DEPLOYMENT    = 'php-app'
    }

    stages {
        stage('1. Checkout Code') {
            steps {
                echo 'Checking out source code from Git...'
                checkout scm
            }
        }

        stage('2. Build Docker Image') {
            steps {
                script {
                    // We tag the image with the unique Jenkins build number for versioning
                    def imageName = "${DOCKER_HUB_USER}/${DOCKER_IMAGE_NAME}:${env.BUILD_NUMBER}"
                    echo "Building Docker image: ${imageName}"
                    // Build from the 'php' subdirectory where the Dockerfile is located
                    sh "docker build -t ${imageName} ./php"
                }
            }
         }
          stage('3. Security Scan with Trivy') {
    steps {
        script {
            def imageName = "${DOCKER_HUB_USER}/${DOCKER_IMAGE_NAME}:${env.BUILD_NUMBER}"
            echo "Scanning ${imageName} for HIGH and CRITICAL vulnerabilities..."

            sh """
                docker run --rm \\
                    -v /var/run/docker.sock:/var/run/docker.sock \\
                    -v ${WORKSPACE}/.trivyignore:/tmp/.trivyignore \\
                    aquasec/trivy image --timeout 15m --exit-code 1 --severity HIGH,CRITICAL --ignorefile /tmp/.trivyignore ${imageName}
            """
        }
    }
}
          stage('4. Push Image to Docker Hub') {
            steps {
                script {
                    def imageName = "${DOCKER_HUB_USER}/${DOCKER_IMAGE_NAME}:${env.BUILD_NUMBER}"
                    echo "Logging in and pushing ${imageName} to Docker Hub..."
                    // Use the Jenkins credential with the ID 'dockerhub-credentials'
                    withCredentials([usernamePassword(credentialsId: 'dockerhub-credentials', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                        sh "docker login -u '${DOCKER_USER}' -p '${DOCKER_PASS}'"
                        sh "docker push ${imageName}"
                    }
                }
            }
        }

        stage('5. Deploy to Kubernetes') {
            steps {
                script {
                    def imageName = "${DOCKER_HUB_USER}/${DOCKER_IMAGE_NAME}:${env.BUILD_NUMBER}"
                    echo "Deploying new image to Kubernetes..."
                    // Use the Jenkins credential with the ID 'kubeconfig-file'
                    withKubeConfig([credentialsId: 'kubeconfig-file']) {
                        // Perform a rolling update of the deployment with the new image
                        sh "kubectl set image deployment/${K8S_DEPLOYMENT} php=${imageName} -n ${K8S_NAMESPACE}"
                        
                        echo "Waiting for deployment rollout to complete..."
                        sh "kubectl rollout status deployment/${K8S_DEPLOYMENT} -n ${K8S_NAMESPACE}"
                    }
                }
            }
        }
    }
    post {
        always {
            // This block runs after the pipeline finishes, regardless of status
            echo 'Cleaning up...'
            sh 'docker logout'
        }
    }
}
