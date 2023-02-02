pipeline {
    agent { label 'local' }

    stages {
        stage('install/update php libraries ') {
            steps {
                sh '$DIR_PATHp/dockerize_laravel/shell-scripts/git_actions.sh'
            }
        }
        stage('build and start docker') {
            steps {
                sh '$DIR_PATH/dockerize_laravel/shell-scripts/build.sh'
            }
        }
        stage('execute artisan commands') {
            steps {
                sh '$DIR_PATH/dockerize_laravel/shell-scripts/artisan.sh'
            }
        }
    }
}
