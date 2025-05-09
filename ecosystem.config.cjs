module.exports = {
    apps: [{
        name: 'ffp-events-laravel-queue-worker',
        script: 'artisan',
        args: 'queue:work', // Add your queue options
        interpreter: 'php',
        cwd: '/var/www/ffp-events-live/app', // Set your app path
        user: 'www-data', // <--- THIS IS THE KEY
        group: 'www-data', // <--- AND THIS
        exec_mode: 'fork', // Or 'cluster' if you run multiple instances
        instances: 1,       // Or more if needed
        autorestart: true,
        watch: false,   // Set to true if you want PM2 to restart on file changes (careful with vendor)
        max_memory_restart: '1G' // Example
    }]
};
